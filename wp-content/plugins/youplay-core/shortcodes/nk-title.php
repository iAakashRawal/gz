<?php
/**
 * YP Title
 *
 * Example:
 * [nk_title tag="h2" like="h2" boxed="false"]My Title[/nk_title]
 */

add_shortcode( 'nk_title', 'nk_title' );

if ( ! function_exists( 'nk_title' ) ) :
function nk_title($atts, $content = null) {
    extract(shortcode_atts(array(
        "tag"   => "h2",
        "like"  => "h2",
        "boxed" => false,
        "class" => ""
    ), $atts));

    if(yp_check($boxed)) {
      $class .= " container";
    }

    switch($tag) {
        case 'h1':
        case 'h2':
        case 'h3':
        case 'h4':
        case 'h5':
        case 'h6':
            break;
        default:
            $tag = 'div';
            break;
    }

    switch($like) {
        case 'h1':
        case 'h3':
        case 'h4':
        case 'h5':
        case 'h6':
            break;
        default:
            $like = 'h2';
            break;
    }
    $class .= ' ' . $like;

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return "<" . $tag . " class='" . esc_attr($class) . "'>" . do_shortcode(yp_fix_content($content)) . "</" . $tag . ">";
}
endif;



/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_nk_title" );
if ( ! function_exists( 'vc_nk_title' ) ) :
function vc_nk_title() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name" => esc_html__("nK Title", 'youplay-core'),
           "base" => "nk_title",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-title",
           "params" => array_merge( array(
              array(
                 "type"        => "textarea_html",
                 "heading"     => esc_html__("Inner Text", 'youplay-core'),
                 "param_name"  => "content",
                 "holder"      => "div",
                 "value"       => "",
                 "description" => "",
              ),
              array(
                 "type"       => "dropdown",
                 "heading"    => esc_html__("Tag", 'youplay-core'),
                 "param_name" => "tag",
                 "std"        => "h2",
                 "value"      => array(
                    esc_html__("h1", 'youplay-core')  => "h1",
                    esc_html__("h2", 'youplay-core')  => "h2",
                    esc_html__("h3", 'youplay-core')  => "h3",
                    esc_html__("h4", 'youplay-core')  => "h4",
                    esc_html__("h5", 'youplay-core')  => "h5",
                    esc_html__("h6", 'youplay-core')  => "h6",
                    esc_html__("div", 'youplay-core') => "div",
                 ),
                 "description" => ""
              ),
              array(
                 "type"       => "dropdown",
                 "heading"    => esc_html__("Looks Like", 'youplay-core'),
                 "param_name" => "like",
                 "std"        => "h2",
                 "value"      => array(
                    esc_html__("h1", 'youplay-core')  => "h1",
                    esc_html__("h2", 'youplay-core')  => "h2",
                    esc_html__("h3", 'youplay-core')  => "h3",
                    esc_html__("h4", 'youplay-core')  => "h4",
                    esc_html__("h5", 'youplay-core')  => "h5",
                    esc_html__("h6", 'youplay-core')  => "h6"
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
