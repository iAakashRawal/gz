<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

$shop_id = get_option( 'woocommerce_shop_page_id' );
$side = strpos(yp_opts('single_page_layout', true, $shop_id), 'side-cont') !== false
					? 'left'
					: (strpos(yp_opts('single_page_layout', true, $shop_id ), 'cont-side') !== false
					  ? 'right'
					  : false);
$boxed_cont = yp_opts('single_page_boxed_cont', true, $shop_id);
$banner = strpos(yp_opts('single_page_layout', true, $shop_id ), 'banner') !== false;
$banner = $banner && shortcode_exists( 'yp_banner' );
$banner_cont = yp_opts('single_page_banner_cont', true, $shop_id );
$banner_parallax_speed_metabox = yp_opts('single_page_banner_parallax_speed_type', true) === 'custom';
$banner_parallax_speed = yp_opts('single_page_banner_parallax_speed', $banner_parallax_speed_metabox, $shop_id);

$rev_slider = yp_opts('single_page_revslider', true, $shop_id ) && function_exists('putRevSlider');
$rev_slider_alias = yp_opts('single_page_revslider_alias', true, $shop_id );

get_header( 'shop' );

if($rev_slider) {
	putRevSlider($rev_slider_alias);
	$banner = true;
}
?>

  	<section class="content-wrap <?php echo ($banner?'':'no-banner'); ?>">
		<?php
			// check if layout with banner
			if ($banner && !$rev_slider) {
				$title = '';
				if($banner_cont) {
					$title = $banner_cont;
				} else if ( apply_filters( 'woocommerce_show_page_title', true ) ) {
					$title = '<h1 class="h2 entry-title">' . woocommerce_page_title(false) . '</h1>';
				}

				echo do_shortcode('[yp_banner img_src="' . yp_opts('single_page_banner_image', true, $shop_id) . '" img_size="1920x1080" banner_size="' . yp_opts('single_page_banner_size', true, $shop_id) . '" parallax="' . yp_opts('single_page_banner_parallax', true, $shop_id) . '" parallax_speed="' . $banner_parallax_speed . '" top_position="true"]' . wp_kses_post($title) . '[/yp_banner]');
			} else if(!$rev_slider) {
				if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
					<h1 class="<?php echo ($boxed_cont?'container':''); ?> entry-title"><?php woocommerce_page_title(); ?></h1>
				<?php endif;
			}
		?>

		<div class="<?php echo ($boxed_cont?'container':'container-fluid'); ?> youplay-store store-grid">
            <div class="row">
                <?php $layout = yp_get_layout_data(); ?>

    			<?php
                    echo '<main class="' . esc_attr($layout['content_class']) . '">';

                    /**
                     * Hook: woocommerce_before_main_content.
                     *
                     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                     * @hooked woocommerce_breadcrumb - 20
                     * @hooked WC_Structured_Data::generate_website_data() - 30
                     */
    				if (!yp_opts('shop_show_breadcrumbs')) {
    					remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    				}
    				remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    				do_action( 'woocommerce_before_main_content' );

    				$side = strpos(yp_opts('single_page_layout', true, $shop_id), 'side-cont') !== false
    					? 'left'
    					: (strpos(yp_opts('single_page_layout', true, $shop_id), 'cont-side') !== false
    						? 'right'
    						: false);
    			?>

                <?php
                /**
                 * Hook: woocommerce_archive_description.
                 *
                 * @hooked woocommerce_taxonomy_archive_description - 10
                 * @hooked woocommerce_product_archive_description - 10
                 */
                do_action( 'woocommerce_archive_description' );

                if ( woocommerce_product_loop() ) {

                    /**
                     * Hook: woocommerce_before_shop_loop.
                     *
                     * @hooked wc_print_notices - 10
                     * @hooked woocommerce_result_count - 20
                     * @hooked woocommerce_catalog_ordering - 30
                     */
                    if (!yp_opts('shop_show_result_count')) {
                        remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
                    }
                    if (!yp_opts('shop_show_order_by')) {
                        remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
                    }
                    do_action( 'woocommerce_before_shop_loop' );

                    woocommerce_product_loop_start();

                    if ( wc_get_loop_prop( 'total' ) ) {
                        while ( have_posts() ) {
                            the_post();

                            /**
                             * Hook: woocommerce_shop_loop.
                             *
                             * @hooked WC_Structured_Data::generate_product_data() - 10
                             */
                            do_action( 'woocommerce_shop_loop' );

                            wc_get_template_part( 'content', 'product' );
                        }
                    }

                    woocommerce_product_loop_end();

                    /**
                     * Hook: woocommerce_after_shop_loop.
                     *
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action( 'woocommerce_after_shop_loop' );


                    // If comments are open or we have at least one comment, load up the comment template
                    if ((comments_open() || get_comments_number()) ) :
                        comments_template();
                    endif;

    			} else {
                    /**
                     * Hook: woocommerce_no_products_found.
                     *
                     * @hooked wc_no_products_found - 10
                     */
                    do_action( 'woocommerce_no_products_found' );
                }

                /**
                 * Hook: woocommerce_after_main_content.
                 *
                 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                 */
                do_action( 'woocommerce_after_main_content' );

                /**
                 * Hook: woocommerce_sidebar.
                 *
                 * @hooked woocommerce_get_sidebar - 10
                 */
                do_action( 'woocommerce_sidebar' );
    			?>
            </div>
		</div>

<?php get_footer( 'shop' ); ?>
