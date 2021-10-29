<?php
/**
 * YP Text
 *
 * Example:
 * [yp_text boxed="false"]My Text[/yp_text]
 */

add_shortcode( 'yp_text', 'yp_text' );

if ( ! function_exists( 'yp_text' ) ) :
function yp_text($atts, $content = null) {
    extract(shortcode_atts(array(
        "boxed" => false,
        "class" => ""
    ), $atts));

    if(yp_check($boxed)) {
      $class .= " container";
    }

    return "<div class='" . esc_attr($class) . "'>" . do_shortcode(yp_fix_content($content)) . "</div>";
}
endif;



/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_yp_text" );
if ( ! function_exists( 'vc_yp_text' ) ) :
function vc_yp_text() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "deprecated" => "3.5.0",
           "name" => esc_html__("nK Text Block", 'youplay-core'),
           "base" => "yp_text",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-text",
           "params" => array(
              array(
                 "type"        => "textarea_html",
                 "heading"     => esc_html__("Inner Text", 'youplay-core'),
                 "param_name"  => "content",
                 "holder"      => "div",
                 "value"       => "",
                 "description" => "",
              ),
              array(
                 "type"        => "checkbox",
                 "heading"     => esc_html__("Boxed", 'youplay-core'),
                 "param_name"  => "boxed",
                 "value"       => array( "" => true ),
                 "description" => esc_html__("Use it when your page content boxed disabled", 'youplay-core'),
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Custom Classes", 'youplay-core'),
                 "param_name"  => "class",
                 "value"       => "",
                 "description" => "",
              ),
           )
        ) );
    }
}
endif;
