<?php
/**
 * Check for theme updates
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class NKTH_Theme_Dashboard_Updater
 */
class NKTH_Theme_Dashboard_Updater {
    /**
     * The single class instance.
     *
     * @since 1.0.0
     * @access private
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
     * NKTH_Theme_Dashboard_Updater constructor.
     */
    private function __construct() {
        /* We do nothing here! */
    }

    /**
     * Init actions
     */
    private function init_actions() {
        if ( nkth()->theme_dashboard()->theme_id ) {
            // add notice about activation.
            add_action( 'admin_notices', array( $this, 'add_activation_notice' ) );

            // define the alternative API for updating checking.
            add_filter( 'pre_set_site_transient_update_themes', array( $this, 'filter_check_update' ) );
            add_filter( 'pre_set_transient_update_themes', array( $this, 'filter_check_update' ) );

            // request for theme package url when downloading start.
            add_filter( 'upgrader_pre_download', array( $this, 'filter_theme_upgrade' ), 10, 4 );
        }
    }

    /**
     * Get theme version
     *
     * @return string
     */
    public function get_latest_theme_version() {
        if ( isset( $this->latest_theme_version ) ) {
            return $this->latest_theme_version;
        } elseif ( nkth()->theme_dashboard()->theme_id ) {
            // Check cache.
            $last_check   = nkth()->theme_dashboard()->get_option( 'updater-latest-version-time' );
            $last_version = nkth()->theme_dashboard()->get_option( 'updater-latest-version' );

            // Return cached version.
            if ( $last_check && $last_version && 6 * 60 * 60 > ( time() - $last_check ) ) {
                $this->latest_theme_version = $last_version;
                return $this->latest_theme_version;
            }

            // Request for remote version check.
            $response = wp_remote_get( 'https://nkdev.info/wp-json/vatomi/v1/envato/item_version/' . nkth()->theme_dashboard()->theme_id );
            if ( wp_remote_retrieve_response_code( $response ) === 200 && wp_remote_retrieve_body( $response ) ) {
                $response = json_decode( wp_remote_retrieve_body( $response ) );

                if ( isset( $response->success ) ) {
                    $this->latest_theme_version = $response->response;

                    // Save cache.
                    nkth()->theme_dashboard()->update_option( 'updater-latest-version-time', time() );
                    nkth()->theme_dashboard()->update_option( 'updater-latest-version', $this->latest_theme_version );

                    return $this->latest_theme_version;
                }
            }

            return false;
        }
        return false;
    }

    /**
     * Check if update available
     *
     * @return bool
     */
    public function is_update_available() {
        $new = $this->get_latest_theme_version();
        if ( $new ) {
            return version_compare( nkth()->theme_dashboard()->theme_version, $new, '<' );
        }
        return false;
    }

    /**
     * Get theme download url
     *
     * @return string
     */
    public function get_theme_download_url() {
        $edd_license = nkth()->theme_dashboard()->get_option( 'edd_license' );

        if ( isset( $this->theme_download_uri ) ) {
            return $this->theme_download_uri;
        } elseif ( $edd_license ) {
            $edd_name = nkth()->theme_dashboard()->options['edd_name'];
            // phpcs:ignore
            $response = wp_remote_get( 'https://nkdev.info/?edd_action=get_version&item_name=' . urlencode( $edd_name ) . '&license=' . esc_html( $edd_license ) . '&url=' . esc_url( home_url( '/' ) ) );

            if ( wp_remote_retrieve_response_code( $response ) === 200 && wp_remote_retrieve_body( $response ) ) {
                $response = json_decode( wp_remote_retrieve_body( $response ) );

                if ( isset( $response->download_link ) ) {
                    $this->theme_download_uri = $response->download_link;
                    return $this->theme_download_uri;
                }
            }
            return false;
        } elseif ( nkth()->theme_dashboard()->theme_id ) {
            $token         = nkth()->theme_dashboard()->get_option( 'activation_token' );
            $refresh_token = nkth()->theme_dashboard()->get_option( 'refresh_token' );
            $license       = nkth()->theme_dashboard()->get_option( 'activation_purchase_code' );

            // old activation way.
            if ( $token && $refresh_token ) {
                $response = wp_remote_get( 'https://nkdev.info/wp-json/vatomi/v1/envato/item_wp_url/' . nkth()->theme_dashboard()->theme_id . '?license=' . esc_attr( $license ) . '&access_token=' . esc_attr( $token ) . '&refresh_token=' . esc_attr( $refresh_token ) );

                // new activation way.
            } else {
                // phpcs:ignore
                $response = wp_remote_get( 'https://nkdev.info/wp-json/vatomi/v1/envato/item_wp_url/' . nkth()->theme_dashboard()->theme_id . '?license=' . esc_attr( $license ) . '&site=' . urlencode( home_url( '/' ) ) );
            }

            if ( wp_remote_retrieve_response_code( $response ) === 200 && wp_remote_retrieve_body( $response ) ) {
                $response = json_decode( wp_remote_retrieve_body( $response ) );

                if ( isset( $response->success ) ) {
                    $this->theme_download_uri = $response->response;
                    return $this->theme_download_uri;
                }
            }
            return false;
        }
        return false;
    }


    /**
     * Check if current enviroment is dev
     *
     * Environment is considered dev if host is:
     * - ip address
     * - tld is local, dev, wp, test, example, localhost or invalid
     * - no tld (localhost, custom hosts)
     *
     * @param string $host Hostname to check. If null, use HTTP_HOST.
     *
     * @return boolean
     */
    public function is_dev_environment( $host = null ) {
        if ( ! $host ) {
            $host = site_url();
        }

        $chunks = explode( '.', $host );

        if ( 1 === count( $chunks ) ) {
            return true;
        }

        if ( in_array(
            end( $chunks ),
            array(
                'local',
                'dev',
                'wp',
                'test',
                'example',
                'localhost',
                'invalid',
            ),
            true
        ) ) {
            return true;
        }

        if ( preg_match( '/^[0-9\.]+$/', $host ) ) {
            return true;
        }

        return false;
    }

    /**
     * Add Theme Activation Message
     */
    public function add_activation_notice() {
        if (
            ! $this->is_dev_environment() &&
            ! nkth()->theme_dashboard()->activation()->active &&
            'disabled' !== nkth()->get_option( 'ask_for_activation_status' )
        ) {
            ?>
            <div class="nk-theme-activation-notice notice notice-info is-dismissible update-nag below-h2" style="display: block;">
            <?php
                $url = admin_url( 'admin.php?page=nk-theme' );

                // translators: %1$s - theme dashboard url.
                // translators: %2$s - theme name.
                echo sprintf( ' ' . esc_html__( 'To receive automatic updates license activation is required. Please visit %1$s to activate %2$s theme.', 'nk-themes-helper' ), sprintf( '<a href="%s">%s</a>', esc_url( $url ), esc_html__( 'theme dashboard', 'nk-themes-helper' ) ), esc_html( nkth()->theme_dashboard()->theme_name ) );
            ?>
            </div>
            <?php
        }
    }

    /**
     * Check info to the filter transient
     *
     * @param object $checked_data - update item data.
     *
     * @return mixed
     */
    public function filter_check_update( $checked_data ) {
        // Check for new version.
        if ( $this->is_update_available() ) {
            $response                                    = array(
                'slug'        => nkth()->theme_dashboard()->theme_slug,
                'name'        => nkth()->theme_dashboard()->theme_name,
                'url'         => nkth()->theme_dashboard()->theme_uri,
                'new_version' => $this->get_latest_theme_version(),
                'package'     => array(
                    'slug'       => nkth()->theme_dashboard()->theme_slug,
                    'name'       => nkth()->theme_dashboard()->theme_name,
                ),
            );
            $checked_data->response[ $response['slug'] ] = $response;
        }

        return $checked_data;
    }

    /**
     * Filter for theme update action
     *
     * @param mixed  $false - default value.
     * @param array  $package - update package data.
     * @param object $updater - updated api.
     *
     * @return string|WP_Error
     */
    public function filter_theme_upgrade( $false, $package, $updater ) {
        $condition1 = is_array( $package ) && isset( $package['slug'] ) && nkth()->theme_dashboard()->theme_slug === $package['slug'];
        $condition2 = is_array( $package ) && isset( $package['name'] ) && nkth()->theme_dashboard()->theme_name === $package['name'];
        if ( ! $condition1 && ! $condition2 ) {
            return $false;
        }

        if ( ! nkth()->theme_dashboard()->activation()->active ) {
            $url      = esc_url( admin_url( 'admin.php?page=nk-theme' ) );
            $redirect = sprintf( '<a href="%s">%s</a>', $url, esc_html__( 'theme dashboard', 'nk-themes-helper' ) );

            // translators: %1$s - theme dashboard url.
            // translators: %2$s - theme name.
            return new WP_Error( 'no_credentials', sprintf( esc_html__( 'To receive automatic updates license activation is required. Please visit %1$s to activate your theme %2$s.', 'nk-themes-helper' ), $redirect, nkth()->theme_dashboard()->theme_name ) );
        }

        $res = $updater->fs_connect( array( WP_CONTENT_DIR ) );
        if ( ! $res ) {
            return new WP_Error( 'no_credentials', esc_html__( 'Error! Can\'t connect to filesystem', 'nk-themes-helper' ) );
        }

        // check if old activation used and show notice for user.
        if ( nkth()->theme_dashboard()->get_option( 'activation_token' ) && nkth()->theme_dashboard()->get_option( 'refresh_token' ) ) {
            echo '<div class="notice-warning settings-error notice is-dismissible">';
            echo '<p>';
            echo esc_html__( 'Please, reactivate theme (updater method was updated and old code is deprecated now)', 'nk-themes-helper' );
            echo '</p>';
            echo '<p>';
            echo '<a class="button button-primary"
                href="' . esc_url( admin_url( 'admin.php?page=nk-theme' ) ) . '" target="_blank">' . esc_html__( 'Reactivate in Dashboard', 'nk-themes-helper' ) . '</a>';
            echo '</p>';
            echo '</div>';
        }

        $updater->strings['downloading_package_url'] = esc_html__( 'Getting download link...', 'nk-themes-helper' );
        $updater->skin->feedback( 'downloading_package_url' );

        $download_url = $this->get_theme_download_url();

        if ( ! $download_url ) {
            return new WP_Error( 'no_credentials', esc_html__( 'Download link could not be retrieved', 'nk-themes-helper' ) );
        }

        $updater->strings['downloading_package'] = esc_html__( 'Downloading package...', 'nk-themes-helper' );
        $updater->skin->feedback( 'downloading_package' );

        $downloaded_archive = download_url( $download_url );
        if ( is_wp_error( $downloaded_archive ) ) {
            return $downloaded_archive;
        }

        // WP will use same name for plugin directory as archive name, so we have to rename it.
        if ( basename( $downloaded_archive, '.zip' ) !== $package['slug'] ) {
            $new_archive_name = dirname( $downloaded_archive ) . '/' . $package['slug'] . '.zip';

            // @codingStandardsIgnoreLine
            rename( $downloaded_archive, $new_archive_name );
            $downloaded_archive = $new_archive_name;
        }

        return $downloaded_archive;
    }
}

