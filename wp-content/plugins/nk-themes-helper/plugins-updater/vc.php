<?php
/**
 * Check for WPB Page Builder plugins updates
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class NKTH_VC_Plugin_Updater
 */
class NKTH_VC_Plugin_Updater {
    /**
     * Plugin name
     *
     * @var array
     */
    private $plugin_name = 'js_composer';

    /**
     * NKTH_VC_Plugin_Updater constructor.
     */
    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
    }

    /**
     * After setup theme.
     */
    public function after_setup_theme() {
        $is_elements      = nkth()->theme_dashboard()->is_envato_elements && 'elements' === nkth()->theme_dashboard()->options['purchase_platform'];
        $is_envato_hosted = nkth()->theme_dashboard()->is_envato_hosted;

        // For active themes only.
        if ( $is_elements || $is_envato_hosted || ! nkth()->theme_dashboard()->activation()->active ) {
            return;
        }

        // Already active.
        if ( get_option( 'wpb_js_js_composer_purchase_code' ) ) {
            return;
        }

        // Don't run on VC Settings page to prevent conflicts.
        // phpcs:ignore
        if ( isset( $_GET['page'] ) && 'vc-updater' === $_GET['page'] ) {
            return;
        }

        // Return VC fake license to prevent notices.
        add_filter( 'option_wpb_js_js_composer_purchase_code', array( $this, 'js_composer_license' ), 20, 1 );
        add_filter( 'pre_option_wpb_js_js_composer_purchase_code', array( $this, 'js_composer_license' ), 20, 1 );
        add_filter( 'site_option_wpb_js_js_composer_purchase_code', array( $this, 'js_composer_license' ), 20, 1 );
        add_filter( 'pre_site_option_wpb_js_js_composer_purchase_code', array( $this, 'js_composer_license' ), 20, 1 );

        // Prevent VC upgrader work.
        add_filter( 'upgrader_pre_download', array( $this, 'js_composer_pre_download_1' ), 1, 4 );
        add_filter( 'upgrader_pre_download', array( $this, 'js_composer_pre_download_2' ), 20, 4 );

        // Modify update information for premium plugins.
        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'modify_plugin_transient' ), 20, 1 );
    }

    /**
     * Get TGM plugin data
     *
     * @return array
     */
    public function get_tgm_plugin_data() {
        if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
            return false;
        }

        $plugins = TGM_Plugin_Activation::$instance->plugins;

        if ( isset( $plugins[ $this->plugin_name ] ) ) {
            return $plugins[ $this->plugin_name ];
        }

        return false;
    }

    /**
     * Return VC fake license to prevent notices
     *
     * @param mixed $license - license.
     * @return mixed
     */
    public function js_composer_license( $license ) {
        return $license ? $license : 'fake';
    }

    /**
     * Change VC data to fake to prevent VC upgrader start
     *
     * @param mixed       $return - return data.
     * @param object      $package - package data.
     * @param WP_Upgrader $updater - upgrader data.
     * @return mixed
     */
    public function js_composer_pre_download_1( $return, $package, $updater ) {
        if ( isset( $updater->skin->plugin ) && 'js_composer/js_composer.php' === $updater->skin->plugin ) {
            $is_fake = 'fake' === get_option( 'wpb_js_js_composer_purchase_code' );

            if ( $is_fake ) {
                $updater->skin->plugin .= ' fake';
            }
        }

        if ( isset( $updater->skin->plugin_info ) && 'WPBakery Page Builder' === $updater->skin->plugin_info['Name'] ) {
            $is_fake = 'fake' === get_option( 'wpb_js_js_composer_purchase_code' );

            if ( $is_fake ) {
                $updater->skin->plugin_info['Name'] .= ' fake';
            }
        }

        return $return;
    }

    /**
     * Change VC data back to normal after VC upgrader code end
     *
     * @param mixed       $return - return data.
     * @param object      $package - package data.
     * @param WP_Upgrader $updater - upgrader data.
     * @return mixed
     */
    public function js_composer_pre_download_2( $return, $package, $updater ) {
        if ( isset( $updater->skin->plugin ) && 'js_composer/js_composer.php fake' === $updater->skin->plugin ) {
            $updater->skin->plugin = 'js_composer/js_composer.php';
        }
        if ( isset( $updater->skin->plugin_info ) && 'WPBakery Page Builder fake' === $updater->skin->plugin_info['Name'] ) {
            $updater->skin->plugin_info['Name'] = 'WPBakery Page Builder';
        }
        return $return;
    }

    /**
     * Modify plugin update information
     *
     * @param object $transient - plugin data.
     * @return object
     */
    public function modify_plugin_transient( $transient ) {
        // bail early if no response (error).
        if ( ! isset( $transient->response ) ) {
            return $transient;
        }

        // get tmp plugin data.
        $plugin = $this->get_tgm_plugin_data();

        if ( ! $plugin || empty( $plugin ) ) {
            return $transient;
        }

        // only for external source type.
        if ( 'external' !== $plugin['source_type'] ) {
            return $transient;
        }

        // check if available transient for this plugin.
        if ( ! isset( $transient->response[ $plugin['file_path'] ] ) ) {
            return $transient;
        }

        $transient->response[ $plugin['file_path'] ]->package = $plugin['source'];

        return $transient;
    }
}

new NKTH_VC_Plugin_Updater();
