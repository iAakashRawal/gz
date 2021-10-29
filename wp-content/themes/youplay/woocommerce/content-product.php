<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$shop_style = yp_opts('shop_style')=='row'?'row':'grid';

ob_start();
/**
 * woocommerce_shop_loop_item_title hook.
 *
 * @hooked woocommerce_template_loop_product_title - 10
 */
do_action( 'woocommerce_shop_loop_item_title' );
$title = ob_get_contents();
ob_end_clean();
$title = str_replace('<h2 class="woocommerce-loop-product__title">', '<h2 class="woocommerce-loop-product__title h4">', $title);
$class = '';
?>

<div <?php wc_product_class( $shop_style === 'grid' ? 'item' : '', $product ); ?>>

    <?php
    /**
     * Hook: woocommerce_before_shop_loop_item.
     *
     * @hooked woocommerce_template_loop_product_link_open - 10
     */
	do_action( 'woocommerce_before_shop_loop_item' );
    ?>

	<a href="<?php the_permalink(); ?>" class="<?php echo ($shop_style=='grid'?'angled-img':'item angled-bg'); ?>">

		<?php if($shop_style == 'grid') : ?>

			<div class="img img-offset">
				<?php
					/**
					 * Hook: woocommerce_before_shop_loop_item_title.
					 *
					 * @hooked woocommerce_show_product_loop_sale_flash - 10
					 * @hooked woocommerce_template_loop_product_thumbnail - 10
					 */
					remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
					do_action( 'woocommerce_before_shop_loop_item_title' );
				?>

				<?php
					// add discount badge
					echo yp_woo_discount_badge( $product );
				?>
			</div>
			<div class="bottom-info">
                <?php echo $title; ?>
				<div class="row">
					<?php
					$ratingIsset = is_numeric( $product->get_average_rating() ) && yp_opts('shop_show_ratings');

					if( $ratingIsset ): ?>
						<div class="col-xs-6">
							<?php woocommerce_template_loop_rating(); ?>
						</div>
					<?php endif; ?>

					<div class="col-xs-<?php echo ($ratingIsset?'6':'12'); ?>">
						<div class="price">
							<?php woocommerce_template_loop_price(); ?>
						</div>
					</div>
				</div>
			</div>

		<?php else: ?>

		  <div class="row">
		    <div class="col-lg-2 col-md-3 col-xs-4">
		      <div class="angled-img">
		        <div class="img">
					<?php
						/**
						 * Hook: woocommerce_before_shop_loop_item_title.
						 *
						 * @hooked woocommerce_show_product_loop_sale_flash - 10
						 * @hooked woocommerce_template_loop_product_thumbnail - 10
						 */
						remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
						do_action( 'woocommerce_before_shop_loop_item_title' );
					?>

					<?php
						// add discount badge
						echo yp_woo_discount_badge( $product );
					?>
		        </div>
		      </div>
		    </div>
		    <div class="col-lg-10 col-md-9 col-xs-8">
		      <div class="row">
		        <div class="col-xs-6 col-md-9">
                    <?php
                    echo $title;

					$ratingIsset = is_numeric( $product->get_average_rating() ) && yp_opts('shop_show_ratings');

					if( $ratingIsset ): ?>
						<?php woocommerce_template_loop_rating(); ?>
					<?php endif; ?>

		        </div>
		        <div class="col-xs-6 col-md-3 align-right">
		          <div class="price">
								<?php woocommerce_template_loop_price(); ?>
		          </div>
		        </div>
		      </div>
		    </div>
		  </div>

		<?php endif; ?>

		<?php
			/**
			 * Hook: woocommerce_after_shop_loop_item_title.
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>
	</a>

	<?php

		/**
		 * Hook: woocommerce_after_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		if (!yp_opts('shop_show_add_to_cart') || $shop_style == 'row') {
			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
		}
		do_action( 'woocommerce_after_shop_loop_item' );

	?>

</div>
