<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.5
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );
?>

<form method="post" class="woocommerce-ResetPassword lost_reset_password">

    <p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'youplay' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

	<div class="form-row form-row-first">
		<p for="password_1"><?php esc_html_e( 'New password', 'youplay' ); ?>&nbsp;<span class="required">*</span></p>
		<div class="youplay-input">
			<input type="password" class="input-text" name="password_1" id="password_1" autocomplete="new-password" />
		</div>
	</div>
	<div class="form-row form-row-last">
		<p for="password_2"><?php esc_html_e( 'Re-enter new password', 'youplay' ); ?>&nbsp;<span class="required">*</span></p>
		<div class="youplay-input">
			<input type="password" class="input-text" name="password_2" id="password_2" autocomplete="new-password" />
		</div>
	</div>

	<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
	<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

	<div class="clear"></div>

	<?php do_action( 'woocommerce_resetpassword_form' ); ?>

	<div class="form-row">
		<input type="hidden" name="wc_reset_password" value="true" />
		<button type="submit" class="btn btn-default"><?php esc_attr_e( 'Save', 'youplay' ); ?></button>
	</div>

	<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

</form>

<?php
do_action( 'woocommerce_after_reset_password_form' );
