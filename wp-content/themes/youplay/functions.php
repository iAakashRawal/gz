<?php
/**
 * Youplay functions and definitions
 *
 * @package Youplay
 */

add_action( 'after_setup_theme', 'yp_setup' );
if ( ! function_exists( 'yp_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function yp_setup() {

    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on Youplay, use a find and replace
     * to change 'youplay' to the name of your theme in all the template files
     */
    load_theme_textdomain( 'youplay', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    // Add editor style support.
    add_editor_style();

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    // This theme uses wp_nav_menu() in one location.
    register_nav_menus( array(
        'primary'       => esc_html__( 'Primary Menu', 'youplay' ),
        'primary-right' => esc_html__( 'Primary Right Menu', 'youplay' ),
    ) );

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ) );

    /*
     * Enable support for WooCommerce
     */
    add_theme_support( 'woocommerce' );

    // Add default image sizes
    add_theme_support('post-thumbnails');
    add_image_size('500x375', 500);
    add_image_size('500x375_crop', 500, 375, true);
    add_image_size('200x200', 200, 200, true);
    add_image_size('90x90', 90, 90, true);
    add_image_size('1440x900', 1440);
    add_image_size('1440x900_crop', 1440, 900, true);
    add_image_size('1920x1080', 1920);

    // Register the three useful image sizes for use in Add Media modal
    add_filter( 'image_size_names_choose', 'yp_custom_sizes' );
    if ( ! function_exists( 'yp_custom_sizes' ) ) :
    function yp_custom_sizes( $sizes ) {
        return array_merge( $sizes, array(
            '500x375_crop' => esc_html__( 'Carousel Thumbnail (500x375 crop)', 'youplay' ),
            '500x375'   => esc_html__( 'Carousel Thumbnail (500x375)', 'youplay' ),
            '200x200'   => esc_html__( 'User Avatar (200x200 crop)', 'youplay' ),
            '90x90'     => esc_html__( 'User Small Avatar (90x90 crop)', 'youplay' ),
            '1440x900_crop' => esc_html__( '1440x900 crop', 'youplay' ),
            '1440x900'  => esc_html__( '1440x900', 'youplay' ),
            '1920x1080' => esc_html__( '1920x1080', 'youplay' ),
        ) );
    }
    endif;
}
endif; // yp_setup

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
    $content_width = 1400;
}

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
add_action( 'widgets_init', 'yp_widgets_init' );
if ( ! function_exists( 'yp_widgets_init' ) ) :
function yp_widgets_init() {

    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'youplay' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Default Sidebar', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'WooCommerce Sidebar', 'youplay' ),
        'id'            => 'woocommerce_sidebar',
        'description'   => esc_html__( 'Sidebar for WooCommerce Pages', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'BuddyPress Sidebar', 'youplay' ),
        'id'            => 'buddypress_sidebar',
        'description'   => esc_html__( 'Sidebar for BuddyPress Pages', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'bbPress Sidebar', 'youplay' ),
        'id'            => 'bbpress_sidebar',
        'description'   => esc_html__( 'Sidebar for bbPress Pages', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Matches Sidebar', 'youplay' ),
        'id'            => 'matches_sidebar',
        'description'   => esc_html__( 'Sidebar for Matches Pages', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets 1', 'youplay' ),
        'id'            => 'footer_widgets_1',
        'description'   => esc_html__( 'Footer Widgets 1 Column', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets 2', 'youplay' ),
        'id'            => 'footer_widgets_2',
        'description'   => esc_html__( 'Footer Widgets 2 Column', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets 3', 'youplay' ),
        'id'            => 'footer_widgets_3',
        'description'   => esc_html__( 'Footer Widgets 3 Column', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widgets 4', 'youplay' ),
        'id'            => 'footer_widgets_4',
        'description'   => esc_html__( 'Footer Widgets 4 Column', 'youplay' ),
        'before_widget' => '<div id="%1$s" class="side-block widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title block-title">',
        'after_title'   => '</h4>',
    ) );
}
endif;


/**
 * Enqueue scripts and styles.
 */
add_action( 'wp_enqueue_scripts', 'yp_scripts' );
if ( ! function_exists( 'yp_scripts' ) ) :
function yp_scripts() {
    wp_enqueue_style( 'youplay', get_template_directory_uri() . '/style.css', '', '3.7.4' );
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/dist/css/bootstrap.min.css', '', '3.3.7' );
    wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/assets/vendor/magnific-popup/dist/magnific-popup.css', '', '1.1.0' );
    wp_enqueue_style( 'flickity', get_template_directory_uri() . '/assets/vendor/flickity/dist/flickity.min.css', '', '2.2.0' );

    // deregister bbPress styles
    wp_deregister_style( 'bbp-default' );
    wp_register_style( 'bbp-default', get_template_directory_uri() . '/assets/css/bbpress-styles.css' );

    // theme style
    $theme_style = yp_opts('theme_style');
    $youplay_style_file = '';
    $youplay_style_version = '3.7.4';

    if ($theme_style === 'custom') {
        youplay_maybe_compile_scss();

        if ( function_exists('nk_theme') ) {
            $youplay_style_file = nk_theme()->get_compiled_css_url('youplay-custom.min.css');
            $youplay_style_version = nk_theme()->get_compiled_css_version('youplay-custom.min.css');
        }

        if (!$youplay_style_file) {
            $theme_style = 'dark';
        }
    }

    if($theme_style !== 'custom') {
        $youplay_style_file = get_template_directory_uri() . '/assets/css/youplay-' . $theme_style . '.min.css';
    }

    wp_enqueue_style('youplay-' . $theme_style, $youplay_style_file, array(), $youplay_style_version);

    // rtl
    if(yp_opts('general_rtl')) {
        wp_enqueue_style( 'youplay-rtl', get_template_directory_uri() . '/assets/css/youplay-rtl.min.css', '', '3.7.4' );
    }

    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/vendor/bootstrap/dist/js/bootstrap.min.js', array('jquery'), '3.3.7', true );
    wp_enqueue_script( 'font-awesome', get_template_directory_uri() . '/assets/vendor/fontawesome-free/js/all.js', array(), '5.9.0', true );
    wp_enqueue_script( 'font-awesome-v4-shims', get_template_directory_uri() . '/assets/vendor/fontawesome-free/js/v4-shims.js', array('font-awesome'), '5.9.0', true );
    wp_enqueue_script( 'isotope', get_template_directory_uri() . '/assets/vendor/isotope-layout/dist/isotope.pkgd.min.js', array('jquery', 'imagesloaded'), '3.0.6', true );
    wp_enqueue_script( 'jquery-countdown', get_template_directory_uri() . '/assets/vendor/jquery-countdown/dist/jquery.countdown.min.js', array('jquery'), '2.2.0', true );
    wp_enqueue_script( 'moment-timezone', get_template_directory_uri() . '/assets/vendor/moment-timezone/builds/moment-timezone-with-data.min.js', array('moment'), '0.5.14', true );
    wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/assets/vendor/magnific-popup/dist/jquery.magnific-popup.min.js', '', '1.1.0', true );
    wp_enqueue_script( 'flickity', get_template_directory_uri() . '/assets/vendor/flickity/dist/flickity.pkgd.min.js', array('jquery', 'imagesloaded'), '2.2.0', true );
    wp_enqueue_script( 'object-fit-images', get_template_directory_uri() . '/assets/vendor/object-fit-images/dist/ofi.min.js', '', '3.2.3', true );
    wp_enqueue_script( 'jarallax', get_template_directory_uri() . '/assets/vendor/jarallax/dist/jarallax.min.js', '', '1.10.7', true );
    wp_enqueue_script( 'skrollr', get_template_directory_uri() . '/assets/vendor/skrollr/dist/skrollr.min.js', '', '0.6.30', true );
    wp_enqueue_script( 'hexagon-progress', get_template_directory_uri() . '/assets/vendor/HexagonProgress/jquery.hexagonprogress.min.js', '', '1.2.0', true );

    wp_enqueue_script( 'youplay', get_template_directory_uri() . '/assets/js/youplay.min.js', array('jquery', 'bootstrap', 'isotope', 'imagesloaded', 'jquery-countdown', 'magnific-popup', 'flickity', 'object-fit-images', 'jarallax', 'hexagon-progress'), '3.7.4', true );
    wp_enqueue_script( 'youplay-wp', get_template_directory_uri() . '/assets/js/youplay-wp.min.js', array('jquery', 'youplay'), '3.7.4', true );
    wp_enqueue_script( 'youplay-cf7', get_template_directory_uri() . '/assets/js/youplay-cf7.min.js', array('jquery', 'youplay'), '3.7.4', true );

    wp_enqueue_script( 'youplay-init', get_template_directory_uri() . '/assets/js/youplay-init.min.js', array('jquery', 'youplay'), '3.7.4', true );

    $dataInit = array(
        'enableParallax' => yp_opts('general_parallax'),
        'enableFadeBetweenPages' => yp_opts('general_fade_between_pages') && yp_opts('general_preloader')
    );
    wp_localize_script('youplay-init', 'youplayInitOptions', $dataInit);

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }


    // Custom CSS
    ob_start();
    require get_template_directory() . '/inc/head_styles.php';
    $custom_css = ob_get_clean();
    $custom_css = wp_kses( $custom_css, array( '\'', '\"' ) );
    $custom_css = str_replace( '&gt;' , '>' , $custom_css );
    wp_add_inline_style( 'youplay-' . $theme_style, $custom_css );

    // Custom JS
    wp_add_inline_script( 'youplay-init', yp_opts('general_custom_js') );
}
endif;


// Compile SCSS.
if (!function_exists('youplay_maybe_compile_scss')) :
    function youplay_maybe_compile_scss() {
        if ( yp_opts('theme_style') !== 'custom' || ! function_exists('nk_theme') ) {
            return;
        }

        $theme_colors_from = yp_opts('theme_colors_from') == 'light' ? 'light' : 'dark';
        $theme_main_color = yp_opts('theme_main_color');
        $theme_back_color = yp_opts('theme_back_color');
        $theme_back_grey_color = yp_opts('theme_back_grey_color');
        $theme_text_color = yp_opts('theme_text_color');
        $theme_primary_color = yp_opts('theme_primary_color');
        $theme_success_color = yp_opts('theme_success_color');
        $theme_info_color = yp_opts('theme_info_color');
        $theme_warning_color = yp_opts('theme_warning_color');
        $theme_danger_color = yp_opts('theme_danger_color');
        $theme_skew_size = yp_opts('theme_skew_size');
        $theme_navbar_height = yp_opts('theme_navbar_height');
        $theme_navbar_small_height = yp_opts('theme_navbar_small_height');
        $theme_banners_opacity = yp_opts('theme_banners_opacity') / 100;
        $theme_images_opacity = yp_opts('theme_images_opacity') / 100;
        $theme_images_hover_opacity = yp_opts('theme_images_hover_opacity') / 100;

        $theme_data = wp_get_theme();
        $theme_parent = $theme_data->parent();
        if (!empty($theme_parent)) {
            $theme_data = $theme_parent;
        }
        $theme_version = $theme_data['Version'];

        $path = get_template_directory() . '/assets/css/';
        $custom_vars = '
            @import "_helpers.scss";
            @import "_variables.scss";

            $theme_version:"' . $theme_version . '";

            $theme:' . $theme_colors_from . ';
            $main_color:' . $theme_main_color . ';
            $back_color:' . $theme_back_color . ';
            $back_darken_color:' . ($theme_colors_from == 'light' ? '#FFFFFF' : 'darken($back_color, 13)' ) . ';
            $back_grey_color:' . $theme_back_grey_color . ';
            $back_darken_grey_color: ' . ($theme_colors_from == 'light' ? 'lighten' : 'darken') . '($back_grey_color, 10);
            $text_color:' . $theme_text_color . ';
            $text_mute_color:  rgba($text_color, 0.5);
            $color_primary:' . $theme_primary_color . ';
            $color_success:' . $theme_success_color . ';
            $color_info:' . $theme_info_color . ';
            $color_warning:' . $theme_warning_color . ';
            $color_danger:' . $theme_danger_color . ';
            $skew_size:' . $theme_skew_size . 'deg;
            $banners_opacity:' . $theme_banners_opacity . ';
            $images_opacity:' . $theme_images_opacity . ';
            $images_hover_opacity:' . $theme_images_hover_opacity . ';
            $navbar-height:' . $theme_navbar_height . 'px;
            $navbar-sm-height:' . $theme_navbar_small_height . 'px;

            @import "_includes.scss"';

        nk_theme()->scss('youplay-custom.min.css', $path, $custom_vars);
    }
endif;
add_action('ot_after_theme_options_save', 'youplay_maybe_compile_scss');

/**
 * Admin References
 */
require get_template_directory() . '/admin/admin.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Colors convert functions
 */
require get_template_directory() . '/inc/colors.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Comments walker
 */
require get_template_directory() . '/inc/comments-walker.php';

/**
 * Custom WooCommerce functions
 */
require get_template_directory() . '/woocommerce/functions.php';

/**
 * Custom BuddyPress functions
 */
require get_template_directory() . '/buddypress/functions.php';

/**
 * Custom bbPress functions
 */
require get_template_directory() . '/bbpress/functions.php';

/**
 * Infinitie Scroll for Posts
 */
require get_template_directory() . '/inc/lib/nk-infinite-scroll/nk-infinitie-scroll.php';
