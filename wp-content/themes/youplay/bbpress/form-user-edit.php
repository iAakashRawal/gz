<?php

/**
 * bbPress User Profile Edit Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<form action="<?php bbp_user_profile_edit_url( bbp_get_displayed_user_id() ); ?>" method="post" enctype="multipart/form-data">

	<h2><?php _e( 'Name', 'youplay' ) ?></h2>

	<?php do_action( 'bbp_user_edit_before' ); ?>

		<?php do_action( 'bbp_user_edit_before_name' ); ?>

		<p><?php _e( 'First Name', 'youplay' ) ?></p>
		<div class="youplay-input">
			<input type="text" name="first_name" id="first_name" value="<?php bbp_displayed_user_field( 'first_name', 'edit' ); ?>" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<p><?php _e( 'Last Name', 'youplay' ) ?></p>
		<div class="youplay-input">
			<input type="text" name="last_name" id="last_name" value="<?php bbp_displayed_user_field( 'last_name', 'edit' ); ?>" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<p><?php _e( 'Nickname', 'youplay' ); ?></p>
		<div class="youplay-input">
			<input type="text" name="nickname" id="nickname" value="<?php bbp_displayed_user_field( 'nickname', 'edit' ); ?>" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<p><?php _e( 'Display Name', 'youplay' ) ?></p>

		<div class="youplay-select">
			<?php bbp_edit_user_display_name(); ?>
		</div>

		<?php do_action( 'bbp_user_edit_after_name' ); ?>



	<h2><?php _e( 'Contact Info', 'youplay' ) ?></h2>

		<?php do_action( 'bbp_user_edit_before_contact' ); ?>

			<p><?php _e( 'Website', 'youplay' ) ?></p>
			<div class="youplay-input">
				<input type="text" name="url" id="url" value="<?php bbp_displayed_user_field( 'user_url', 'edit' ); ?>" class="regular-text code" tabindex="<?php bbp_tab_index(); ?>" />
			</div>

		<?php foreach ( bbp_edit_user_contact_methods() as $name => $desc ) : ?>

			<p><?php echo apply_filters( 'user_' . $name . '_label', $desc ); ?></p>
			<div class="youplay-input">
				<input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>" value="<?php bbp_displayed_user_field( $name, 'edit' ); ?>" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />
			</div>

		<?php endforeach; ?>

		<?php do_action( 'bbp_user_edit_after_contact' ); ?>


	<h2><?php bbp_is_user_home_edit() ? _e( 'About Yourself', 'youplay' ) : _e( 'About the user', 'youplay' ); ?></h2>

		<?php do_action( 'bbp_user_edit_before_about' ); ?>

		<p><?php _e( 'Biographical Info', 'youplay' ); ?></p>
		<div class="youplay-textarea">
			<textarea name="description" id="description" rows="5" cols="30" tabindex="<?php bbp_tab_index(); ?>"><?php bbp_displayed_user_field( 'description', 'edit' ); ?></textarea>
		</div>

		<?php do_action( 'bbp_user_edit_after_about' ); ?>


	<h2><?php _e( 'Account', 'youplay' ) ?></h2>

		<?php do_action( 'bbp_user_edit_before_account' ); ?>

		<p><?php _e( 'Username', 'youplay' ); ?></p>
		<div class="youplay-input">
			<input type="text" name="user_login" id="user_login" value="<?php bbp_displayed_user_field( 'user_login', 'edit' ); ?>" disabled="disabled" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<p><?php _e( 'Email', 'youplay' ); ?></p>
		<div class="youplay-input">
			<input type="text" name="email" id="email" value="<?php bbp_displayed_user_field( 'user_email', 'edit' ); ?>" class="regular-text" tabindex="<?php bbp_tab_index(); ?>" />
		</div>

		<?php

		// Handle address change requests
		$new_email = get_option( bbp_get_displayed_user_id() . '_new_email' );
		if ( !empty( $new_email ) && $new_email !== bbp_get_displayed_user_field( 'user_email', 'edit' ) ) : ?>

			<span class="updated inline">

				<?php printf( __( 'There is a pending email address change to <code>%1$s</code>. <a href="%2$s">Cancel</a>', 'youplay' ), $new_email['newemail'], esc_url( self_admin_url( 'user.php?dismiss=' . bbp_get_current_user_id()  . '_new_email' ) ) ); ?>

			</span>

		<?php endif; ?>

		<p><?php _e( 'New Password', 'youplay' ); ?></p>
		<span class="description"><?php _e( 'If you would like to change the password type a new one. Otherwise leave this blank.', 'youplay' ); ?></span>
		<div class="row">
			<div class="col-md-6">
				<div class="youplay-input">
					<input type="password" name="pass1" id="pass1" size="16" value="" autocomplete="off" tabindex="<?php bbp_tab_index(); ?>" />
				</div>
			</div>
			<div class="col-md-6">
				<div class="youplay-input">
					<input type="password" name="pass2" id="pass2" size="16" value="" autocomplete="off" tabindex="<?php bbp_tab_index(); ?>" placeholder="<?php _e( 'Type your new password again.', 'youplay' ); ?>" />
				</div>
			</div>
		</div>
		<span class="description indicator-hint"><?php _e( 'Your password should be at least ten characters long. Use upper and lower case letters, numbers, and symbols to make it even stronger.', 'youplay' ); ?></span>

		<?php do_action( 'bbp_user_edit_after_account' ); ?>


	<?php if ( current_user_can( 'edit_users' ) && ! bbp_is_user_home_edit() ) : ?>

		<h2><?php _e( 'User Role', 'youplay' ) ?></h2>

			<?php do_action( 'bbp_user_edit_before_role' ); ?>

			<?php if ( is_multisite() && is_super_admin() && current_user_can( 'manage_network_options' ) ) : ?>

				<p for="super_admin"><?php _e( 'Network Role', 'youplay' ); ?></p>
				<div class="youplay-checkbox ml-10">
					<input class="checkbox" type="checkbox" id="super_admin" name="super_admin"<?php checked( is_super_admin( bbp_get_displayed_user_id() ) ); ?> tabindex="<?php bbp_tab_index(); ?>" />
          <label for="super_admin"><?php _e( 'Grant this user super admin privileges for the Network.', 'youplay' ); ?></label>
        </div>

			<?php endif; ?>

			<?php bbp_get_template_part( 'form', 'user-roles' ); ?>

			<?php do_action( 'bbp_user_edit_after_role' ); ?>


	<?php endif; ?>

	<?php do_action( 'bbp_user_edit_after' ); ?>


	<?php bbp_edit_user_form_fields(); ?>

	<div class="clearfix mt-30"></div>
	<button type="submit" tabindex="<?php bbp_tab_index(); ?>" id="bbp_user_edit_submit" name="bbp_user_edit_submit" class="btn btn-default btn-lg pull-right"><?php bbp_is_user_home_edit() ? _e( 'Update Profile', 'youplay' ) : _e( 'Update User', 'youplay' ); ?></button>

	<div class="clearfix mb-30"></div>
</form>
