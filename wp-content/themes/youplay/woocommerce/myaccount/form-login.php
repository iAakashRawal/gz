<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
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

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="u-columns col2-set" id="customer_login">

	<div class="u-column1 col-1">

<?php endif; ?>

    <h2><?php esc_html_e( 'Login', 'youplay' ); ?></h2>

    <form class="woocommerce-form-login login" method="post">

        <?php do_action( 'woocommerce_login_form_start' ); ?>

        <label for="username"><?php esc_html_e( 'Username or email address', 'youplay' ); ?>&nbsp;<span class="required">*</span></label>
        <div class="youplay-input">
            <input type="text" class="input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
        </div>

        <label for="password"><?php esc_html_e( 'Password', 'youplay' ); ?>&nbsp;<span class="required">*</span></label>
        <div class="youplay-input">
            <input class="input-text" type="password" name="password" id="password" autocomplete="current-password" />
        </div>

        <?php do_action( 'woocommerce_login_form' ); ?>

        <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
        <button type="submit" class="btn btn-default woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'youplay' ); ?>"><?php esc_html_e( 'Log in', 'youplay' ); ?></button>

        <div class="youplay-checkbox dib ml-15 woocommerce-form-login__rememberme">
            <input name="rememberme" type="checkbox" id="rememberme" value="forever" />
            <label for="rememberme" style="line-height: normal;"><?php esc_html_e( 'Remember me', 'youplay' ); ?></label>
        </div>

        <p class="lost_password">
            <a href="<?php echo esc_url( wc_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'youplay' ); ?></a>
        </p>

        <?php do_action( 'woocommerce_login_form_end' ); ?>

    </form>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

	</div>

	<div class="u-column2 col-2">

		<h2><?php esc_html_e( 'Register', 'youplay' ); ?></h2>

		<form method="post" class="register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<label for="reg_username"><?php esc_html_e( 'Username', 'youplay' ); ?>&nbsp;<span class="required">*</span></label>
				<div class="youplay-input">
                    <input type="text" class="input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
				</div>

			<?php endif; ?>

            <label for="reg_email"><?php esc_html_e( 'Email address', 'youplay' ); ?>&nbsp;<span class="required">*</span></label>
            <div class="youplay-input">
                <input type="email" class="input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
            </div>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<label for="reg_password"><?php esc_html_e( 'Password', 'youplay' ); ?>&nbsp;<span class="required">*</span></label>
				<div class="youplay-input">
					<input type="password" class="input-text" name="password" id="reg_password" autocomplete="new-password" />
				</div>

            <?php else : ?>

                <p><?php esc_html_e( 'A password will be sent to your email address.', 'youplay' ); ?></p>

            <?php endif; ?>

			<?php do_action( 'woocommerce_register_form' ); ?>

            <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
            <button type="submit" class="btn btn-default" name="register" value="<?php esc_attr_e( 'Register', 'youplay' ); ?>"><?php esc_html_e( 'Register', 'youplay' ); ?></button>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>

</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
