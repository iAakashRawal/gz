<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
    return;
}

?>
<div class="woocommerce-form-coupon-toggle">
    <?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'youplay' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'youplay' ) . '</a>' ), 'notice' ); ?>
</div>

<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none; padding: 0; border: none;">
    <p><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'youplay' ); ?></p>

	<div class="form-row form-row-first">
		<div class="youplay-input mb-0">
            <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'youplay' ); ?>" id="coupon_code" value="" />
        </div>
	</div>

	<div class="form-row form-row-last">
        <button class="btn btn-default" type="submit" name="apply_coupon"><?php esc_attr_e( 'Apply Coupon', 'youplay' ); ?></button>
	</div>

	<div class="clear"></div>
</form>
