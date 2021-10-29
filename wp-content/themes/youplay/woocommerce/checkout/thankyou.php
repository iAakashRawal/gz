<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
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
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<?php if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<p><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'youplay' ); ?></p>

		<div class="btn-group">
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="btn btn-default"><?php esc_html_e( 'Pay', 'youplay' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn btn-default"><?php esc_html_e( 'My Account', 'youplay' ); ?></a>
			<?php endif; ?>
		</div>

	<?php else : ?>

        <div class="alert"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'youplay' ), $order ); ?></div>

		<ul class="order_details h4 pl-0 mb-40">
			<li class="order">
				<?php esc_html_e( 'Order Number:', 'youplay' ); ?>
				<strong><?php echo esc_html( $order->get_order_number() ); ?></strong>
			</li>
			<li>
				<?php esc_html_e( 'Date:', 'youplay' ); ?>
				<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
			</li>
            <?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
                <li>
                    <?php esc_html_e( 'Email:', 'youplay' ); ?>
                    <strong><?php echo esc_html( $order->get_billing_email() ); ?></strong>
                </li>
            <?php endif; ?>
			<li class="total">
				<?php esc_html_e( 'Total:', 'youplay' ); ?>
				<strong><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></strong>
			</li>
			<?php if ( $order->get_payment_method_title() ) : ?>
			<li class="method">
				<?php esc_html_e( 'Payment Method:', 'youplay' ); ?>
				<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
			</li>
			<?php endif; ?>
		</ul>

		<div class="clear"></div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

<?php else : ?>

	<div class="alert"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'youplay' ), null ); ?></div>

<?php endif; ?>
