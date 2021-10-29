<?php
/**
 * YP Single Image
 *
 * Example:
 * [yp_single_image img_src="14" img_size="500x375_crop" link_to_full_image="true" href="" target="_self" rel="noopener noreferrer" icon="fa fa-search-plus" center="false"]
 */

add_shortcode( 'yp_single_image', 'yp_single_image' );

if ( ! function_exists( 'yp_single_image' ) ) :
function yp_single_image($atts, $content = null) {
    extract(shortcode_atts(array(
        "img_src"            => "",
        "img_size"           => "500x375_crop",
        "link_to_full_image" => false,
        "href"               => "",
        "target"             => "_self",
        "rel"                => "",
        "icon"               => "fa fa-search-plus",
        "center"             => false,
        "class"              => ""
    ), $atts));

    $img = $img_full = $img_src;
    $icon = yp_check($icon) ? "<span class='" . esc_attr($icon) . " icon'></span>" : "";
    $max_width = '';
    $before = $after = '';

    if(is_numeric($img_src)) {
      $img = wp_get_attachment_image_src( $img_src, $img_size );
      $img = $img[0];
      $img_full = yp_check($link_to_full_image) ? wp_get_attachment_image_src( $img_src, "full" ) : array('');
      $img_full = $img_full[0];
      $max_width = "style='width: " . esc_attr($img[1]) . "px;'";
    }

    if($center) {
      $before = '<div class="align-center">';
      $after = '</div>';
    }

    if($link_to_full_image) {
      $href = $img_full;
      $target = '';
      $rel = '';
      $class .= ' image-popup';
    } else {
      $target = ' target="' . esc_attr($target) . '"';

      if ( $rel ) {
        $rel = ' rel="' . esc_attr( $rel ) . '"';
      }
    }

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return $before . "<a href='" . esc_url($href) . "' " . $target . " " . $rel . " " . $max_width . " class='angled-img " . esc_attr($class) . "'>
              <div class='img'>
                <img src='" . esc_url($img) . "' alt=''>
              </div>
              $icon
            </a>" . $after;
}
endif;



/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_yp_single_image" );
if ( ! function_exists( 'vc_yp_single_image' ) ) :
function vc_yp_single_image() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name" => esc_html__("nK Single Image", 'youplay-core'),
           "base" => "yp_single_image",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-single-image",
           "params" => array_merge( array(
              array(
                 "type" => "attach_image",
                 "heading" => esc_html__("Image", 'youplay-core'),
                 "param_name" => "img_src",
                 "value" => "",
                 "description" => "",
                 "admin_label" => true,
              ),
              array(
                 "type" => "dropdown",
                 "heading" => esc_html__("Image Size", 'youplay-core'),
                 "param_name" => "img_size",
                 "value" => yp_get_intermediate_image_sizes(),
                 "std" => "500x375_crop",
                 "description" => "",
                 "admin_label" => true,
              ),
              array(
                 "type" => "iconpicker",
                 "heading" => esc_html__("Icon", 'youplay-core'),
                 "param_name" => "icon",
                 "value" => "fa fa-search-plus"
              ),
              array(
                  "type" => "checkbox",
                  "heading" => esc_html__( "Link to Full Image", 'youplay-core' ),
                  "param_name" => "link_to_full_image",
                  "value" => array( "" => true )
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Link", 'youplay-core'),
                 "param_name"  => "href",
                 "value"       => "",
                 "description" => '',
                'dependency' => array(
                  'element' => 'link_to_full_image',
                  'value_not_equal_to' => array(
                    "1"
                  ),
                ),
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Target", 'youplay-core'),
                 "param_name"  => "target",
                 "value"       => "",
                 "description" => '',
                'dependency' => array(
                  'element' => 'link_to_full_image',
                  'value_not_equal_to' => array(
                    "1"
                  ),
                ),
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Rel", 'youplay-core'),
                 "param_name"  => "rel",
                 "value"       => "",
                 "description" => '',
                'dependency' => array(
                  'element' => 'link_to_full_image',
                  'value_not_equal_to' => array(
                    "1"
                  ),
                ),
              ),
              array(
                  "type" => "checkbox",
                  "heading" => esc_html__( "Center", 'youplay-core' ),
                  "param_name" => "center",
                  "value" => array( "" => true )
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
