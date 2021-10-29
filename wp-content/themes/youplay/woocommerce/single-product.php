<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$boxed_cont = yp_opts('single_product_boxed_cont', true);

get_header( 'shop' ); ?>

    <?php while ( have_posts() ) : the_post();
        $banner = strpos(yp_opts('single_product_layout', true), 'banner') !== false && has_post_thumbnail();
        $rev_slider = yp_opts('single_post_revslider', true) && function_exists('putRevSlider');
        ?>

        <section class="content-wrap <?php echo ($banner||$rev_slider?'':'no-banner'); ?>">

            <?php
                /**
                 * woocommerce_before_single_product_summary hook
                 *
                 * @hooked woocommerce_show_product_sale_flash - 10
                 * @hooked woocommerce_show_product_images - 20
                 */
                remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
                do_action( 'woocommerce_before_single_product_summary' );
            ?>

            <div class="<?php echo ($boxed_cont?'container':'container-fluid'); ?> youplay-store">
                <div class="row">
                	<?php
                		/**
                		 * woocommerce_before_main_content hook
                		 *
                		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                		 * @hooked woocommerce_breadcrumb - 20
                		 */
                		do_action( 'woocommerce_before_main_content' );
                	?>

                	<?php wc_get_template_part( 'content', 'single-product' ); ?>

                	<?php
                		/**
                		 * woocommerce_after_main_content hook
                		 *
                		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                		 */
                		do_action( 'woocommerce_after_main_content' );
                	?>

                    <?php
                        /**
                         * woocommerce_sidebar hook
                         *
                         * @hooked woocommerce_get_sidebar - 10
                         */
                        do_action( 'woocommerce_sidebar' );
                    ?>
                </div>

            </div>

            <?php woocommerce_output_related_products(); ?>

    <?php endwhile; // end of the loop. ?>

<?php get_footer( 'shop' ); ?>
