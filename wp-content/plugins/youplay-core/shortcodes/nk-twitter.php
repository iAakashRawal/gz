<?php
// Required plugin nK Themes Helper
if(!function_exists('nk_theme')) {
    return;
}

/**
 * nK Twitter
 *
 * Example:
 * [nk_twitter count="3"]
 */

add_shortcode( 'nk_twitter', 'nk_twitter' );

if ( ! function_exists( 'nk_twitter' ) ) :
function nk_twitter($atts, $content = null) {
    extract(shortcode_atts(array(
        "count" => 3,
        "class" => ''
    ), $atts));

    $result = '';

    // Get the tweets from Twitter.
    nk_theme()->twitter()->set_data(array(
        'consumer_key'         => yp_opts('twitter_consumer_key'),
        'consumer_secret'      => yp_opts('twitter_consumer_secret'),
        'access_token'         => yp_opts('twitter_access_token'),
        'access_token_secret'  => yp_opts('twitter_access_token_secret'),
        'cachetime'            => yp_opts('twitter_cachetime')
    ));
    $tweets = nk_theme()->twitter()->get_tweets($count, yp_opts('twitter_show_replies'));

    if (!nk_theme()->twitter()->has_error() && !empty($tweets)) {
        foreach($tweets as $tweet) {
            if($tweet) {

                $result .= '<div>
                                <div class="youplay-twitter-icon">
                                    <i class="fa fa-twitter"></i>
                                </div>
                                <div class="youplay-twitter-date date">
                                    <span class="twitter-date">' . $tweet->date_formatted . '</span>
                                </div>
                                <div class="youplay-twitter-text">
                                    ' . $tweet->text_entitled . '
                                </div>
                            </div>';
            }
        }
    } else if(nk_theme()->twitter()->has_error()) {
        $result = nk_theme()->twitter()->get_error()->message;
    }

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return '<div class="youplay-twitter ' . esc_attr($class) . '">' . $result . '</div>';
}
endif;


/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_nk_twitter" );
if ( ! function_exists( 'vc_nk_twitter' ) ) :
function vc_nk_twitter() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name" => esc_html__("nK Twitter", 'youplay-core'),
           "base" => "nk_twitter",
           "controls" => "full",
           "category" => "nK",
           "icon" => "icon-nk icon-nk-twitter",
           "params" => array_merge( array(
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Count", 'youplay-core'),
                 "param_name"  => "count",
                 "value"       => 3,
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
