<?php
/**
 * Check for ACF Pro plugin updates
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class NKTH_ACF_Pro_Plugin_Updater
 */
class NKTH_ACF_Pro_Plugin_Updater {
    /**
     * Plugin name
     *
     * @var array
     */
    private $plugin_name = 'advanced-custom-fields-pro';

    /**
     * NKTH_ACF_Pro_Plugin_Updater constructor.
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

        // Don't run on ACF Settings page to prevent conflicts.
        // phpcs:ignore
        if ( isset( $_GET['post_type'] ) && 'acf-field-group' === $_GET['post_type'] ) {
            return;
        }

        // Already active.
        if ( get_option( 'acf_pro_license' ) ) {
            return;
        }

        // Return ACF pro fake license to prevent notices.
        add_filter( 'option_acf_pro_license', array( $this, 'acf_pro_license' ), 20, 1 );
        add_filter( 'pre_option_acf_pro_license', array( $this, 'acf_pro_license' ), 20, 1 );

        // Modify update information for plugin.
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
     *  Return ACF pro fake license to prevent notices
     *
     *  @param mixed $license - license.
     *  @return mixed
     */
    public function acf_pro_license( $license ) {
        if ( ! $license ) {
            // phpcs:ignore
            return base64_encode(
                maybe_serialize(
                    array(
                        'key' => 'fake',
                        'url' => home_url(),
                    )
                )
            );
        }

        return $license;
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

new NKTH_ACF_Pro_Plugin_Updater();
