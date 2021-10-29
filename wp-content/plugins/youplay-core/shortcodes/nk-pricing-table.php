<?php
/**
 * YP Pricing Table
 *
 * Example:
 * [nk_pricing_table]
 *    [yp_pricing_item type="plan"]Standard[/yp_pricing_item]
 *    [yp_pricing_item type="price"]$10[/yp_pricing_item]
 *    [yp_pricing_item]24/7 Support[/yp_pricing_item]
 *    [yp_pricing_item]Lifetime Updates[/yp_pricing_item]
 * [/nk_pricing_table]
 */

add_shortcode( 'nk_pricing_table', 'nk_pricing_table' );

if ( ! function_exists( 'nk_pricing_table' ) ) :
function nk_pricing_table($atts, $content = null) {
    extract(shortcode_atts(array(
        "class"        => ""
    ), $atts));

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return '<ul class="pricing-table ' . esc_attr($class) . '">
                ' . do_shortcode($content) . '
            </ul>';
}
endif;

// pricing table item
add_shortcode( 'nk_pricing_item', 'nk_pricing_item' );

if ( ! function_exists( 'nk_pricing_item' ) ) :
function nk_pricing_item($atts, $content = null) {
    extract(shortcode_atts(array(
        "class"        => "",
        "type"         => ""
    ), $atts));

    switch($type) {
        case 'name':
            $class .= ' plan-name';
            break;
        case 'price':
            $class .= ' plan-price';
            break;
        case 'action':
            $class .= ' plan-action';
            break;
    }

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return '<li class="' . esc_attr($class) . '">
                ' . do_shortcode($content) . '
            </li>';
}
endif;


/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_nk_pricing_table" );
if ( ! function_exists( 'vc_nk_pricing_table' ) ) :
function vc_nk_pricing_table() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"              => esc_html__("nK Pricing Table", 'youplay-core'),
           "base"              => "nk_pricing_table",
           "controls"          => "full",
           "category"          => "nK",
           "icon"              => "icon-nk icon-nk-pricing-table",
           "as_parent"         => array('only' => 'nk_pricing_item'),
           "is_container"      => true,
           "js_view"           => 'VcColumnView',
           "show_settings_on_create" => false,
           "params"            => array_merge( array(
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

/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_nk_pricing_item" );
if ( ! function_exists( 'vc_nk_pricing_item' ) ) :
function vc_nk_pricing_item() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"              => esc_html__("nK Pricing Item", 'youplay-core'),
           "base"              => "nk_pricing_item",
           "controls"          => "full",
           "category"          => "nK",
           "icon"              => "icon-nk icon-nk-pricing-table",
           "as_child"          => array('only' => 'nk_pricing_table'),
           "content_element"   => true,
           "params"            => array_merge( array(
              array(
                 "type"       => "dropdown",
                 "heading"    => esc_html__("Type", 'youplay-core'),
                 "param_name" => "type",
                 "value"      => array(
                    esc_html__("Default", 'youplay-core') => 'default',
                    esc_html__("Name", 'youplay-core')    => 'name',
                    esc_html__("Price", 'youplay-core')   => 'price',
                    esc_html__("Action", 'youplay-core')  => 'action'
                 ),
                 "description" => "",
                 "admin_label" => true,
              ),
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
    class WPBakeryShortCode_nk_pricing_table extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_nk_pricing_item extends WPBakeryShortCode {
    }
}
