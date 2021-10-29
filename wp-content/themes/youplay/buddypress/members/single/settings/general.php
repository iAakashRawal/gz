<?php
/**
 * BuddyPress - Members Single Profile
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/settings/profile.php */
do_action( 'bp_before_member_settings_template' ); ?>

<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/general'; ?>" method="post" id="settings-form">

	<?php if ( !is_super_admin() ) : ?>
		<h3 class="mt-40"><?php _e( 'Current Password <small>(required to update email or change current password)</small>', 'youplay' ); ?></h3>
		<div class="form-horizontal mt-30 mb-40">
			<div class="form-group">
				<label class="control-label col-sm-2" for="pwd"><?php _e( 'Current Password', 'youplay' ); ?>:</label>
				<div class="col-sm-10">
					<div class="youplay-input">
						<input type="password" name="pwd" id="pwd" size="16" value="" class="settings-input small" <?php bp_form_field_attributes( 'password' ); ?>/>
					</div>
					<a href="<?php echo wp_lostpassword_url(); ?>" title="<?php esc_attr_e( 'Password Lost and Found', 'youplay' ); ?>"><?php _e( 'Lost your password?', 'youplay' ); ?></a>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<h3 class="mt-40"><?php _e( 'Account Email', 'youplay' ); ?></h3>
	<div class="form-horizontal mt-30 mb-40">
		<div class="form-group">
			<label class="control-label col-sm-2" for="email"><?php _e( 'Email', 'youplay' ); ?>:</label>
			<div class="col-sm-10">
				<div class="youplay-input">
					<input type="email" name="email" id="email" value="<?php echo bp_get_displayed_user_email(); ?>" class="settings-input" <?php bp_form_field_attributes( 'email' ); ?>/>
				</div>
			</div>
		</div>
	</div>

	<h3><?php _e( 'Change Password <small>(leave blank for no change)</small>', 'youplay' ); ?></h3>
	<div class="form-horizontal mt-30 mb-40">
		<div class="form-group">
			<label class="control-label col-sm-2" for="pass1"><?php _e( 'New Password', 'youplay' ); ?>:</label>
			<div class="col-sm-10">
				<div class="youplay-input">
					<input type="password" name="pass1" id="pass1" size="16" value="" class="settings-input small password-entry" <?php bp_form_field_attributes( 'password' ); ?>/>
				</div>
				<div id="pass-strength-result"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="control-label col-sm-2" for="pass2"><?php _e( 'Repeat New Password', 'youplay' ); ?>:</label>
			<div class="col-sm-10">
				<div class="youplay-input">
					<input type="password" name="pass2" id="pass2" size="16" value="" class="settings-input small password-entry-confirm" <?php bp_form_field_attributes( 'password' ); ?>/>
				</div>
			</div>
		</div>
	</div>

	<?php

	/**
	 * Fires before the display of the submit button for user general settings saving.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_core_general_settings_before_submit' ); ?>

	<div class="submit">
        <span class="btn btn-default">
            <?php esc_html_e( 'Save Changes', 'youplay' ); ?>
            <input type="submit" value="<?php esc_attr_e( 'Save Changes', 'youplay' ); ?>" id="submit" name="submit" />
        </span>
	</div>

	<?php

	/**
	 * Fires after the display of the submit button for user general settings saving.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_core_general_settings_after_submit' ); ?>

	<?php wp_nonce_field( 'bp_settings_general' ); ?>

</form>

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/settings/profile.php */
do_action( 'bp_after_member_settings_template' ); ?>
