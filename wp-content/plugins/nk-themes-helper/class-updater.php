<?php
/**
 * Check for plugin updates
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class NKTH_Updater
 */
class NKTH_Updater {
    /**
     * The single class instance.
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Main Instance
     * Ensures only one instance of this class exists in memory at any one time.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->init_actions();
        }
        return self::$instance;
    }

    /**
     * NKTH_Updater constructor.
     */
    private function __construct() {
        /* We do nothing here! */
    }

    /**
     * Init actions
     */
    private function init_actions() {
        // update check.
        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'filter_plugin_set_transient' ) );
    }

    /**
     * Get latest plugin version
     *
     * @return string
     */
    public function get_latest_plugin_version() {
        if ( isset( $this->latest_plugin_version ) ) {
            return $this->latest_plugin_version;
        } else {

            // Check cache.
            $last_version = nkth()->get_cache( 'nkth_version_latest' );

            // Return cached version.
            if ( $last_version ) {
                $this->latest_plugin_version = $last_version;
                return $this->latest_plugin_version;
            }

            // Request for remote version check.
            $response = wp_remote_get( 'https://wp.nkdev.info/_api/?item_id=' . nkth()->plugin_name_sanitized . '&type=version' );
            if ( is_array( $response ) && 200 === $response['response']['code'] && $response['body'] ) {
                $response = json_decode( wp_remote_retrieve_body( $response ) );

                if ( isset( $response->success ) ) {
                    $this->latest_plugin_version = $response->response;

                    // Save cache.
                    nkth()->set_cache( 'nkth_version_latest', $this->latest_plugin_version );

                    return $this->latest_plugin_version;
                }
            }
        }
        return false;
    }

    /**
     * Is update available
     *
     * @return bool
     */
    public function is_update_available() {
        $new = $this->get_latest_plugin_version();
        if ( $new ) {
            return version_compare( nkth()->plugin_version, $new, '<' );
        }
        return false;
    }

    /**
     * Get plugin download url
     *
     * @return string
     */
    public function get_plugin_download_url() {
        if ( isset( $this->plugin_download_uri ) ) {
            return $this->plugin_download_uri;
        } else {
            $response = wp_remote_get( 'https://wp.nkdev.info/_api/?item_id=' . nkth()->plugin_name_sanitized . '&type=get-wp-uri' );
            if ( is_array( $response ) && 200 === $response['response']['code'] && $response['body'] ) {
                $response = json_decode( wp_remote_retrieve_body( $response ) );

                if ( isset( $response->success ) ) {
                    $this->plugin_download_uri = $response->response;
                    return $this->plugin_download_uri;
                }
            }
        }
        return false;
    }

    /**
     * Check info to the filter transient
     *
     * @param object $transient - transient data.
     *
     * @return mixed
     */
    public function filter_plugin_set_transient( $transient ) {
        // Check for new version.
        if ( $this->is_update_available() ) {
            $obj                               = new stdClass();
            $obj->slug                         = nkth()->plugin_slug;
            $obj->new_version                  = $this->get_latest_plugin_version();
            $obj->url                          = '';
            $obj->package                      = $this->get_plugin_download_url();
            $transient->response[ $obj->slug ] = $obj;
        }
        return $transient;
    }
}
