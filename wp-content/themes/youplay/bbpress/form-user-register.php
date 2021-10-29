<?php

/**
 * User Registration Form
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<form method="post" action="<?php bbp_wp_login_action( array( 'context' => 'login_post' ) ); ?>">
		<h2><?php _e( 'Create an Account', 'youplay' ); ?></h2>

		<div class="alert">
			<?php _e( 'Your username must be unique, and cannot be changed later.', 'youplay' ) ?>
			<br>
			<?php _e( 'We use your email address to email you a secure password and verify your account.', 'youplay' ) ?>
		</div>

		<p><?php _e( 'Username', 'youplay' ); ?>: </p>
		<div class="youplay-input">
			<input type="text" name="user_login" value="<?php bbp_sanitize_val( 'user_login' ); ?>" size="20" id="user_login" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<p><?php _e( 'Email', 'youplay' ); ?>: </p>
		<div class="youplay-input">
			<input type="text" name="user_email" value="<?php bbp_sanitize_val( 'user_email' ); ?>" size="20" id="user_email" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<?php do_action( 'register_form' ); ?>

		<button type="submit" tabindex="<?php bbp_tab_index(); ?>" name="user-submit" class="btn btn-default btn-lg pull-right"><?php _e( 'Register', 'youplay' ); ?></button>

		<div class="clearfix"></div>

		<?php bbp_user_register_fields(); ?>

</form>
