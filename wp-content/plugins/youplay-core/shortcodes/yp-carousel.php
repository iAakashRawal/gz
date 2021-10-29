<?php
/**
 * YP Carousel
 *
 * Example:
 * [yp_carousel style="1" width="100%" autoplay="" loop="true" stage_padding="70" item_padding="0" badges_always_show="false" center="false" boxed="false"]
 *    [yp_carousel_img img_src="88" title="Image 1" href="https://nkdev.info" target="_self" rel="noopener noreferrer" rating="3" badge_text="Badge 1" badge_color="default" price="$10"]
 *    [yp_carousel_img img_src="85" title="Image 2" href="https://nkdev.info" target="_self" rel="noopener noreferrer" rating="5" badge_text="Badge 2" badge_color="primary" price="$14"]
 * [/yp_carousel]
 */
if ( ! function_exists( 'youplay_is_video' ) ) :
function youplay_is_video($url) {
    return strpos($url, 'youtube') > 0 || strpos($url, 'vimeo') > 0 || strpos($url, 'youtu.be') > 0;
}
endif;

add_shortcode( 'yp_carousel', 'yp_carousel' );

if ( ! function_exists( 'yp_carousel' ) ) :
function yp_carousel($atts, $content = null) {
    if( !yp_check($content) ) {
      return '';
    }

    extract(shortcode_atts(array(
        "style"               => 1,
        "size"                => 4,
        "width"               => "100%",
        "autoplay"            => "",
        "loop"                => true,
        "stage_padding"       => 70,
        "item_padding"        => 0,
        "show_arrows"         => true,
        "show_dots"           => false,
        "badges_always_show"  => false,
        "center"              => false,
        "img_size"            => '500x375_crop',
        "boxed"               => false,
        "class"               => ""
    ), $atts));

    $cont_class = $class;
    $style_attr = '';

    if ( yp_check($width) ) {
        if ( preg_match('/px$/', $width) ) {
            $style_attr .= 'width: 100%;';
        }
        $style_attr .= 'max-width: ' . $width . ';';
    }

    // size
    if ($size) {
        $cont_class .= ' youplay-carousel-size-' . $size;
    }

    // autoplay
    $autoplay = intval($autoplay);
    if($autoplay) {
      $autoplay = 'data-autoplay="' . $autoplay . '"';
    } else {
      $autoplay = '';
    }

    // extract image shortcodes
    preg_match_all( '/yp_carousel_img([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
    $items = array();
    if ( isset( $matches[1] ) ) {
      $items = $matches[1];
    }

    // center
    if($center) {
        $cont_class .= ' mauto';
    }

    $before = $after = '';
    if(yp_check($boxed)) {
      $before = '<div class="container">';
      $after = '</div>';
    }

    // each carousel item
    $result_items = '';
    foreach ( $items as $item ) {
      $item_atts = shortcode_parse_atts($item[0]);

      extract(shortcode_atts(array(
        "href"        => "",
        "target"      => "_self",
        "rel"         => "",
        "img_src"     => "",
        "rating"      => "",
        "title"       => "",
        "badge_text"  => "",
        "badge_color" => "default",
        "price"       => "",
        "class"       => ""
      ), $item_atts));

      $href = yp_check($href) ? "href='" . esc_url($href) . "'" : "";
      $target = yp_check($target) ? "target='" . esc_attr($target) . "'" : "";
      $rel = yp_check($rel) ? "rel='" . esc_attr($rel) . "'" : "";
      $title = yp_check($title) ? "<h4>" . esc_html($title) . "</h4>" : "";
      $icon = "<i class=\"fa fa-search-plus icon\"></i>";

      if(is_numeric($img_src)) {
        $img_full = wp_get_attachment_image_src( $img_src, 'full' );
        $img_full = $img_full[0];
        if(!yp_check($href) && $img_full) {
          $href = "href='" . esc_url($img_full) . "'" ;
        }
      }

      $badge = "";
      if( yp_check($badge_text) ) {
        $badge = '<div class="badge ' . esc_attr('bg-' . $badge_color) . (yp_check($badges_always_show)?' show':'') . '">' . esc_html($badge_text) . '</div>';
      }

      // rating
      $rating = yp_get_rating($rating);


      if( yp_check($price) ) {
        $price = '<div class="price">' . esc_html($price) . '</div>';
      }

      // video icon
      if(youplay_is_video($href)) {
        $icon = "<i class=\"fa fa-play icon\"></i>";
      }

      // additional classname for custom styles VC
      $class .= yp_get_css_tab_class($item_atts);

      $item_content = '';
      switch($style) {
        case 2:
          $description = '';
          if( yp_check($price) && yp_check($rating) ) {
            $description =
              '<div class="row">
                <div class="col-xs-6">
                  ' . $rating . '
                </div>
                <div class="col-xs-6">
                  ' . $price . '
                </div>
              </div>';
          } else if( yp_check($price) ) {
            $description = $price;
          } else if( yp_check($rating) ) {
            $description = $rating;
          }

          $item_content =
            '<a class="angled-img ' . esc_attr($class) . '" ' . $href . ' ' . $target . ' ' . $rel .  '>
              <div class="img img-offset">
                ' . youplay_get_image( $img_src, $img_size ) . '
                ' . $badge . '
              </div>
              <div class="bottom-info">
                ' . $title . '
                ' . $description . '
              </div>
            </a>';
          break;

        case 3:
          $item_content =
            '<a class="angled-img ' . esc_attr($class) . '" ' . $href . ' ' . $target . ' ' . $rel .  '>
              <div class="img">
                ' . youplay_get_image( $img_src, $img_size ) . '
                ' . $badge . '
              </div>
              ' . $icon . '
            </a>';
            break;

        case 4:
          $item_content =
            '<a class="angled-img pull-left ' . esc_attr($class) . '" ' . $href . ' ' . $target . ' ' . $rel .  '>
              <div class="img">
                ' . youplay_get_image( $img_src, $img_size ) . '
              </div>
              ' . $icon . '
            </a>';
          break;

        case 5:
          $item_content =
            '<a ' . $href . ' ' . $target . ' ' . $rel .  ' class="' . esc_attr($class) . '">
              ' . youplay_get_image( $img_src, $img_size ) . '
            </a>';
          break;

        default:
          $item_content =
            '<a class="angled-img ' . esc_attr($class) . '" ' . $href . ' ' . $target . ' ' . $rel .  '>
              <div class="img">
                ' . youplay_get_image( $img_src, $img_size ) . '
                ' . $badge . '
              </div>
              <div class="over-info">
                <div>
                  <div>
                    ' . $title . '
                    ' . $rating . '
                    ' . $price . '
                  </div>
                </div>
              </div>
            </a>';
            break;
      }

      $result_items .= $item_content;

    }

    // prepare style attribute
    if ( $style_attr ) {
        $style_attr = 'style="' . esc_attr( $style_attr ) . '"';
    }

    // additional classname for custom styles VC
    $cont_class .= yp_get_css_tab_class($atts);

    switch($style) {
      case 3:
        return $before .
            '<div class="youplay-carousel gallery-popup ' . esc_attr($cont_class) . '" ' . $style_attr . ' ' . $autoplay . ' data-stage-padding="' . esc_attr($stage_padding) . '" data-item-padding="' . esc_attr($item_padding) . '" data-loop="' . esc_attr(yp_check($loop) ? 'true' : 'false') . '" data-dots="' . esc_attr(yp_check($show_dots) ? 'true' : 'false') . '" data-arrows="' . esc_attr(yp_check($show_arrows) ? 'true' : 'false') . '">' . $result_items . '</div>' .
            $after;

      case 4:
        return $before .
            '<div class="youplay-carousel youplay-slider gallery-popup ' . esc_attr($cont_class) . '" ' . $style_attr . ' ' . $autoplay . ' data-stage-padding="' . esc_attr($stage_padding) . '" data-item-padding="' . esc_attr($item_padding) . '" data-loop="' . esc_attr(yp_check($loop) ? 'true' : 'false') . '" data-dots="' . esc_attr(yp_check($show_dots) ? 'true' : 'false') . '" data-arrows="' . esc_attr(yp_check($show_arrows) ? 'true' : 'false') . '">
              ' . $result_items . '
            </div>' .
            $after;

      default:
        return $before .
            '<div class="youplay-carousel ' . esc_attr($cont_class) . '" ' . $style_attr . ' ' . $autoplay . ' data-stage-padding="' . esc_attr($stage_padding) . '" data-item-padding="' . esc_attr($item_padding) . '" data-loop="' . esc_attr(yp_check($loop) ? 'true' : 'false') . '" data-dots="' . esc_attr(yp_check($show_dots) ? 'true' : 'false') . '" data-arrows="' . esc_attr(yp_check($show_arrows) ? 'true' : 'false') . '">' . $result_items . '</div>' .
        $after;
    }
}
endif;

// image for carousel
add_shortcode( 'yp_carousel_img', 'yp_carousel_img' );

if ( ! function_exists( 'yp_carousel_img' ) ) :
function yp_carousel_img($atts, $content = null) {
    // full content inside yp_carousel shortcode
    return '';
}
endif;


/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_youplay_carousel" );
if ( ! function_exists( 'vc_youplay_carousel' ) ) :
function vc_youplay_carousel() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"     => esc_html__("nK Carousel", 'youplay-core'),
           "base"     => "yp_carousel",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-carousel",
           "as_parent" => array('only' => 'yp_carousel_img'),
           "content_element" => true,
           "show_settings_on_create" => false,
           "admin_enqueue_css"       => youplay_core()->plugin_url . "shortcodes/css/yp-carousel-vc-view.css",
           "params"   => array_merge( array(
              array(
                 "type"       => "dropdown",
                 "heading"    => esc_html__("Style", 'youplay-core'),
                 "param_name" => "style",
                 "value"      => array(
                    esc_html__("Style 1", 'youplay-core') => 1,
                    esc_html__("Style 2", 'youplay-core') => 2,
                    esc_html__("Style 3", 'youplay-core') => 3,
                    esc_html__("Style 4", 'youplay-core') => 4,
                    esc_html__("Style 5", 'youplay-core') => 5,
                 ),
                 "admin_label" => true,
                 "description" => ""
              ),
               array(
                   "type"       => "dropdown",
                   "heading"    => esc_html__("Size", 'youplay-core'),
                   "param_name" => "size",
                   "std"        => 4,
                   "value"      => array(
                       esc_html__("1", 'youplay-core') => 1,
                       esc_html__("2", 'youplay-core') => 2,
                       esc_html__("3", 'youplay-core') => 3,
                       esc_html__("4", 'youplay-core') => 4,
                       esc_html__("5", 'youplay-core') => 5,
                       esc_html__("6", 'youplay-core') => 6,
                   ),
                   "admin_label" => true,
                   "description" => ""
               ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Width", 'youplay-core'),
                 "param_name"  => "width",
                 "value"       => esc_html__("100%", 'youplay-core'),
                 "description" => "",
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Autoplay", 'youplay-core'),
                 "param_name"  => "autoplay",
                 "value"       => "",
                 "description" => esc_html__("Type integer value in ms", 'youplay-core')
              ),
              array(
                 "type"        => "checkbox",
                 "heading"     => esc_html__("Loop", 'youplay-core'),
                 "param_name"  => "loop",
                 "std"         => true,
                 "value"       => array( "" => true ),
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Stage Padding", 'youplay-core'),
                 "param_name"  => "stage_padding",
                 "value"       => 70
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Item Padding", 'youplay-core'),
                 "param_name"  => "item_padding",
                 "value"       => 0
              ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Show Arrows", 'youplay-core'),
                   "param_name"  => "show_arrows",
                   "std"         => true,
                   "value"       => array( "" => true ),
                   "description" => "",
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Show Dots", 'youplay-core'),
                   "param_name"  => "show_dots",
                   "value"       => array( "" => true ),
                   "description" => "",
               ),
              array(
                 "type"        => "checkbox",
                 "heading"     => esc_html__("Badges Always Show", 'youplay-core'),
                 "param_name"  => "badges_always_show",
                 "value"       => array( "" => true ),
                 "description" => "When unchecked - show only on mouse over",
              ),
              array(
                 "type"        => "checkbox",
                 "heading"     => esc_html__("Center", 'youplay-core'),
                 "param_name"  => "center",
                 "value"       => array( "" => true ),
              ),
               array(
                   "type" => "dropdown",
                   "heading" => esc_html__("Images Size", 'youplay-core'),
                   "param_name" => "img_size",
                   "value" => yp_get_intermediate_image_sizes(),
                   "std" => "500x375_crop",
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
           ), yp_get_css_tab() ),
           "js_view" => 'VcColumnView'
        ) );
    }
}
endif;


add_action( "after_setup_theme", "vc_youplay_carousel_img" );
if ( ! function_exists( 'vc_youplay_carousel_img' ) ) :
function vc_youplay_carousel_img() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"     => esc_html__("nK Image", 'youplay-core'),
           "base"     => "yp_carousel_img",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-single-image",
           "as_child" => array('only' => 'yp_carousel'),
           "content_element" => true,
           "params"   => array_merge( array(
              array(
                 "type" => "attach_image",
                 "heading" => esc_html__("Image", 'youplay-core'),
                 "param_name" => "img_src",
                 "value" => "",
                 "description" => "",
                 "admin_label" => true,
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Title", 'youplay-core'),
                 "param_name"  => "title",
                 "value"       => esc_html__("Youplay", 'youplay-core'),
                 "description" => "Only for Style 1 and 2",
                 "admin_label" => true,
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Link", 'youplay-core'),
                 "param_name"  => "href",
                 "value"       => "",
                 "description" => esc_html__("Leave this blank, if you want to show full image in popup in 3 and 4 carousel style. If you want to show youtube/vimeo video in popup, simply add link to this input.", 'youplay-core'),
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Target", 'youplay-core'),
                 "param_name"  => "target",
                 "value"       => "",
                 "description" => '',
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Rel", 'youplay-core'),
                 "param_name"  => "rel",
                 "value"       => "",
                 "description" => '',
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Rating", 'youplay-core'),
                 "param_name"  => "rating",
                 "value"       => "",
                 "description" => esc_html__("Write number from 0 to 5. For example: 1 / 2 / 3.5 / etc... Only for Style 1 and 2", 'youplay-core'),
              ),

              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Badge Text", 'youplay-core'),
                 "param_name"  => "badge_text",
                 "value"       => "",
                 "description" => "",
              ),
              array(
                 "type"       => "dropdown",
                 "heading"    => esc_html__("Badge Color", 'youplay-core'),
                 "param_name" => "badge_color",
                 "value"      => array(
                    esc_html__("Default", 'youplay-core') => "default",
                    esc_html__("Primary", 'youplay-core') => "primary",
                    esc_html__("Success", 'youplay-core') => "success",
                    esc_html__("Info", 'youplay-core')    => "info",
                    esc_html__("Warning", 'youplay-core') => "warning",
                    esc_html__("Danger", 'youplay-core')  => "danger",
                 ),
                 "description" => ""
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Price", 'youplay-core'),
                 "param_name"  => "price",
                 "value"       => "",
                 "description" => esc_html__("Only for Style 1 and 2", 'youplay-core'),
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
    class WPBakeryShortCode_yp_carousel extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_yp_carousel_img extends WPBakeryShortCode {
    }
}
