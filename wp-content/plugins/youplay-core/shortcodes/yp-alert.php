<?php
/**
 * YP Alert
 *
 * Example:
 * [yp_alert color="primary" dismissible="false" boxed="false"]<strong>Well done!</strong> You successfully read this important alert message.[/yp_alert]
 */

add_shortcode( 'yp_alert', 'yp_alert' );

if ( ! function_exists( 'yp_alert' ) ) :
function yp_alert($atts, $content = "<strong>Well done!</strong> You successfully read this important alert message.") {
    extract(shortcode_atts(array(
        "color"       => "primary",
        "dismissible" => false,
        "boxed"       => false,
        "class"       => ""
    ), $atts));

    if(yp_check($boxed)) {
        $class .= " container";
    }

    $dismissible_btn = yp_check($dismissible)?'<button type="button" class="close" data-dismiss="alert" aria-label="' . esc_html__("Close", 'youplay-core') . '"><span aria-hidden="true">&times;</span></button>':'';

    $class .= ' alert-' . $color;

    if(yp_check($dismissible)) {
      $class .= ' alert-dismissible';
    }

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return '<div class="alert ' . esc_attr($class) . '" role="alert">' . $dismissible_btn . do_shortcode(yp_fix_content($content)) . '</div>';
}
endif;



/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_youplay_alert" );
if ( ! function_exists( 'vc_youplay_alert' ) ) :
function vc_youplay_alert() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"     => esc_html__("nK Alert", 'youplay-core'),
           "base"     => "yp_alert",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-alert",
           "params"   => array_merge( array(
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
                 "description" => "",
                 "admin_label" => true,
              ),
              array(
                  "type"       => "checkbox",
                  "heading"    => esc_html__( "Dismissible", 'youplay-core' ),
                  "param_name" => "dismissible",
                  "value"      => array( "" => true )
              ),
              array(
                 "type"        => "textarea_html",
                 "heading"     => esc_html__("Inner Text", 'youplay-core'),
                 "param_name"  => "content",
                 "value"       => esc_html__("Well done! You successfully read this important alert message.", 'youplay-core'),
                 "description" => "",
              ),
              array(
                 "type"        => "checkbox",
                 "heading"     => esc_html__("Boxed", 'youplay-core'),
                 "param_name"  => "boxed",
                 "value"       => array( "" => true ),
                 "description" => "Use it when your page content boxed disabled",
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
