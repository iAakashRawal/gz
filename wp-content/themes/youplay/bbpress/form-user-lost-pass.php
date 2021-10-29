<?php

/**
 * User Lost Password Form
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<form method="post" action="<?php bbp_wp_login_action( array( 'action' => 'lostpassword', 'context' => 'login_post' ) ); ?>">
		<h2><?php _e( 'Lost Password', 'youplay' ); ?></h2>

			<p><?php _e( 'Username or Email', 'youplay' ); ?>: </p>
			<div class="youplay-input">
				<input type="text" name="user_login" value="" size="20" id="user_login" tabindex="<?php bbp_tab_index(); ?>" />
			</div>

		<?php do_action( 'login_form', 'resetpass' ); ?>

		<button type="submit" tabindex="<?php bbp_tab_index(); ?>" name="user-submit" class="btn btn-default btn-lg pull-right"><?php _e( 'Reset My Password', 'youplay' ); ?></button>

		<?php bbp_user_lost_pass_fields(); ?>

		<div class="clearfix"></div>
</form>
