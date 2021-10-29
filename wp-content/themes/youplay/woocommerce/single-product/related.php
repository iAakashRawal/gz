<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : ?>

	<h2 class="container"><?php _e( 'Related Products', 'youplay' ); ?></h2>

	<?php $relatedProductIds = ""; ?>

	<?php woocommerce_product_loop_start(); ?>

		<?php foreach ( $related_products as $related_product ) :

			$relatedProductIds .= $related_product->get_id() . ",";

		endforeach; ?>

	<?php woocommerce_product_loop_end(); ?>

	<?php echo do_shortcode('[yp_posts_carousel show_rating="true" show_price="true" posts="' . $relatedProductIds . '"]'); ?>

<?php endif;

wp_reset_postdata();
