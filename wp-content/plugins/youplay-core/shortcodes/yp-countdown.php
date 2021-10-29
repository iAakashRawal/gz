<?php
/**
 * YP Countdown
 *
 * Example:
 * [yp_countdown style="default" custom="" date="2017-01-21 12:00" timezone=""]
 */

add_shortcode( 'yp_countdown', 'yp_countdown' );

if ( ! function_exists( 'yp_countdown' ) ) :
function yp_countdown($atts, $content = null) {
    static $countdown_id = 0;
    $countdown_id++;

    extract(shortcode_atts(array(
        "style"    => "default",
        "custom"   => "%D days %H:%M:%S",
        "date"     => "2017-01-21 12:00",
        "timezone" => "",
        "class"    => ""
    ), $atts));

    if(!$date) {
      return "";
    }

    $timer_function = '
      $(this).text(
        event.strftime("%D days %H:%M:%S")
      );
    ';

    if($style == "styled") {
      $class .= " style-1";
      $timer_function = "
        $(this).html(
          event.strftime([
            '<div class=\"countdown-item\">',
                '<span>" . esc_html__('Days', 'youplay-core') . "</span>',
                '<span><span>%D</span></span>',
            '</div>',
            '<div class=\"countdown-item\">',
                '<span>" . esc_html__('Hours', 'youplay-core') . "</span>',
                '<span><span>%H</span></span>',
            '</div>',
            '<div class=\"countdown-item\">',
                '<span>" . esc_html__('Minutes', 'youplay-core') . "</span>',
                '<span><span>%M</span></span>',
            '</div>',
            '<div class=\"countdown-item\">',
                '<span>" . esc_html__('Seconds', 'youplay-core') . "</span>',
                '<span><span>%S</span></span>',
            '</div>'
          ].join(''))
        );";
    } else if($style == 'custom') {
      $timer_function = '
        $(this).html(
          event.strftime("' . $custom . '")
        );
      ';
    }

    wp_add_inline_script( 'youplay', "
        jQuery(function ($) {
            $(\"#youplay_countdown_id_" . intval($countdown_id) . "\").each(function() {
                var tz = $(this).attr('data-timezone');
                var end = $(this).attr('data-end');
                  end = moment.tz(end, tz).toDate();
                $(this).countdown(end, function(event) {
                    " . ($timer_function ? $timer_function : "") . "
                });
            });
        });
    " );

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return '<div class="countdown ' . esc_attr($class) . '" id="youplay_countdown_id_' . intval($countdown_id) . '" data-end="' . esc_attr($date) . '" data-timezone="' . esc_attr($timezone) . '"></div>';
}
endif;



/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_youplay_countdown" );
if ( ! function_exists( 'vc_youplay_countdown' ) ) :
function vc_youplay_countdown() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"     => esc_html__("nK Countdown", 'youplay-core'),
           "base"     => "yp_countdown",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-countdown",
           "params"   => array_merge( array(
              array(
                 "type"       => "dropdown",
                 "heading"    => esc_html__("Style", 'youplay-core'),
                 "param_name" => "style",
                 "value"      => array(
                    esc_html__("Default", 'youplay-core')  => "default",
                    esc_html__("Styled", 'youplay-core')   => "styled",
                    esc_html__("Custom", 'youplay-core')   => "custom"
                  ),
                 "description" => ""
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Custom Markup", 'youplay-core'),
                 "param_name"  => "custom",
                 "value"       => esc_html__("%D days %H:%M:%S", 'youplay-core'),
                 "description" => sprintf(esc_html__("Type here custom coundown markup. More info here %s", 'youplay-core'), "<a href='http://hilios.github.io/jQuery.countdown/' target='_blank'>http://hilios.github.io/jQuery.countdown/</a>"),
                "dependency"  => array(
                  "element"   => "style",
                  "value"     => array( "custom" ),
                ),
              ),
              array(
                 "type"       => "textfield",
                 "heading"    => esc_html__("Date", 'youplay-core'),
                 "param_name" => "date",
                 "value"      => esc_html__("2017-01-21 12:00", 'youplay-core'),
                 "description" => esc_html__("Date Format: YYYY-MM-DD hh:mm", 'youplay-core'),
                 "admin_label" => true,
              ),
              array(
                 "type"       => "dropdown",
                 "heading"    => esc_html__("Time Zone", 'youplay-core'),
                 "param_name" => "timezone",
                 "value"      => youplay_get_tz_list(),
                 "description" => ""
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
