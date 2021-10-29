<?php
/**
 * BuddyPress - Members Settings Capabilities
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/settings/profile.php */
do_action( 'bp_before_member_settings_template' ); ?>

<form action="<?php echo bp_displayed_user_domain() . bp_get_settings_slug() . '/capabilities/'; ?>" name="account-capabilities-form" id="account-capabilities-form" class="standard-form" method="post">

	<?php

	/**
	 * Fires before the display of the submit button for user capabilities saving.
	 *
	 * @since 1.6.0
	 */
	do_action( 'bp_members_capabilities_account_before_submit' ); ?>

	<div class="youplay-checkbox ml-10">
		<input type="checkbox" name="user-spammer" id="user-spammer" value="1" <?php checked( bp_is_user_spammer( bp_displayed_user_id() ) ); ?> />
		<label for="user-spammer"><?php _e( 'This user is a spammer.', 'youplay' ); ?></label>
	</div>

	<div class="submit">
        <span class="btn btn-default">
            <?php esc_html_e( 'Save', 'youplay' ); ?>
            <input type="submit" value="<?php esc_attr_e( 'Save', 'youplay' ); ?>" id="capabilities-submit" name="capabilities-submit" />
        </span>
	</div>

	<?php

	/**
	 * Fires after the display of the submit button for user capabilities saving.
	 *
	 * @since 1.6.0
	 */
	do_action( 'bp_members_capabilities_account_after_submit' ); ?>

	<?php wp_nonce_field( 'capabilities' ); ?>

</form>

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/settings/profile.php */
do_action( 'bp_after_member_settings_template' ); ?>
