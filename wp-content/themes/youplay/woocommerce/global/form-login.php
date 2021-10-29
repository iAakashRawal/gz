<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( is_user_logged_in() ) {
    return;
}

?>
<form method="post" class="login" style="border: none; padding: 0; <?php echo ( $hidden ? 'display:none;' : '' ); ?>">

	<?php do_action( 'woocommerce_login_form_start' ); ?>

    <?php echo ( $message ) ? wpautop( wptexturize( $message ) ) : ''; // @codingStandardsIgnoreLine ?>

	<div class="form-row form-row-first">
		<p><?php esc_html_e( 'Username or email', 'youplay' ); ?>&nbsp;<span class="required">*</span></p>
		<div class="youplay-input">
			<input type="text" class="input-text" name="username" id="username" autocomplete="username" />
		</div>
	</div>
	<div class="form-row form-row-last">
		<p><?php esc_html_e( 'Password', 'youplay' ); ?>&nbsp;<span class="required">*</span></p>
		<div class="youplay-input">
			<input class="input-text" type="password" name="password" id="password" autocomplete="current-password" />
		</div>
	</div>
	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form' ); ?>

	<div class="form-row">
        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
        <button type="submit" class="btn btn-default" name="login" value="<?php esc_attr_e( 'Login', 'youplay' ); ?>"><?php esc_html_e( 'Login', 'youplay' ); ?></button>
		<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ); ?>" />

		<div class="youplay-checkbox dib ml-15 woocommerce-form-login__rememberme">
			<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
			<label for="rememberme" class="inline"><?php esc_html_e( 'Remember me', 'youplay' ); ?></label>
		</div>
	</div>
	<div class="lost_password">
		<a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'youplay' ); ?></a>
	</div>

	<div class="clear"></div>

	<?php do_action( 'woocommerce_login_form_end' ); ?>

</form>
