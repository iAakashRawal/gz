<?php
/**
 * Admin Class for _nK themes
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class NKTH_Theme_Dashboard_Pages
 */
class NKTH_Theme_Dashboard_Pages {
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
            self::$instance->init_globals();
        }
        return self::$instance;
    }

    /**
     * NKTH_Theme_Dashboard_Pages constructor.
     */
    private function __construct() {
        /* We do nothing here! */
    }

    /**
     * Init Global variables
     */
    private function init_globals() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'in_admin_header', array( $this, 'in_admin_header' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_body_class', array( $this, 'admin_body_class' ) );
        add_filter( 'admin_footer_text', array( $this, 'print_pages_footer' ) );
    }

    /**
     * Admin init action
     */
    public function admin_init() {
        if ( isset( $_GET['nk-deactivate'] ) && 'deactivate-plugin' === $_GET['nk-deactivate'] ) {
            check_admin_referer( 'nk-deactivate', 'nk-deactivate-nonce' );

            $plugins = TGM_Plugin_Activation::$instance->plugins;

            foreach ( $plugins as $plugin ) {
                if ( isset( $_GET['plugin'] ) && $plugin['slug'] === $_GET['plugin'] ) {
                    deactivate_plugins( $plugin['file_path'] );
                }
            }
        }
        if ( isset( $_GET['nk-activate'] ) && 'activate-plugin' === $_GET['nk-activate'] ) {
            check_admin_referer( 'nk-activate', 'nk-activate-nonce' );

            $plugins = TGM_Plugin_Activation::$instance->plugins;

            foreach ( $plugins as $plugin ) {
                if ( isset( $_GET['plugin'] ) && $plugin['slug'] === $_GET['plugin'] ) {
                    activate_plugin( $plugin['file_path'] );

                    // phpcs:ignore
                    wp_redirect( admin_url( 'admin.php?page=nk-theme-plugins' ) );
                    exit;
                }
            }
        }
    }

    /**
     * Admin menus
     */
    public function admin_menu() {
        if ( ! is_array( nkth()->theme_dashboard()->options['pages'] ) || ! current_user_can( 'edit_theme_options' ) ) {
            return;
        }

        $main_item_title = nkth()->theme_dashboard()->theme_name;
        $badge           = '';

        if ( nkth()->theme_dashboard()->updater()->is_update_available() ) {
            $badge = __( 'New', 'nk-themes-helper' );
        }

        // review notice.
        if ( nkth()->theme_dashboard()->is_show_ask_for_review_notice() ) {
            $badge = $badge ? 2 : 1;
        }

        if ( $badge ) {
            $main_item_title .= ' <span class="awaiting-mod">' . $badge . '</span>';
        }

        $parent_slug = 'nk-theme';

        // add top menu.
        add_menu_page(
            $main_item_title,
            $main_item_title,
            'edit_theme_options',
            $parent_slug,
            array( $this, 'print_pages' ),
            'dashicons-admin-nk',
            '3.22222'
        );

        // add submenus.
        global $submenu;
        foreach ( nkth()->theme_dashboard()->options['pages'] as $name => $page ) {
            if ( isset( $page['external_uri'] ) ) {
                // @codingStandardsIgnoreLine
                $submenu[ $parent_slug ][] = array( $page['title'], 'edit_theme_options', $name );
            } else {
                add_submenu_page(
                    $parent_slug,
                    $page['title'],
                    $page['title'],
                    'edit_theme_options',
                    $name,
                    array( $this, 'print_pages' )
                );
            }
        }
    }

    /**
     * Admin navigation.
     */
    public function in_admin_header() {
        if ( ! function_exists( 'get_current_screen' ) ) {
            return;
        }

        $screen = get_current_screen();

        // Determine if the current page being viewed is "Lazy Blocks" related.
        if (
            ! isset( $screen->post_type ) ||
            // phpcs:ignore
            ! isset( $_GET['page'] ) ||
            // phpcs:ignore
            ! ( 'nk-theme' === $_GET['page'] || 'nk-theme-plugins' === $_GET['page'] || 'nk-theme-demos' === $_GET['page'] ) ||
            ( isset( $screen->is_block_editor ) && $screen->is_block_editor() )
        ) {
            return;
        }

        global $submenu, $submenu_file, $plugin_page;

        $parent_slug = 'nk-theme';
        $tabs        = array();

        // Generate array of navigation items.
        if ( isset( $submenu[ $parent_slug ] ) ) {
            foreach ( $submenu[ $parent_slug ] as $i => $sub_item ) {

                // Check user can access page.
                if ( ! current_user_can( $sub_item[1] ) ) {
                    continue;
                }

                // Define tab.
                $tab = array(
                    'text' => $sub_item[0],
                    'url'  => $sub_item[2],
                );

                // Convert submenu slug "test" to "$parent_slug&page=test".
                if ( ! strpos( $sub_item[2], '.php' ) && 0 !== strpos( $sub_item[2], 'https://' ) ) {
                    $tab['url'] = add_query_arg( array( 'page' => $sub_item[2] ), admin_url( 'admin.php' ) );
                }

                // Detect active state.
                if ( $submenu_file === $sub_item[2] || $plugin_page === $sub_item[2] ) {
                    $tab['is_active'] = true;
                }

                $tabs[] = $tab;
            }
        }

        // Bail early if set to false.
        if ( false === $tabs ) {
            return;
        }

        ?>
        <div class="nk-theme-admin-toolbar">
            <h2>
                <i class="dashicons-admin-nk-dark"></i>
                <?php echo esc_html( nkth()->theme_dashboard()->theme_name ); ?>
            </h2>
            <?php
            foreach ( $tabs as $tab ) {
                printf(
                    '<a class="nk-theme-admin-toolbar-tab%s" href="%s">%s</a>',
                    ! empty( $tab['is_active'] ) ? ' is-active' : '',
                    esc_url( $tab['url'] ),
                    // phpcs:ignore
                    $tab['text']
                );
            }
            ?>
        </div>
        <?php
    }

    /**
     * Admin body class
     *
     * @param string $classes - classes.
     *
     * @return string
     */
    public function admin_body_class( $classes ) {
        // phpcs:ignore
        $this_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : false;
        $pages     = nkth()->theme_dashboard()->options['pages'];

        if ( isset( $pages ) && is_array( $pages ) ) {
            foreach ( $pages as $k => $page ) {
                if ( $k === $this_page ) {
                    $classes .= ' nk-theme-page';
                    return $classes;
                }
            }
        }
        return $classes;
    }

    /**
     * Print pages
     */
    public function print_pages() {
        ?>
        <div class="wrap about-wrap nk-theme-wrap">
            <?php
            $tab = 'nk-theme';

            // phpcs:ignore
            if ( isset( $_GET['page'] ) ) {
                // phpcs:ignore
                $tab = sanitize_text_field( wp_unslash( $_GET['page'] ) );
            }
            ?>

            <div id="poststuff">
                <?php
                if ( isset( nkth()->theme_dashboard()->options['pages'][ $tab ] ) && isset( nkth()->theme_dashboard()->options['pages'][ $tab ]['template'] ) ) {
                    require nkth()->plugin_path . '/theme-dashboard/admin_pages/' . nkth()->theme_dashboard()->options['pages'][ $tab ]['template'];
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Print pages footer
     *
     * @param String $return - footer text.
     * @return String
     */
    public function print_pages_footer( $return ) {
        $screen = get_current_screen();

        if ( 'nk-theme' === $screen->parent_base ) {
            require nkth()->plugin_path . '/theme-dashboard/admin_pages/footer.php';
            return '';
        }

        return $return;
    }
}

