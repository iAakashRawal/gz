<?php

require youplay_core()->plugin_path . '/widgets/recent-posts.php';
require youplay_core()->plugin_path . '/widgets/twitter.php';
require youplay_core()->plugin_path . '/widgets/instagram.php';

/* Override WooCommerce Widgets */
add_action( 'widgets_init', 'yp_override_woocommerce_widgets', 15 );
if ( ! function_exists( 'yp_override_woocommerce_widgets' ) ) :
function yp_override_woocommerce_widgets() {
	$override_list = array(
		'WC_Widget_Recently_Viewed'     => 'class-wc-widget-recently-viewed.php',
		'WC_Widget_Top_Rated_Products'  => 'class-wc-widget-top-rated-products.php',
		'WC_Widget_Products'            => 'class-wc-widget-products.php',
		'WC_Widget_Recent_Reviews'      => 'class-wc-widget-recent-reviews.php',
		'WC_Widget_Product_Categories'  => 'class-wc-widget-product-categories.php',
		'WC_Widget_Price_Filter'        => 'class-wc-widget-price-filter.php',
		'WC_Widget_Product_Tag_Cloud'   => 'class-wc-widget-product-tag-cloud.php'
	);

	foreach($override_list as $key => $val) {
		if ( class_exists( $key ) ) {
			unregister_widget( $key );
			include_once( youplay_core()->plugin_path . '/widgets/woocommerce/' . $val );
			register_widget( 'yp_' . $key );
		}
	}
}
endif;

/* Override bbPress Widgets */
add_action( 'widgets_init', 'yp_override_bbpress_widgets', 16 );
if ( ! function_exists( 'yp_override_bbpress_widgets' ) ) :
function yp_override_bbpress_widgets() {
  $override_list = array(
    'BBP_Login_Widget'     => 'bbp-login-widget.php',
    'BBP_Views_Widget'     => 'bbp-views-widget.php',
    'BBP_Forums_Widget'    => 'bbp-forums-widget.php',
    'BBP_Topics_Widget'    => 'bbp-topics-widget.php',
    'BBP_Replies_Widget'   => 'bbp-replies-widget.php',
  );

  foreach($override_list as $key => $val) {
    if ( class_exists( $key ) ) {
      unregister_widget( $key );
      include_once( youplay_core()->plugin_path . '/widgets/bbpress/' . $val );
      register_widget( 'yp_' . $key );
    }
  }
}
endif;
