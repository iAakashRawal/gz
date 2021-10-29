<?php
/**
 * Demo Import Helper
 *
 * Example:
 *   // import all demo data
 *   echo '<br><h4>Demo Data:</h4>';
 *   nkth()->demo_importer()->import_demo_data($import_data_file);
 *
 *   // setup widgets
 *   echo '<br><h4>Widgets:</h4>';
 *   nkth()->demo_importer()->import_demo_widgets($import_widgets_file);
 *
 *   // options tree importer
 *   echo '<br><h4>Theme Options:</h4>';
 *   nkth()->demo_importer()->import_demo_options_tree($import_options_file);
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class NKTH_Demo_Importer
 */
class NKTH_Demo_Importer {
    /**
     * The single class instance.
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Time in milliseconds, marking the beginning of the import.
     *
     * @var float
     */
    private $microtime;

    /**
     * Main Instance
     * Ensures only one instance of this class exists in memory at any one time.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * NKTH_Demo_Importer constructor.
     */
    private function __construct() {
        /* We do nothing here! */
    }

    /**
     * Prepare demo importer
     */
    private function prepare_demo_importer() {
        // set time limit to prevent demo import failings.
        set_time_limit( 300 );

        if ( ! class_exists( '\WP_Importer' ) ) {
            // phpcs:ignore
            defined( 'WP_LOAD_IMPORTERS' ) || define( 'WP_LOAD_IMPORTERS', true );
            require ABSPATH . '/wp-admin/includes/class-wp-importer.php';
        }

        if ( ! class_exists( '\NKTH\WPContentImporter2\WXRImporter' ) ) {
            $wxr_importer_path           = nkth()->plugin_path . 'vendor/wxr-importer/class-wxr-importer.php';
            $wxr_importer_info           = nkth()->plugin_path . 'vendor/wxr-importer/class-wxr-import-info.php';
            $wxr_logger_path             = nkth()->plugin_path . 'vendor/wxr-importer/class-logger.php';
            $wxr_logger_html_path        = nkth()->plugin_path . 'vendor/wxr-importer/class-logger-html.php';
            $wxr_logger_serversentevents = nkth()->plugin_path . 'vendor/wxr-importer/class-logger-serversentevents.php';

            if ( file_exists( $wxr_importer_path ) ) {
                require_once $wxr_importer_path;
            }
            if ( file_exists( $wxr_importer_info ) ) {
                require_once $wxr_importer_info;
            }
            if ( file_exists( $wxr_logger_path ) ) {
                require_once $wxr_logger_path;
            }
            if ( file_exists( $wxr_logger_html_path ) ) {
                require_once $wxr_logger_html_path;
            }
            if ( file_exists( $wxr_logger_serversentevents ) ) {
                require_once $wxr_logger_serversentevents;
            }

            $nkth_wxr_importer_path = nkth()->plugin_path . 'vendor/nk-wxr-importer.php';
            if ( file_exists( $nkth_wxr_importer_path ) ) {
                require_once $nkth_wxr_importer_path;
            }
        }

        if ( ! class_exists( 'Customizer_Import' ) ) {
            $customizer_importer_path      = nkth()->plugin_path . 'vendor/customizer-importer/customizer-importer.php';
            $nkth_customizer_importer_path = nkth()->plugin_path . 'vendor/nk-customizer-importer.php';
            if ( file_exists( $customizer_importer_path ) ) {
                require_once $customizer_importer_path;
            }
            if ( file_exists( $nkth_customizer_importer_path ) ) {
                require_once $nkth_customizer_importer_path;
            }
        }

        if ( ! function_exists( 'wie_import_data' ) ) {
            $widgets_importer_path = nkth()->plugin_path . 'vendor/widgets-importer/widgets_import.php';
            if ( file_exists( $widgets_importer_path ) ) {
                require_once $widgets_importer_path;
            }
        }
    }


    /***
     * NEW IMPORTER WITH STREAMING PROCESS
     * idea from https://github.com/humanmade/WordPress-Importer/blob/master/class-wxr-import-ui.php
     */

    /**
     * Max delta
     *
     * @var int
     */
    public $max_delta = 0;

    /**
     * Current delta
     *
     * @var int
     */
    public $delta = 0;

    /**
     * Resume delta
     *
     * @var int
     */
    public $resume_delta = 0;

    /**
     * Logger
     *
     * @var object
     */
    public $logger;

    /**
     * WP Import
     *
     * @var object
     */
    public $wp_import;

    /**
     * Imported images
     *
     * @var array
     */
    public $imported_images = array();

    /**
     * Stream Import
     *
     * @param array $settings settings.
     */
    public function stream_import( $settings = array() ) {
        // get settings.
        $settings = array_merge(
            array(
                // phpcs:ignore
                /*
                    Set blog options
                    Example:
                    array(
                        'permalink'            => '/%postname%/',
                        'page_on_front_title'  => 'GodLike',
                        'page_for_posts_title' => 'News',
                        'posts_per_page'       => 6
                    )
                 */
                'blog_options'        => false,

                // phpcs:ignore
                /*
                    Set WooCommerce pages
                    Example:
                    array(
                        'shop_page_title'      => 'Shop',
                        'cart_page_title'      => 'Cart',
                        'checkout_page_title'  => 'Checkout',
                        'myaccount_page_title' => 'My Account',
                    )
                 */
                'woocommerce_options' => false,

                // phpcs:ignore
                /*
                    Set Ghost Kit settings
                    Example:
                    array(
                        'typography' => array(
                            'body' => array(
                                'font-family-category' => 'google-fonts',
                                'font-family'          => 'Open Sans',
                                'font-size'            => '',
                                'font-weight'          => '400',
                                'line-height'          => '1.7',
                                'letter-spacing'       => '',
                            ),
                        ),
                    )
                 */
                'ghostkit_options'    => false,

                // phpcs:ignore
                /*
                    Set navigations
                    Example:
                    array(
                        'Top Menu'            => 'top_menu',
                        'Main Menu'           => 'primary',
                        'Some Menu Locations' => array('primary', 'top_menu'),
                    )
                 */
                'navigations'         => false,

                // exported files to import.
                'demo_data_file'      => false,
                'widgets_file'        => false,
                'customizer_file'     => false,
                'rev_slider_file'     => false,
            ),
            $settings
        );

        // Turn off PHP output compression.
        // phpcs:disable
        $previous = error_reporting( error_reporting() ^ E_WARNING );
        ini_set( 'output_buffering', 'off' );
        ini_set( 'zlib.output_compression', false );
        error_reporting( $previous );
        // phpcs:enable

        if ( $GLOBALS['is_nginx'] ) {
            // Setting this header instructs Nginx to disable fastcgi_buffering
            // and disable gzip for this request.
            header( 'X-Accel-Buffering: no' );
            header( 'Content-Encoding: none' );
        }

        // Start the event stream.
        header( 'Content-Type: text/event-stream' );
        header( 'Cache-Control: no-cache' );

        // 2KB padding for IE
        echo ':' . esc_html( str_repeat( ' ', 2048 ) ) . "\n\n";

        // Time to run the import!
        set_time_limit( 300 );

        // Ensure we're not buffered.
        if ( ob_get_length() ) {
            ob_end_flush();
        }
        flush();

        // prepare all importer libraries.
        $this->prepare_demo_importer();

        // main importer.
        $this->wp_import = new \NKTH\WPContentImporter2\NKTH_WXRImporter(
            array(
                'aggressive_url_search' => true,
                'fetch_attachments'     => true,
            )
        );

        // init logger.
        $this->logger = new \NKTH\WPContentImporter2\WPImporterLogger_ServerSentEvents();

        // get imported menu items cache.
        $imported_menu_items = get_transient( 'nkth_imported_menu_items' );

        // get max_delta information.
        if ( $settings['demo_data_file'] ) {
            $info             = $this->wp_import->get_preliminary_information( $settings['demo_data_file'] );
            $this->max_delta += isset( $info->comment_count ) ? $info->comment_count : 0;
            $this->max_delta += isset( $info->media_count ) ? $info->media_count : 0;
            $this->max_delta += isset( $info->post_count ) ? $info->post_count : 0;
            $this->max_delta += isset( $info->term_count ) ? $info->term_count : 0;
        }

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Max Delta After Info: ', 'nk-themes-helper' ) . $this->max_delta );
        }

        foreach ( $settings as $k => $setting ) {
            if ( is_array( $setting ) ) {
                $this->max_delta += count( $setting );
            } elseif ( 'demo_data_file' !== $k ) {
                $this->max_delta += 1;
            }
        }

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Max Delta After Settings: ', 'nk-themes-helper' ) . $this->max_delta );
        }

        // Import XML file.
        if ( $settings['demo_data_file'] ) {
            $this->stream_import_demo_data( $settings['demo_data_file'] );
        }

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Resume Delta Pre Widgets: ', 'nk-themes-helper' ) . $this->resume_delta );
            $this->logger->info( __( 'Delta Pre Widgets: ', 'nk-themes-helper' ) . $this->delta );
        }

        // import widgets.
        if ( $settings['widgets_file'] && $this->resume_delta <= $this->delta ) {
            $this->stream_import_widgets( $settings['widgets_file'] );
        }
        $this->update_delta( 'import_widgets' );
        $this->new_request_maybe( array() );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Resume Delta Pre Customizer: ', 'nk-themes-helper' ) . $this->resume_delta );
            $this->logger->info( __( 'Delta Pre Customizer: ', 'nk-themes-helper' ) . $this->delta );
        }

        // import customizer.
        if ( $settings['customizer_file'] && $this->resume_delta <= $this->delta ) {
            $this->stream_import_customizer( $settings['customizer_file'] );
        }
        $this->update_delta( 'import_customizer' );
        $this->new_request_maybe( array() );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Resume Delta Pre Navigations: ', 'nk-themes-helper' ) . $this->resume_delta );
            $this->logger->info( __( 'Delta Pre Navigations: ', 'nk-themes-helper' ) . $this->delta );
        }

        // setup navigations.
        if ( $settings['navigations'] && is_array( $settings['navigations'] ) && $this->resume_delta <= $this->delta ) {
            $this->stream_setup_navigations( $settings['navigations'] );
        }
        $this->update_delta( 'setup_navigations' );
        $this->new_request_maybe( array() );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Resume Delta Pre Blog Options: ', 'nk-themes-helper' ) . $this->resume_delta );
            $this->logger->info( __( 'Delta Pre Blog Options: ', 'nk-themes-helper' ) . $this->delta );
        }

        // update blog options.
        if ( $settings['blog_options'] && is_array( $settings['blog_options'] ) && $this->resume_delta <= $this->delta ) {
            $this->stream_blog_options( $settings['blog_options'] );
        }
        $this->update_delta( 'blog_options' );
        $this->new_request_maybe( array() );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Resume Delta Pre WooCommerce Options: ', 'nk-themes-helper' ) . $this->resume_delta );
            $this->logger->info( __( 'Delta Pre WooCommerce Options: ', 'nk-themes-helper' ) . $this->delta );
        }

        // update WooCommerce options.
        if ( $settings['woocommerce_options'] && is_array( $settings['woocommerce_options'] ) && class_exists( 'WooCommerce' ) && $this->resume_delta <= $this->delta ) {
            $this->stream_woocommerce_options( $settings['woocommerce_options'] );
        }
        $this->update_delta( 'woocommerce_options' );
        $this->new_request_maybe( array() );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Resume Delta Pre GhostKit Options: ', 'nk-themes-helper' ) . $this->resume_delta );
            $this->logger->info( __( 'Delta Pre GhostKit Options: ', 'nk-themes-helper' ) . $this->delta );
        }

        // update Ghost Kit options.
        if ( $settings['ghostkit_options'] && is_array( $settings['ghostkit_options'] ) && $this->resume_delta <= $this->delta ) {
            $this->stream_ghostkit_options( $settings['ghostkit_options'] );
        }
        $this->update_delta( 'ghostkit_options' );
        $this->new_request_maybe( array() );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Resume Delta Pre RevSlider: ', 'nk-themes-helper' ) . $this->resume_delta );
            $this->logger->info( __( 'Delta Pre RevSlider: ', 'nk-themes-helper' ) . $this->delta );
        }

        // import RevSlider.
        if ( $settings['rev_slider_file'] && class_exists( 'RevSlider' ) && $this->resume_delta <= $this->delta ) {
            $this->stream_import_rev_slider( $settings['rev_slider_file'] );
        }
        $this->update_delta( 'import_rev_slider' );
        $this->new_request_maybe( array() );

        // remapping menu items.
        if ( $imported_menu_items ) {
            $this->stream_import_menu_items( $imported_menu_items );
        }

        // Done.
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Max Delta: ', 'nk-themes-helper' ) . $this->max_delta );
            $this->logger->info( __( 'Resume Delta: ', 'nk-themes-helper' ) . $this->resume_delta );
            $this->logger->info( __( 'Delta: ', 'nk-themes-helper' ) . $this->delta );
        }

        $this->logger->info( __( 'Demo data successfully imported!', 'nk-themes-helper' ) );
        delete_transient( 'nkth_importer_delta' );
        delete_transient( 'nkth_imported_menu_items' );
        $this->emit_sse_message(
            array(
                'action' => 'complete',
                'error'  => false,
            )
        );
    }

    /**
     * Import demo data using stream
     *
     * @param string $file - file name.
     */
    public function stream_import_demo_data( $file ) {
        $this->microtime = microtime( true );

        add_action( 'wxr_importer.processed.post', array( $this, 'add_imported_menu_items_to_cache' ), 10, 3 );
        add_action( 'wxr_importer.processed.post', array( $this, 'imported_post' ), 10, 2 );
        add_action( 'wxr_importer.process_failed.post', array( $this, 'imported_post' ), 10, 2 );
        add_action( 'wxr_importer.process_already_imported.post', array( $this, 'imported_post' ), 10, 2 );
        add_action( 'wxr_importer.process_skipped.post', array( $this, 'imported_post' ), 10, 2 );
        add_action( 'wxr_importer.processed.comment', array( $this, 'imported_comment' ) );
        add_action( 'wxr_importer.process_already_imported.comment', array( $this, 'imported_comment' ) );
        add_action( 'wxr_importer.processed.term', array( $this, 'imported_term' ) );
        add_action( 'wxr_importer.process_failed.term', array( $this, 'imported_term' ) );
        add_action( 'wxr_importer.process_already_imported.term', array( $this, 'imported_term' ) );

        // Disable users import.
        add_filter( 'wxr_importer.pre_process.user', '__return_false' );

        // Check, if we need to send another AJAX request.
        add_filter( 'wxr_importer.pre_process.post', array( $this, 'new_request_maybe' ) );
        add_filter( 'wxr_importer.pre_process.post', array( $this, 'update_comment_delta' ), 9, 4 );
        add_filter( 'wxr_importer.pre_process.term', array( $this, 'new_request_maybe' ) );

        // Set the importing author to the current user.
        add_filter( 'wxr_importer.pre_process.post', array( $this, 'set_post_author' ) );

        if ( ! $this->wp_import ) {
            $this->wp_import = new \NKTH\WPContentImporter2\NKTH_WXRImporter(
                array(
                    'fetch_attachments' => true,
                )
            );
        }

        // get delta from the previous demo run.
        $importer_delta = get_transient( 'nkth_importer_delta' );

        if ( false === $importer_delta ) {
            delete_transient( 'nkth_imported_menu_items' );
        }

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Update Resume Delta: ', 'nk-themes-helper' ) . $importer_delta );
        }

        $this->resume_delta = $importer_delta ? $importer_delta : 0;

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'New Resume Delta: ', 'nk-themes-helper' ) . $this->resume_delta );
        }

        $this->wp_import->set_logger( $this->logger );

        $err = $this->wp_import->import( $file );

        if ( is_wp_error( $err ) ) {
            $this->emit_sse_message(
                array(
                    'action' => 'error',
                    'error'  => $err->get_error_message(),
                )
            );
            exit;
        }
    }

    /**
     * Update Delta for imported comments.
     *
     * @param object $data - Post Object.
     * @param array  $meta - Post Metas.
     * @param array  $comments - Post Comments.
     * @param array  $terms - post Terms.
     * @return object
     */
    public function update_comment_delta( $data, $meta, $comments, $terms ) {
        if ( is_array( $comments ) && ! empty( $comments ) ) {
            foreach ( $comments as $comment ) {
                $this->delta += 1;
            }
        }
        return $data;
    }

    /**
     * Check if we need to create a new AJAX request, so that server does not timeout.
     *
     * @param array $data current post data.
     * @return array
     */
    public function new_request_maybe( $data ) {
        $time = microtime( true ) - $this->microtime;

        // update delta.
        $this->delta += 1;

        // We should make a new ajax call, if the time is right.
        // 25 seconds per request.
        if ( $time > 25 ) {
            // Set the current importer stat, so it will be used on the next ajax call.
            if ( $this->wp_import ) {
                set_transient( 'nkth_importer_delta', $this->delta, 0.1 * HOUR_IN_SECONDS );
            }

            $this->logger->info( __( 'New Request...', 'nk-themes-helper' ) );
            $this->emit_sse_message(
                array(
                    'action' => 'new_ajax',
                    'type'   => 'new_ajax',
                )
            );

            wp_die(
                '',
                '',
                array(
                    'response' => null,
                )
            );
        }

        if ( $this->resume_delta >= $this->delta ) {
            return array();
        }

        return $data;
    }

    /**
    // Set importing author to the current user.
    // Fixes the [WARNING] Could not find the author for ... log warning messages.
     *
     * @param array $data current post data.
     * @return array
     */
    public function set_post_author( $data ) {
        if ( ! empty( $data ) ) {
            $current_user_obj    = wp_get_current_user();
            $data['post_author'] = $current_user_obj->user_login;
        }
        return $data;
    }

    /**
     * Import widgets
     *
     * @param string $file - file name.
     */
    public function stream_import_widgets( $file ) {
        $this->prepare_demo_importer();

        if ( ! file_exists( $file ) ) {
            $this->logger->info( __( 'Widgets import file could not be found.', 'nk-themes-helper' ) );
            return;
        }

        // phpcs:ignore
        $data = file_get_contents( $file );
        $data = json_decode( $data );
        wie_import_data( $data );
        $this->logger->info( __( 'Widgets imported', 'nk-themes-helper' ) );
    }

    /**
     * Import customizer
     *
     * @param string $file - file name.
     */
    public function stream_import_customizer( $file ) {
        $this->prepare_demo_importer();
        $importer                = new NKTH_Customizer_Import();
        $importer->import_images = true;
        $importer->_import( $file );
        $this->logger->info( __( 'Customizer options imported', 'nk-themes-helper' ) );
    }

    /**
     * Import rev slider
     *
     * @param string $file - file name.
     * @param object $slider - rev slider object.
     */
    public function stream_import_rev_slider( $file, $slider = false ) {
        if ( ! class_exists( 'RevSlider' ) ) {
            return;
        }
        if ( ! $slider ) {
            $slider = new RevSlider();
        }
        if ( is_array( $file ) ) {
            foreach ( $file as $a ) {
                $this->stream_import_rev_slider( $a, $slider );
            }
            return;
        }
        $this->prepare_demo_importer();
        if ( file_exists( $file ) ) {
            $file_hash = md5_file( $file );

            // check if slider already exists.
            $imported = nkth()->get_option( 'revslider_' . $file_hash, false );
            if ( $imported ) {
                $all_sliders = $slider->getArrSlidersShort();
                if ( isset( $all_sliders[ $imported ] ) ) {
                    return;
                }
            }

            // import new slider.
            $response = $slider->importSliderFromPost( true, true, $file );
            if ( $response && isset( $response['sliderID'] ) ) {
                nkth()->update_option( 'revslider_' . $file_hash, $response['sliderID'] );
            }
            // translators: %s.
            $this->logger->info( sprintf( __( 'RevSlider %s imported', 'nk-themes-helper' ), basename( $file ) ) );
        }
    }

    /**
     * Setup navigations
     *
     * @param array $navigations - navigations.
     */
    public function stream_setup_navigations( $navigations = array() ) {
        $locations = get_theme_mod( 'nav_menu_locations', array() );
        $menus     = wp_get_nav_menus();
        if ( $menus ) {
            foreach ( $menus as $menu ) {
                if ( isset( $navigations[ $menu->name ] ) ) {
                    if ( is_array( $navigations[ $menu->name ] ) ) {
                        foreach ( $navigations[ $menu->name ] as $menu_name ) {
                            $locations[ $menu_name ] = $menu->term_id;
                        }
                    } else {
                        $locations[ $navigations[ $menu->name ] ] = $menu->term_id;
                    }
                }
            }
        }
        set_theme_mod( 'nav_menu_locations', $locations );
        $this->logger->info( __( 'Navigations added to their locations', 'nk-themes-helper' ) );
    }

    /**
     * Setup blog options
     *
     * @param array $options - options.
     */
    public function stream_blog_options( $options = array() ) {
        foreach ( $options as $name => $value ) {
            switch ( $name ) {
                case 'permalink':
                    global $wp_rewrite;
                    $wp_rewrite->set_permalink_structure( $value );
                    break;

                // home page.
                case 'page_on_front_title':
                    // @codingStandardsIgnoreLine
                    $homepage = get_page_by_title( $value );
                    if ( isset( $homepage ) && $homepage->ID ) {
                        update_option( 'show_on_front', 'page' );
                        update_option( 'page_on_front', $homepage->ID );
                    }
                    break;

                // blog page.
                case 'page_for_posts_title':
                    // @codingStandardsIgnoreLine
                    $blog = get_page_by_title( $value );
                    if ( isset( $blog ) && $blog->ID ) {
                        update_option( 'page_for_posts', $blog->ID );
                    }
                    break;

                // default options.
                default:
                    update_option( $name, $value );
                    break;
            }
        }
        $this->logger->info( __( 'Blog settings imported', 'nk-themes-helper' ) );
    }

    /**
     * Setup WooCommerce options
     *
     * @param array $options - options.
     */
    public function stream_woocommerce_options( $options = array() ) {
        foreach ( $options as $name => $value ) {
            switch ( $name ) {
                // default pages by title.
                case 'shop_page_title':
                case 'cart_page_title':
                case 'checkout_page_title':
                case 'myaccount_page_title':
                // @codingStandardsIgnoreLine
                    $page = get_page_by_title( $value );
                    if ( isset( $page ) && $page->ID ) {
                        update_option( 'woocommerce_' . str_replace( '_title', '', $name ) . '_id', $page->ID );
                    }
                    break;

                // default options.
                default:
                    update_option( $name, $value );
                    break;
            }
        }
        $this->logger->info( __( 'WooCommerce settings imported', 'nk-themes-helper' ) );
    }

    /**
     * Setup Ghost Kit options
     *
     * @param array $options - options.
     */
    public function stream_ghostkit_options( $options = array() ) {
        // Typography.
        if ( isset( $options['typography'] ) && ! empty( $options['typography'] ) ) {
            $new_typography = array();

            foreach ( $options['typography'] as $cat => $typography ) {
                $new_typography[ $cat ] = array();

                foreach ( $typography as $name => $val ) {
                    $name_camel    = str_replace( ' ', '', ucwords( str_replace( '-', ' ', $name ) ) );
                    $name_camel[0] = strtolower( $name_camel[0] );

                    $new_typography[ $cat ][ $name_camel ] = $val;
                }

                if ( empty( $new_typography[ $cat ] ) ) {
                    unset( $new_typography[ $cat ] );
                }
            }

            $current_typography = get_option( 'ghostkit_typography', array() );
            $updated_option     = array();

            if ( empty( $current_typography ) ) {
                $updated_option = $new_typography;
            } else {
                $updated_option = array_merge(
                    json_decode( $current_typography['ghostkit_typography'], true ),
                    $new_typography
                );
            }

            if ( ! empty( $updated_option ) ) {
                update_option(
                    'ghostkit_typography',
                    array(
                        'ghostkit_typography' => wp_json_encode( $updated_option ),
                    )
                );
            }
        }

        $this->logger->info( __( 'Ghost Kit settings imported', 'nk-themes-helper' ) );
    }

    /**
     * Emit a Server-Sent Events message.
     *
     * @param mixed $data Data to be JSON-encoded and sent in the message.
     */
    protected function emit_sse_message( $data ) {
        $data['max_delta'] = isset( $this->max_delta ) ? $this->max_delta : 0;

        echo "event: message\n";
        echo 'data: ' . wp_json_encode( $data ) . "\n\n";
        // Extra padding.
        echo ':' . esc_html( str_repeat( ' ', 2048 ) ) . "\n\n";

        if ( ob_get_length() ) {
            ob_end_flush();
        }
        flush();
    }

    /**
     * Send message when a post has been imported.
     *
     * @param string $type type.
     */
    public function update_delta( $type ) {
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'Before Update Delta: ', 'nk-themes-helper' ) . $this->delta );
        }

        $this->emit_sse_message(
            array(
                'action' => 'updateDelta',
                'type'   => $type,
                'delta'  => $this->delta,
            )
        );

        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            $this->logger->info( __( 'After Update Delta: ', 'nk-themes-helper' ) . $this->delta );
        }
    }

    /**
     * Send message when a post has been imported.
     *
     * @param int|WP_Error $id Post ID.
     * @param array        $data Post data saved to the DB.
     */
    public function imported_post( $id, $data ) {
        if ( ! is_wp_error( $id ) ) {
            /**
             * Skip error message
                $this->emit_sse_message(
                    array(
                        'action' => 'error',
                        'error' => $id->get_error_message() . ' [' . $id->get_error_code() . ']',
                    )
                );
                } else {
             */
            if ( 'attachment' === $data['post_type'] ) {
                $this->imported_images[] = 'ID: ' . $id . 'URL: ' . ( ! empty( $data['attachment_url'] ) ? $data['attachment_url'] : $data['guid'] );
            }
        }
        $this->update_delta( 'attachment' === $data['post_type'] ? 'media' : 'posts' );
    }

    /**
     * Save Menu Items Import to Cache.
     *
     * @param int|WP_Error $id Post ID.
     * @param array        $data Post data saved to the DB.
     * @param array        $meta Raw meta data, already processed by {@see process_post_meta}.
     */
    public function add_imported_menu_items_to_cache( $id, $data, $meta ) {
        if ( ! is_wp_error( $id ) && 'nav_menu_item' === $data['post_type'] ) {
            $imported_menu_items = get_transient( 'nkth_imported_menu_items' );
            if ( ! $imported_menu_items ) {
                $imported_menu_items = array();
            }
            $imported_menu_items[ $id ] = array(
                'data' => $data,
                'meta' => $meta,
            );
            set_transient( 'nkth_imported_menu_items', $imported_menu_items, 5 * MINUTE_IN_SECONDS );
        }
    }

    /**
     * Remapping Menu Items.
     *
     * @param array $imported_menu_items - List of Menu Items.
     */
    public function stream_import_menu_items( $imported_menu_items ) {
        if ( $imported_menu_items && is_array( $imported_menu_items ) ) {
            foreach ( $imported_menu_items as $key => $imported_menu_item ) {
                $data = $imported_menu_item['data'];
                $meta = $imported_menu_item['meta'];

                $args = array(
                    'menu-item-db-id'    => $data['post_id'],
                    'menu-item-position' => $data['menu_order'],
                    'menu-item-title'    => $data['post_title'],
                    'menu-item-status'   => $data['post_status'],
                );

                foreach ( $meta as $meta_data ) {
                    switch ( $meta_data['key'] ) {
                        case '_menu_item_object_id':
                            $args['menu-item-object-id'] = $meta_data['value'];
                            break;
                        case '_menu_item_object':
                            $args['menu-item-object'] = $meta_data['value'];
                            break;
                        case '_menu_item_menu_item_parent':
                            if ( '0' !== $meta_data['value'] ) {
                                $args['menu-item-parent-id'] = $meta_data['value'];
                            }
                            break;
                        case '_menu_item_type':
                            $args['menu-item-type'] = $meta_data['value'];
                            break;
                        case '_menu_item_url':
                            if ( '' !== $meta_data['value'] ) {
                                $args['menu-item-url'] = $meta_data['value'];
                            }
                            break;
                        case '_menu_item_target':
                            if ( '' !== $meta_data['value'] ) {
                                $args['menu-item-target'] = $meta_data['value'];
                            }
                            break;
                        case '_menu_item_xfn':
                            if ( '' !== $meta_data['value'] ) {
                                $args['menu-item-xfn'] = $meta_data['value'];
                            }
                            break;
                        case '_wxr_import_term':
                            if ( ! empty( $meta_data['value'] ) ) {
                                if ( 'nav_menu' === $meta_data['value']['taxonomy'] ) {
                                    $menu_object = wp_get_nav_menu_object( $meta_data['value']['slug'] );
                                }
                            }
                            break;
                    }
                }
                if ( isset( $menu_object ) && ! empty( $menu_object ) ) {

                    $menu_items = wp_get_nav_menu_items( $menu_object );

                    $remapping = true;
                    if ( $menu_items ) {
                        foreach ( $menu_items as $menu_item ) {
                            if ( $menu_item->ID === $data['post_id'] ) {
                                $remapping = false;
                            }
                        }
                    }

                    if ( $remapping ) {
                        $this->logger->info(
                            sprintf(
                            /* translators: %1$s: Menu Item Title, %2$s: Name of Menu, %3$s: Menu Item Id */

                                __( 'Remapping "%1$s" from (%2$s) - %3$s', 'nk-themes-helper' ),
                                $data['post_title'],
                                $menu_object->name,
                                $data['post_id']
                            )
                        );

                        $item_id = wp_update_nav_menu_item( $menu_object->term_id, $data['post_id'], $args );

                        if ( ! is_wp_error( $item_id ) ) {
                            $this->logger->info(
                                sprintf(
                                /* translators: %s: term Id */
                                    __( 'Remapping successful - %s', 'nk-themes-helper' ),
                                    $item_id
                                )
                            );
                        }
                    }
                }
                $nkth_imported_menu_items = get_transient( 'nkth_imported_menu_items' );
                unset( $nkth_imported_menu_items[ $key ] );
                set_transient( 'nkth_imported_menu_items', $nkth_imported_menu_items, 5 * MINUTE_IN_SECONDS );
            }
        }
        $this->logger->info( __( 'Remapping Complete!', 'nk-themes-helper' ) );
    }

    /**
     * Send message when a comment has been imported.
     */
    public function imported_comment() {
        $this->update_delta( 'comments' );
    }

    /**
     * Send message when a term has been imported.
     */
    public function imported_term() {
        $this->update_delta( 'terms' );
    }



    /**
     * DEPRECATED old importer methods
     */

    // @codingStandardsIgnoreStart
    public function import_demo_data( $file ) {
        $this->prepare_demo_importer();
        $this->logger = new \NKTH\WPContentImporter2\WPImporterLogger_HTML();
        $this->wp_import = new \NKTH\WPContentImporter2\NKTH_WXRImporter(
            array(
                'fetch_attachments' => true,
            )
        );
        $this->wp_import->set_logger( $this->logger );

        $result = $this->wp_import->import( $file );

        if ( is_wp_error( $result ) ) {
            echo $result->get_error_message();
            return $result;
        }
    }
    private function nkth_wie_import_data( $file ) {
        if ( ! file_exists( $file ) ) {
            return new WP_Error( 'widget-import-error', esc_html__( 'Widgets import file could not be found.', 'nk-themes-helper' ) );
        }
        $data = file_get_contents( $file );
        $data = json_decode( $data );
        return wie_import_data( $data );
    }
    public function import_demo_widgets( $file ) {
        $this->prepare_demo_importer();
        $import_widgets_result = $this->nkth_wie_import_data( $file );
        if ( is_wp_error( $import_widgets_result ) ) {
            echo '<p>' . $import_widgets_result->get_error_message() . '</p>';
        } else {
            echo '<p>Widgets imported.</p>';
        }
    }
    public function import_rev_slider( $file, $slider = false ) {
        if ( ! class_exists( 'RevSlider' ) ) {
            echo '<p>Revolution Slider plugin is not installed.</p>';
            return;
        }
        if ( ! $slider ) {
            $slider = new RevSlider();
        }
        if ( is_array( $file ) ) {
            foreach ( $file as $a ) {
                $this->import_rev_slider( $a, $slider );
            }
            return;
        }
        $this->prepare_demo_importer();
        if ( file_exists( $file ) ) {
            $file_hash = md5_file( $file );

            // check if slider already exists
            $imported = nkth()->get_option( 'revslider_' . $file_hash, false );
            if ( $imported ) {
                $all_sliders = $slider->getArrSlidersShort();
                if ( isset( $all_sliders[ $imported ] ) ) {
                    return;
                }
            }

            // import new slider
            $response = $slider->importSliderFromPost( true, true, $file );
            if ( $response && isset( $response['sliderID'] ) ) {
                nkth()->update_option( 'revslider_' . $file_hash, $response['sliderID'] );
            }
            echo '<p>' . basename( $file ) . ' imported.</p>';
        }
    }
    public function import_demo_options_tree( $file ) {
        $this->prepare_demo_importer();
        if ( function_exists( 'ot_options_id' ) && file_exists( $file ) ) {
            $import_options_data = file_get_contents( $file );
            $import_options_data = maybe_unserialize( base64_decode( $import_options_data ) );

            if ( ! empty( $import_options_data ) || is_array( $import_options_data ) ) {
                update_option( ot_options_id(), $import_options_data );
                echo '<p>Options imported.</p>';
            } else {
                echo '<p>Options import error.</p>';
            }
        }
    }
    public function import_demo_customizer( $file ) {
        $this->prepare_demo_importer();
        $importer = new NKTH_Customizer_Import();
        $importer->import_images = true;
        $importer->_import( $file );
    }
    // @codingStandardsIgnoreEnd
}
