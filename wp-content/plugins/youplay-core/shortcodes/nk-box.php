<?php
/**
 * YP Box
 *
 * Example:
 * [nk_box boxed="false"]Content[/nk_box]
 */

add_shortcode( 'nk_box', 'nk_box' );

if ( ! function_exists( 'nk_box' ) ) :
function nk_box($atts, $content = null) {
    extract(shortcode_atts(array(
        "boxed"        => false,
        "class"        => ""
    ), $atts));

    if(yp_check($boxed)) {
      $class .= ' container';
    }

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return "<div class='youplay-box " . esc_attr($class) . "'>
              " . do_shortcode(yp_fix_content($content)) . "
            </div>";
}
endif;


/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_nk_box" );
if ( ! function_exists( 'vc_nk_box' ) ) :
function vc_nk_box() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"              => esc_html__("nK Box", 'youplay-core'),
           "base"              => "nk_box",
           "controls"          => "full",
           "category"          => "nK",
           "icon"              => "icon-nk icon-nk-box",
           "is_container"      => true,
           "js_view"           => 'VcColumnView',
           "params"            => array_merge( array(
              array(
                 "type"        => "checkbox",
                 "heading"     => esc_html__("Boxed", 'youplay-core'),
                 "param_name"  => "boxed",
                 "value"       => array( "" => true ),
                 "description" => esc_html("Use it when your page content boxed disabled", 'youplay-core'),
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Custom Classes", 'youplay-core'),
                 "param_name"  => "class",
                 "value"       => "",
                 "description" => "",
              ),
           ), yp_get_css_tab() )
        ) );
    }
}
endif;

//Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_nk_box extends WPBakeryShortCodesContainer {
    }
}
