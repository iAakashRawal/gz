<?php
/**
 * nK Admin for nK Themes
 * http://themeforest.net/user/_nk/portfolio
 *
 * @package nK
 */
if(!class_exists('nK_Admin')):
    class nK_Admin {
        /**
         * The single class instance.
         *
         * @since 1.0.0
         * @access private
         *
         * @var object
         */
        private static $_instance = null;

        /**
        * Main Instance
        * Ensures only one instance of this class exists in memory at any one time.
        */
        public static function instance () {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
                self::$_instance->init_globals();
                self::$_instance->init_includes();
                self::$_instance->init_actions();
                self::$_instance->init_theme_dashboard();
            }
            return self::$_instance;
        }

        private function __construct () {
            /* We do nothing here! */
        }

        /**
         * Init Global variables
         */
        private function init_globals () {
            $this->admin_path = get_template_directory() . '/admin';
            $this->admin_uri = get_template_directory_uri() . '/admin';
        }

        /**
         * Init Included Files
         */
        private function init_includes () {
            require $this->admin_path . '/theme-migration.php';
            require $this->admin_path . '/theme-options.php';
            require $this->admin_path . '/theme-metaboxes.php';
            require $this->admin_path . '/menu/backend-walker.php';
            require $this->admin_path . '/menu/frontend-walker.php';

            if(is_admin()) {
                require $this->admin_path . '/plugins-activation.php';
            }
        }

        /**
         * Setup the hooks, actions and filters.
         */
        private function init_actions () {
            if (is_admin()) {
                add_action('admin_print_styles', array($this, 'admin_print_styles'));
            }
        }

        /**
         * Print Styles
         */
        public function admin_print_styles () {
            wp_enqueue_style('fontawesome', $this->admin_uri . '/assets/css/font-awesome.min.css');
            wp_enqueue_style('bootstrap-icons', $this->admin_uri . '/assets/css/bootstrap-icons.min.css');
            wp_enqueue_style('youplay-admin', $this->admin_uri . '/assets/css/style.css', '', '3.7.4');
            wp_enqueue_script('youplay-admin', $this->admin_uri . '/assets/js/script.min.js', '', '3.7.4', true);
        }

        /**
         * Add Theme Dashboard
         */
        public function init_theme_dashboard () {
            if ( function_exists( 'nk_theme' ) && method_exists( nk_theme(), 'theme_dashboard' ) ) {
                add_action('import_end', array( $this, 'import_ot' ));

                $theme_demo_path = nk_admin()->admin_path . '/demos/';

                $demos = array(
                    'dark'    => array(
                        'title'     => esc_html__( 'Dark', 'youplay' ),
                        'preview'   => 'https://wp.nkdev.info/youplay/main/',
                        'thumbnail' => get_template_directory_uri() . '/admin/assets/images/demos/dark.jpg',
                        'demo_data' => array(
                            'blog_options'        => array(
                                'permalink'           => '/%postname%/',
                                'page_on_front_title' => 'Main',
                                'posts_per_page'      => 6
                            ),
                            'woocommerce_options' => array(
                                'shop_page_title'      => 'Shop',
                                'cart_page_title'      => 'Cart',
                                'checkout_page_title'  => 'Checkout',
                                'myaccount_page_title' => 'My Account',
                            ),
                            'navigations'         => array(
                                'Main Menu'  => 'primary',
                                'Right Menu' => 'primary-right',
                            ),
                            'demo_data_file'      => $theme_demo_path . 'dark/content.xml',
                            'optiontree_file'     => $theme_demo_path . 'dark/theme_options.txt',
                            'rev_slider_file'     => $theme_demo_path . 'dark/product_slider.zip',
                            'widgets_file'        => $theme_demo_path . 'dark/widgets.json'
                        )
                    ),
                    'shooter' => array(
                        'title'     => esc_html__( 'Shooter', 'youplay' ),
                        'preview'   => 'https://wp.nkdev.info/youplay/demos/shooter/',
                        'thumbnail' => get_template_directory_uri() . '/admin/assets/images/demos/shooter.jpg',
                        'demo_data' => array(
                            'blog_options'        => array(
                                'permalink'           => '/%postname%/',
                                'page_on_front_title' => 'Main',
                                'posts_per_page'      => 6
                            ),
                            'navigations'         => array(
                                'Main Menu'  => 'primary',
                            ),
                            'demo_data_file'      => $theme_demo_path . 'shooter/content.xml',
                            'optiontree_file'     => $theme_demo_path . 'shooter/theme_options.txt',
                            'widgets_file'        => $theme_demo_path . 'shooter/widgets.json'
                        )
                    ),
                    'anime'   => array(
                        'title'     => esc_html__( 'Anime', 'youplay' ),
                        'preview'   => 'https://wp.nkdev.info/youplay/demos/anime/',
                        'thumbnail' => get_template_directory_uri() . '/admin/assets/images/demos/anime.jpg',
                        'demo_data' => array(
                            'blog_options'        => array(
                                'permalink'           => '/%postname%/',
                                'page_on_front_title' => 'Main',
                                'page_for_posts_title' => 'Blog',
                                'posts_per_page'      => 6
                            ),
                            'navigations'         => array(
                                'Main Menu'  => 'primary',
                            ),
                            'demo_data_file'      => $theme_demo_path . 'anime/content.xml',
                            'optiontree_file'     => $theme_demo_path . 'anime/theme_options.txt',
                            'widgets_file'        => $theme_demo_path . 'anime/widgets.json'
                        )
                    ),
                    'light'   => array(
                        'title'     => esc_html__( 'Light', 'youplay' ),
                        'preview'   => 'https://wp.nkdev.info/youplay/demos/light/',
                        'thumbnail' => get_template_directory_uri() . '/admin/assets/images/demos/light.jpg',
                        'demo_data' => array(
                            'blog_options'        => array(
                                'permalink'           => '/%postname%/',
                                'page_on_front_title' => 'Main',
                                'posts_per_page'      => 6
                            ),
                            'woocommerce_options' => array(
                                'shop_page_title'      => 'Shop',
                                'cart_page_title'      => 'Basket',
                                'checkout_page_title'  => 'Checkout',
                                'myaccount_page_title' => 'My Account',
                            ),
                            'navigations'         => array(
                                'Main Menu'  => 'primary',
                            ),
                            'demo_data_file'      => $theme_demo_path . 'light/content.xml',
                            'optiontree_file'     => $theme_demo_path . 'light/theme_options.txt',
                            'widgets_file'        => $theme_demo_path . 'light/widgets.json'
                        )
                    )
                );

                $pages = array(
                    'nk-theme' => array(
                        'title' => esc_html__( 'Dashboard', 'youplay' ),
                        'template' => 'dashboard.php',
                    ),
                    'nk-theme-plugins' => array(
                        'title'    => esc_html__( 'Plugins', 'youplay' ),
                        'template' => 'plugins.php',
                    ),
                    'nk-theme-demos' => array(
                        'title'    => esc_html__( 'Demo Import', 'youplay' ),
                        'template' => 'demos.php',
                    ),
                );

                if ( class_exists( 'OT_Loader' ) ) {
                    $pages[ admin_url( 'admin.php?page=ot-theme-options' ) ] = array(
                        'external_uri' => true,
                        'title' => esc_html__( 'Options', 'youplay' ),
                    );
                }

                $pages[ 'https://nk.ticksy.com/' ] = array(
                    'external_uri' => true,
                    'title' => esc_html__( 'Support', 'youplay' ),
                );

                nk_theme()->theme_dashboard( array(
                    'theme_title'         => 'Youplay',
                    'theme_id'            => '11959042',
                    'theme_version'       => '3.7.4',
                    'theme_uri'           => 'https://themeforest.net/item/youplay-gaming-wordpress-theme/11959042?ref=_nK',
                    'theme_documentation' => 'https://nkdev.info/docs/youplay-wp/',
                    'theme_changelog'     => 'https://nkdev.info/docs/youplay-wp/log/',
                    'ask_for_review'      => true,
                    'is_envato_elements'  => false,
                    'demos' => $demos,
                    'pages' => $pages,
                ) );
            }
        }

        /**
         * OptionTree Import
         */
        public function import_ot () {
            if ( ! function_exists( 'nk_theme' ) ) {
                return;
            }

            $demo_options = nk_theme()->theme_dashboard()->options;
            $demo_name = isset( $_GET['demo_name'] ) ? sanitize_text_field( wp_unslash( $_GET['demo_name'] ) ) : 'main';

            if ( get_transient( 'youplay_import_ot' . $demo_name ) ) {
                return;
            }

            // get demo data.
            if ( ! isset( $demo_options ) || ! isset( $demo_options['demos'] ) || ! count( $demo_options['demos'] ) || ! isset( $demo_options['demos'][ $demo_name ] ) ) {
                return;
            }

            $demo_data = $demo_options['demos'][ $demo_name ]['demo_data'];

            if ( isset( $demo_data['optiontree_file'] ) ) {
                set_transient( 'youplay_import_ot' . $demo_name, true, MINUTE_IN_SECONDS * 20 );
                nk_theme()->demo_importer()->import_demo_options_tree( $demo_data['optiontree_file'] );
            }
        }
    }
endif;

if ( ! function_exists( 'nk_admin' ) ) :
    function nk_admin() {
        return nK_Admin::instance();
    }
endif;

nk_admin();
