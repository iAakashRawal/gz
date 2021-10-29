<?php
/**
 * Youplay Shortcodes
 */

/* Add Button to MCE */
add_action('init', 'add_yp_shortcodes');
if ( ! function_exists( 'add_yp_shortcodes' ) ) :
function add_yp_shortcodes() {
    // Don't bother doing this stuff if the current user lacks permissions
    if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
        return;

     // Add only in Rich Editor mode
    if ( get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "add_yp_shortcodes_tinymce_plugin");
        add_filter('mce_buttons', 'register_yp_shortcode_buttons');

        wp_enqueue_style( 'yp-shortcodes', youplay_core()->plugin_url . 'shortcodes/css/mce.css' );
    }
}
endif;

if ( ! function_exists( 'register_yp_shortcode_buttons' ) ) :
function register_yp_shortcode_buttons($buttons) {
    array_push(
        $buttons,
        "|",
        "yp_text",
        "yp_button",
        "yp_button_group",
        "yp_banner",
        "yp_tabs",
        "yp_accordion",
        "yp_carousel",
        "yp_posts_carousel",
        "yp_recent_posts",
        "yp_features",
        "yp_single_image",
        "yp_progress_bar",
        "yp_label",
        "yp_alert",
        "yp_countdown"
    );
    return $buttons;
}
endif;

// Load the TinyMCE plugin
if ( ! function_exists( 'add_yp_shortcodes_tinymce_plugin' ) ) :
function add_yp_shortcodes_tinymce_plugin($plugin_array) {
    $shortcodeJSURL = youplay_core()->plugin_url . "shortcodes/js";

    $plugin_array["yp_text"] = $shortcodeJSURL . "/yp-text.js";
    $plugin_array["yp_button"] = $shortcodeJSURL . "/yp-button.js";
    $plugin_array["yp_button_group"] = $shortcodeJSURL . "/yp-button-group.js";
    $plugin_array["yp_banner"] = $shortcodeJSURL . "/yp-banner.js";
    $plugin_array["yp_tabs"] = $shortcodeJSURL . "/yp-tabs.js";
    $plugin_array["yp_accordion"] = $shortcodeJSURL . "/yp-accordion.js";
    $plugin_array["yp_carousel"] = $shortcodeJSURL . "/yp-carousel.js";
    $plugin_array["yp_posts_carousel"] = $shortcodeJSURL . "/yp-posts-carousel.js";
    $plugin_array["yp_recent_posts"] = $shortcodeJSURL . "/yp-recent-posts.js";
    $plugin_array["yp_features"] = $shortcodeJSURL . "/yp-features.js";
    $plugin_array["yp_single_image"] = $shortcodeJSURL . "/yp-single-image.js";
    $plugin_array["yp_progress_bar"] = $shortcodeJSURL . "/yp-progress-bar.js";
    $plugin_array["yp_label"] = $shortcodeJSURL . "/yp-label.js";
    $plugin_array["yp_alert"] = $shortcodeJSURL . "/yp-alert.js";
    $plugin_array["yp_countdown"] = $shortcodeJSURL . "/yp-countdown.js";

    return $plugin_array;
}
endif;


// Check if $var isset / true / 1
if ( ! function_exists( 'yp_check' ) ) :
function yp_check($var) {
    return !(!isset($var) || $var === false || $var === 'false' || $var === 0 || $var === "0" || $var === "");
}
endif;

// custom CSS tab
if (!function_exists( 'yp_get_css_tab' )) :
    function yp_get_css_tab () {
        return array(
            array(
                'type'       => 'css_editor',
                'heading'    => esc_html__( 'CSS', 'youplay-core'),
                'param_name' => 'vc_css',
                'group'      => esc_html__( 'Design Options', 'youplay-core'),
            )
        );
    }
endif;

// return additional class from Visual Composer style tab
if (!function_exists( 'yp_get_css_tab_class' )) :
    function yp_get_css_tab_class ($atts = array()) {
        $result = ' ';
        if (function_exists('vc_shortcode_custom_css_class') && isset($atts['vc_css'])) {
            $result = ' ' . vc_shortcode_custom_css_class($atts['vc_css']) . ' ';
        }
        return $result;
    }
endif;


/* include all shortcodes */
require youplay_core()->plugin_path . "/shortcodes/nk-title.php";
require youplay_core()->plugin_path . "/shortcodes/yp-text.php";
require youplay_core()->plugin_path . "/shortcodes/nk-box.php";
require youplay_core()->plugin_path . "/shortcodes/yp-progress-bar.php";
require youplay_core()->plugin_path . "/shortcodes/yp-button.php";
require youplay_core()->plugin_path . "/shortcodes/yp-single-image.php";
require youplay_core()->plugin_path . "/shortcodes/yp-tabs.php";
require youplay_core()->plugin_path . "/shortcodes/yp-accordion.php";
require youplay_core()->plugin_path . "/shortcodes/yp-features.php";
require youplay_core()->plugin_path . "/shortcodes/yp-label.php";
require youplay_core()->plugin_path . "/shortcodes/yp-alert.php";
require youplay_core()->plugin_path . "/shortcodes/yp-carousel.php";
require youplay_core()->plugin_path . "/shortcodes/yp-posts-carousel.php";
require youplay_core()->plugin_path . "/shortcodes/yp-recent-posts.php";
require youplay_core()->plugin_path . "/shortcodes/yp-countdown.php";
require youplay_core()->plugin_path . "/shortcodes/nk-testimonials.php";
require youplay_core()->plugin_path . "/shortcodes/nk-pricing-table.php";
require youplay_core()->plugin_path . "/shortcodes/nk-twitter.php";
require youplay_core()->plugin_path . "/shortcodes/nk-instagram.php";

// deprecated
require youplay_core()->plugin_path . "/shortcodes/yp-banner.php";
