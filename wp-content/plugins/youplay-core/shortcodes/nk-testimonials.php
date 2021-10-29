<?php
/**
 * nK Testimonials
 *
 * Example:
 * [nk_testimonials name="John" img_src="32"]My Text[/nk_testimonials]
 */

add_shortcode( 'nk_testimonials', 'nk_testimonials' );

if ( ! function_exists( 'nk_testimonials' ) ) :
function nk_testimonials($atts, $content = null) {
    extract(shortcode_atts(array(
        "name"    => "",
        "img_src" => "",
        "class"   => ""
    ), $atts));

    if(is_numeric($img_src)) {
      $img = wp_get_attachment_image_src( $img_src, 'thumbnail' );
      $img_src = $img[0];
    }

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return '<div class="testimonials ' . esc_attr($class) . '">
                <blockquote>
                    <p class="clients-words">
                        ' . do_shortcode($content) . '
                    </p>
                    <small class="author-name">' . $name . '</small>
                    <img class="img-circle" alt="' . esc_attr($name) . '" src="' . esc_attr($img_src) . '" />
                </blockquote>
            </div>';
}
endif;



/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_nk_testimonials" );
if ( ! function_exists( 'vc_nk_testimonials' ) ) :
function vc_nk_testimonials() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"     => esc_html__("nK Testimonials", 'youplay-core'),
           "base"     => "nk_testimonials",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-testimonials",
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
                 "type"        => "textfield",
                 "heading"     => esc_html__("Name", 'youplay-core'),
                 "param_name"  => "name",
                 "value"       => "",
                 "description" => '',
                 "admin_label" => true,
              ),
              array(
                 "type" => "attach_image",
                 "heading" => esc_html__("Avatar", 'youplay-core'),
                 "param_name" => "img_src",
                 "value" => "",
                 "description" => "",
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
