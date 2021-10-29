<?php
// WooCommerce is active
if ( !class_exists( 'WooCommerce' ) ) {
	return;
}


/* Custom Breadcrumbs */
add_filter( 'woocommerce_breadcrumb_defaults', 'yp_woocommerce_breadcrumbs' );
if ( ! function_exists( 'yp_woocommerce_breadcrumbs' ) ) :
function yp_woocommerce_breadcrumbs($defaults) {
	$defaults['delimiter'] = ' <span class="fa fa-angle-right"></span> ';
	$defaults['wrap_before'] = '<nav class="mb-20">';
	$defaults['wrap_after'] = '</nav>';
	return $defaults;
}
endif;


/* Related Products Count */
add_filter( 'woocommerce_output_related_products_args', 'yp_related_products_args' );
if ( ! function_exists( 'yp_related_products_args' ) ) :
function yp_related_products_args( $args ) {
	$args['posts_per_page'] = 5;
	return $args;
}
endif;

// add share buttons tab
// add custom youplay tab
add_filter( 'woocommerce_product_tabs', 'yp_add_woo_tabs', 98 );
if ( ! function_exists( 'yp_add_woo_tabs' ) ) :
function yp_add_woo_tabs( $tabs ) {
	$tabs['sharing'] = array(
		'priority' => 25,
		'callback' => 'woocommerce_template_single_sharing'
	);

	$tabs['additional_params'] = array(
		'priority' => 26,
		'callback' => 'youplay_woo_additional_tab'
	);
	return $tabs;
}
endif;

if ( ! function_exists( 'youplay_woo_additional_tab' ) ) :
function youplay_woo_additional_tab() {
	$use = yp_opts('single_product_additional_params', true);
	$title = yp_opts('single_product_additional_params_title', true);
	$cont = yp_opts('single_product_additional_params_cont', true);

	if($use) {
		if($title) {
			echo '<h2>' . $title . '</h2>';
		}
		if($cont) {
			echo do_shortcode($cont);
		}
	}
}
endif;



// proceed to checkout button
if ( ! function_exists( 'woocommerce_button_proceed_to_checkout' ) ) :
function woocommerce_button_proceed_to_checkout() {
	$checkout_url = wc_get_checkout_url();
	?>
	<a href="<?php echo esc_url($checkout_url); ?>" class="btn btn-default btn-lg"><?php _e( 'Proceed to Checkout', 'youplay' ); ?></a>
	<?php
}
endif;

if ( ! function_exists( 'yp_get_text_between_tags' ) ) :
function yp_get_text_between_tags($string, $tagname) {
	$pattern = "/<$tagname>(.*)<\/$tagname>/";
	preg_match($pattern, $string, $matches);
	return $matches[1];
}
endif;

// Product Price fix discount
add_filter( 'woocommerce_get_price_html', 'yp_woo_price_html', 100, 2 );
if ( ! function_exists( 'yp_woo_price_html' ) ) :
function yp_woo_price_html( $price ) {
	// check if no <ins> tag and return default value
	if (strpos($price, '<ins>') == false) {
		return $price;
	}

	$old = yp_get_text_between_tags($price, "del");
	$new = yp_get_text_between_tags($price, "ins");
	if($new) {
    	return $new . ($old ? (' <sup><del>' . $old . '</del></sup>') : '');
	} else {
		return $price;
	}
}
endif;

// product discount badge
if ( ! function_exists( 'yp_woo_discount_badge' ) ) :
function yp_woo_discount_badge( $product, $show = true ) {
	$regular = $product->get_regular_price();
	$current = $product->get_sale_price();

	if(is_numeric($regular) && is_numeric($current)) {
		$discount = ceil(100 - 100 * $current / $regular);

		if($discount == 0) {
			return '';
		}

		$bg = ' bg-default';

		if($discount >= 80) {
			$bg = ' bg-success';
		}

		return '<div class="' . esc_attr('badge' . ($show?' show':'') . $bg) . '">-' . $discount . '%</div>';
	} else {
		return '';
	}
}
endif;
