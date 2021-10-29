<?php
/**
 * YP Label
 *
 * Example:
 * [yp_label color="default" text="Label"]
 */

add_shortcode( 'yp_label', 'yp_label' );

if ( ! function_exists( 'yp_label' ) ) :
function yp_label($atts, $content = null) {
    extract(shortcode_atts(array(
        "color"    => "default",
        "text"     => "Label",
        "class"    => ""
    ), $atts));

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return '<span class="label ' . esc_attr($class . ' label-' . $color) . '">' . esc_html($text) . '</span>';
}
endif;



/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_youplay_label" );
if ( ! function_exists( 'vc_youplay_label' ) ) :
function vc_youplay_label() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"     => esc_html__("nK Label", 'youplay-core'),
           "base"     => "yp_label",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-label",
           "params"   => array_merge( array(
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Inner Text", 'youplay-core'),
                 "param_name"  => "text",
                 "value"       => esc_html__("Label", 'youplay-core'),
                 "admin_label" => true,
                 "description" => "",
              ),
              array(
                 "type"       => "dropdown",
                 "heading"    => esc_html__("Color", 'youplay-core'),
                 "param_name" => "color",
                 "value"      => array(
                    esc_html__("Default", 'youplay-core') => "",
                    esc_html__("Primary", 'youplay-core') => "primary",
                    esc_html__("Success", 'youplay-core') => "success",
                    esc_html__("Info", 'youplay-core')    => "info",
                    esc_html__("Warning", 'youplay-core') => "warning",
                    esc_html__("Danger", 'youplay-core')  => "danger",
                 ),
                 "description" => ""
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
           ), yp_get_css_tab() )
        ) );
    }
}
endif;
