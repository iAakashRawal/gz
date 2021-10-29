<?php

/**
 * User Login Form
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<form method="post" action="<?php bbp_wp_login_action( array( 'context' => 'login_post' ) ); ?>">
		<h2><?php _e( 'Log In', 'youplay' ); ?></h2>

		<p><?php _e( 'Username', 'youplay' ); ?>: </p>
		<div class="youplay-input">
			<input type="text" name="log" value="<?php bbp_sanitize_val( 'user_login', 'text' ); ?>" size="20" id="user_login" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<p><?php _e( 'Password', 'youplay' ); ?>: </p>
		<div class="youplay-input">
			<input type="password" name="pwd" value="<?php bbp_sanitize_val( 'user_pass', 'password' ); ?>" size="20" id="user_pass" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<div class="youplay-checkbox">
			<input type="checkbox" name="rememberme" value="forever" <?php checked( bbp_get_sanitize_val( 'rememberme', 'checkbox' ) ); ?> id="rememberme" tabindex="<?php bbp_tab_index(); ?>" />
			<label for="rememberme"><?php _e( 'Keep me signed in', 'youplay' ); ?></label>
		</div>

		<?php do_action( 'login_form' ); ?>


		<button type="submit" tabindex="<?php bbp_tab_index(); ?>" name="user-submit" class="btn btn-default btn-lg pull-right"><?php _e( 'Log In', 'youplay' ); ?></button>

		<div class="clearfix"></div>

		<?php bbp_user_login_fields(); ?>

</form>
