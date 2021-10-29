<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

global $product, $yp_showed_woo_image;

$yp_showed_woo_image = true;

$post_thumbnail_id = $product->get_image_id();

$rev_slider = yp_opts('single_product_revslider', true) && function_exists('putRevSlider');
$rev_slider_alias = yp_opts('single_product_revslider_alias', true);
$custom_banner_iamge = yp_opts('single_product_banner_image', true);
$banner = strpos(yp_opts('single_product_layout', true), 'banner') !== false;
$banner = ( $banner && shortcode_exists( 'yp_banner' ) ) || $rev_slider;

// Banner with title
if ( $banner && ( $post_thumbnail_id || $custom_banner_iamge ) && !$rev_slider ) {

  ob_start();
    the_title('<h1 class="h2 entry-title">', '</h1>');
    woocommerce_template_single_add_to_cart();
    $content = ob_get_contents();
  ob_end_clean();

    $banner_parallax_speed_metabox = yp_opts('single_product_banner_parallax_speed_type', true) === 'custom';
    $banner_parallax_speed = yp_opts('single_product_banner_parallax_speed', $banner_parallax_speed_metabox);

  echo '<div class="product"><div class="images">';
  echo do_shortcode('[yp_banner img_src="' . esc_attr( $custom_banner_iamge ? : $post_thumbnail_id ) . '" img_size="1920x1080" banner_size="' .  yp_opts('single_product_banner_size', true) . '" parallax="' .  yp_opts('single_product_banner_parallax', true) . '" parallax_speed="' . $banner_parallax_speed . '" top_position="true" class="youplay-banner-store-main woocommerce-product-gallery__image" image_class="wp-post-image"]' . $content . '[/yp_banner]');
  echo '</div></div>';
?>

<?php } else if(!$rev_slider) {
  the_title('<h1 class="container entry-title">', '</h1>');
  ?>

  <div class="container"><?php woocommerce_template_single_add_to_cart(); ?></div>

  <?php
} else {
  putRevSlider($rev_slider_alias);
  ?>
  <div class="container"><?php woocommerce_template_single_add_to_cart(); ?></div>
  <?php
}

do_action( 'woocommerce_product_thumbnails' );
?>
