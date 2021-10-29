<?php
/**
 * BuddyPress - Groups Admin
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<div class="item-list-tabs no-ajax clearfix" id="subnav" role="navigation">
	<ul class="pagination no-ajax pagination-sm">
		<?php youplay_bp_group_admin_tabs(); ?>
	</ul>
</div><!-- .item-list-tabs -->

<form action="<?php bp_group_admin_form_action(); ?>" name="group-settings-form" id="group-settings-form" method="post" enctype="multipart/form-data">

<?php

/**
 * Fires inside the group admin form and before the content.
 *
 * @since 1.1.0
 */
do_action( 'bp_before_group_admin_content' ); ?>

<?php /* Edit Group Details */ ?>
<?php if ( bp_is_group_admin_screen( 'edit-details' ) ) : ?>

	<?php

	/**
	 * Fires before the display of group admin details.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_details_admin' ); ?>

	<label for="group-name"><?php _e( 'Group Name (required)', 'youplay' ); ?></label>
	<div class="youplay-input">
		<input type="text" name="group-name" id="group-name" value="<?php bp_group_name(); ?>" aria-required="true" />
	</div>

	<label for="group-desc"><?php _e( 'Group Description (required)', 'youplay' ); ?></label>
	<div class="youplay-textarea">
		<textarea name="group-desc" id="group-desc" aria-required="true" rows="5"><?php bp_group_description_editable(); ?></textarea>
	</div>

	<?php

	/**
	 * Fires after the group description admin details.
	 *
	 * @since 1.0.0
	 */
	do_action( 'groups_custom_group_fields_editable' ); ?>

	<p>
		<div class="youplay-checkbox ml-10">
			<input type="checkbox" name="group-notify-members" id="group-notify-members" value="1" />
			<label for="group-notify-members"><?php _e( 'Notify group members of these changes via email', 'youplay' ); ?></label>
		</div>
	</p>

	<?php

	/**
	 * Fires after the display of group admin details.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_details_admin' ); ?>

    <span class="btn btn-default">
        <?php esc_html_e( 'Save Changes', 'youplay' ); ?>
        <input type="submit" value="<?php esc_attr_e( 'Save Changes', 'youplay' ); ?>" id="save" name="save" />
    </span>
	<?php wp_nonce_field( 'groups_edit_group_details' ); ?>

<?php endif; ?>

<?php /* Manage Group Settings */ ?>
<?php if ( bp_is_group_admin_screen( 'group-settings' ) ) : ?>

	<?php

	/**
	 * Fires before the group settings admin display.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_settings_admin' ); ?>

	<?php if ( bp_is_active( 'forums' ) ) : ?>

		<?php if ( bp_forums_is_installed_correctly() ) : ?>

			<div class="youplay-checkbox">
				<input type="checkbox" name="group-show-forum" id="group-show-forum-check" value="1"<?php bp_group_show_forum_setting(); ?> />
				<label for="group-show-forum-check"><?php _e( 'Enable discussion forum', 'youplay' ); ?></label>
			</div>

		<?php endif; ?>

	<?php endif; ?>

	<h3 class="mt-30 mb-20"><?php _e( 'Privacy Options', 'youplay' ); ?></h3>

	<div class="radio">

		<div class="youplay-radio ml-5">
			<input type="radio" name="group-status" id="group-status-public" value="public"<?php if ( 'public' == bp_get_new_group_status() || !bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> aria-describedby="public-group-description" />
			<label for="group-status-public"><?php _e( 'This is a public group', 'youplay' ); ?></label>
		</div>

		<ul id="public-group-description">
			<li><?php _e( 'Any site member can join this group.', 'youplay' ); ?></li>
			<li><?php _e( 'This group will be listed in the groups directory and in search results.', 'youplay' ); ?></li>
			<li><?php _e( 'Group content and activity will be visible to any site member.', 'youplay' ); ?></li>
		</ul>

		<div class="youplay-radio ml-5">
			<input type="radio" name="group-status" id="group-status-private" value="private"<?php if ( 'private' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> aria-describedby="private-group-description" />
			<label for="group-status-private"><?php _e( 'This is a private group', 'youplay' ); ?></label>
		</div>

		<ul id="private-group-description">
			<li><?php _e( 'Only users who request membership and are accepted can join the group.', 'youplay' ); ?></li>
			<li><?php _e( 'This group will be listed in the groups directory and in search results.', 'youplay' ); ?></li>
			<li><?php _e( 'Group content and activity will only be visible to members of the group.', 'youplay' ); ?></li>
		</ul>

		<div class="youplay-radio ml-5">
			<input type="radio" name="group-status" id="group-status-hidden" value="hidden"<?php if ( 'hidden' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> aria-describedby="hidden-group-description" />
			<label for="group-status-hidden"><?php _e('This is a hidden group', 'youplay' ); ?></label>
		</div>

		<ul id="hidden-group-description">
			<li><?php _e( 'Only users who are invited can join the group.', 'youplay' ); ?></li>
			<li><?php _e( 'This group will not be listed in the groups directory or search results.', 'youplay' ); ?></li>
			<li><?php _e( 'Group content and activity will only be visible to members of the group.', 'youplay' ); ?></li>
		</ul>

	</div>


	<h3 class="mt-30 mb-20"><?php _e( 'Group Invitations', 'youplay' ); ?></h3>

	<p><?php _e( 'Which members of this group are allowed to invite others?', 'youplay' ); ?></p>

	<div class="radio">

		<div class="youplay-radio ml-5">
			<input type="radio" name="group-invite-status" id="group-invite-status-members" value="members"<?php bp_group_show_invite_status_setting( 'members' ); ?> />
			<label for="group-invite-status-members"><?php _e( 'All group members', 'youplay' ); ?></label>
		</div>

		<div class="youplay-radio ml-5">
			<input type="radio" name="group-invite-status" id="group-invite-status-mods" value="mods"<?php bp_group_show_invite_status_setting( 'mods' ); ?> />
			<label for="group-invite-status-mods"><?php _e( 'Group admins and mods only', 'youplay' ); ?></label>
		</div>

		<div class="youplay-radio ml-5">
			<input type="radio" name="group-invite-status" id="group-invite-status-admins" value="admins"<?php bp_group_show_invite_status_setting( 'admins' ); ?> />
			<label for="group-invite-status-admins"><?php _e( 'Group admins only', 'youplay' ); ?></label>
		</div>

 	</div>

	<?php

	/**
	 * Fires after the group settings admin display.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_settings_admin' ); ?>

    <span class="btn btn-default">
        <?php esc_html_e( 'Save Changes', 'youplay' ); ?>
        <input type="submit" value="<?php esc_attr_e( 'Save Changes', 'youplay' ); ?>" id="save" name="save" />
    </span>
	<?php wp_nonce_field( 'groups_edit_group_settings' ); ?>

<?php endif; ?>

<?php /* Group Avatar Settings */ ?>
<?php if ( bp_is_group_admin_screen( 'group-avatar' ) ) : ?>

	<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>

			<p><?php _e("Upload an image to use as a profile photo for this group. The image will be shown on the main group page, and in search results.", 'youplay' ); ?></p>

			<p>
				<label for="file" class="bp-screen-reader-text"><?php _e( 'Select an image', 'youplay' ); ?></label>
				<input type="file" name="file" id="file" />
				<input type="submit" name="upload" id="upload" value="<?php esc_attr_e( 'Upload Image', 'youplay' ); ?>" />
				<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
			</p>

			<?php if ( bp_get_group_has_avatar() ) : ?>

				<p><?php _e( "If you'd like to remove the existing group profile photo but not upload a new one, please use the delete group profile photo button.", 'youplay' ); ?></p>

				<?php bp_button( array( 'id' => 'delete_group_avatar', 'component' => 'groups', 'wrapper_id' => 'delete-group-avatar-button', 'link_class' => 'edit', 'link_href' => bp_get_group_avatar_delete_link(), 'link_title' => __( 'Delete Group Profile Photo', 'youplay' ), 'link_text' => __( 'Delete Group Profile Photo', 'youplay' ) ) ); ?>

			<?php endif; ?>

			<?php
			/**
			 * Load the Avatar UI templates
			 *
			 * @since  2.3.0
			 */
			bp_avatar_get_templates(); ?>

			<?php wp_nonce_field( 'bp_avatar_upload' ); ?>

	<?php endif; ?>

	<?php if ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>

		<h4><?php _e( 'Crop Profile Photo', 'youplay' ); ?></h4>

		<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php esc_attr_e( 'Profile photo to crop', 'youplay' ); ?>" />

		<div id="avatar-crop-pane">
			<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php esc_attr_e( 'Profile photo preview', 'youplay' ); ?>" />
		</div>

		<input type="submit" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php esc_attr_e( 'Crop Image', 'youplay' ); ?>" />

		<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
		<input type="hidden" id="x" name="x" />
		<input type="hidden" id="y" name="y" />
		<input type="hidden" id="w" name="w" />
		<input type="hidden" id="h" name="h" />

		<?php wp_nonce_field( 'bp_avatar_cropstore' ); ?>

	<?php endif; ?>

<?php endif; ?>

<?php /* Group Cover image Settings */ ?>
<?php if ( bp_is_group_admin_screen( 'group-cover-image' ) ) : ?>

	<h4><?php _e( 'Change Cover Image', 'youplay' ); ?></h4>

	<?php

	/**
	 * Fires before the display of profile cover image upload content.
	 *
	 * @since 2.4.0
	 */
	do_action( 'bp_before_group_settings_cover_image' ); ?>

	<p><?php _e( 'The Cover Image will be used to customize the header of your group.', 'youplay' ); ?></p>

	<?php bp_attachments_get_template_part( 'cover-images/index' ); ?>

	<?php

	/**
	 * Fires after the display of group cover image upload content.
	 *
	 * @since 2.4.0
	 */
	do_action( 'bp_after_group_settings_cover_image' ); ?>

<?php endif; ?>

<?php /* Manage Group Members */ ?>
<?php if ( bp_is_group_admin_screen( 'manage-members' ) ) : ?>

	<?php

	/**
	 * Fires before the group manage members admin display.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_manage_members_admin' ); ?>

	<div class="bp-widget">
		<h3 class="mt-30 mb-20"><?php _e( 'Administrators', 'youplay' ); ?></h3>

		<?php if ( bp_has_members( '&include='. bp_group_admin_ids() ) ) : ?>

		<table id="admins-list" class="item-list single-line table table-hover">

			<?php while ( bp_members() ) : bp_the_member(); ?>
			<tr>
				<td class="p-15">
					<?php echo bp_core_fetch_avatar( array( 'item_id' => bp_get_member_user_id(), 'type' => 'thumb', 'width' => 30, 'height' => 30, 'alt' => sprintf( __( 'Profile picture of %s', 'youplay' ), bp_get_member_name() ) ) ); ?>
					<a class="ml-10" href="<?php bp_member_permalink(); ?>"> <?php bp_member_name(); ?></a>
				</td>
				<td class="p-15 text-right text-mute">
					<?php if ( count( bp_group_admin_ids( false, 'array' ) ) > 1 ) : ?>
					<small>
						<a class="button confirm admin-demote-to-member" href="<?php bp_group_member_demote_link( bp_get_member_user_id() ); ?>"><?php _e( 'Demote to Member', 'youplay' ); ?></a>
					</small>
					<?php endif; ?>
				</td>
			</tr>
			<?php endwhile; ?>

		</table>

		<?php endif; ?>

	</div>

	<?php if ( bp_group_has_moderators() ) : ?>
		<div class="bp-widget">
			<h3 class="mt-30 mb-20"><?php _e( 'Moderators', 'youplay' ); ?></h3>

			<?php if ( bp_has_members( '&include=' . bp_group_mod_ids() ) ) : ?>

				<table id="mods-list" class="item-list single-line table table-hover">

					<?php while ( bp_members() ) : bp_the_member(); ?>
					<tr>
						<td class="p-15">
							<?php echo bp_core_fetch_avatar( array( 'item_id' => bp_get_member_user_id(), 'type' => 'thumb', 'width' => 30, 'height' => 30, 'alt' => sprintf( __( 'Profile picture of %s', 'youplay' ), bp_get_member_name() ) ) ); ?>
							<a class="ml-10" href="<?php bp_member_permalink(); ?>"> <?php bp_member_name(); ?></a>
						</td>
						<td class="p-15 text-right text-mute">
							<small>
								<a href="<?php bp_group_member_promote_admin_link( array( 'user_id' => bp_get_member_user_id() ) ); ?>" class="button confirm mod-promote-to-admin" title="<?php esc_attr_e( 'Promote to Admin', 'youplay' ); ?>"><?php _e( 'Promote to Admin', 'youplay' ); ?></a>
								<a class="button confirm mod-demote-to-member" href="<?php bp_group_member_demote_link( bp_get_member_user_id() ); ?>"><?php _e( 'Demote to Member', 'youplay' ); ?></a>
							</small>
						</td>
					</tr>
					<?php endwhile; ?>

				</table>

			<?php endif; ?>
		</div>
	<?php endif ?>


	<div class="bp-widget">
		<h3 class="mt-30 mb-20"><?php _e("Members", 'youplay'); ?></h3>

		<?php if ( bp_group_has_members( 'per_page=15&exclude_banned=0' ) ) : ?>

			<?php if ( bp_group_member_needs_pagination() ) : ?>

				<div class="pagination no-ajax text-mute">

					<div id="member-count" class="pag-count">
						<?php bp_group_member_pagination_count(); ?>
					</div>

					<div id="member-admin-pagination" class="pagination-links">
						<?php bp_group_member_admin_pagination(); ?>
					</div>

				</div>

			<?php endif; ?>

			<table id="members-list" class="item-list single-line table table-hover">

				<?php while ( bp_group_members() ) : bp_group_the_member(); ?>
				<tr class="<?php bp_group_member_css_class(); ?>">
					<td class="p-15">
						<?php bp_group_member_avatar_mini(); ?>
						<span class="ml-10"></span>
						<?php bp_group_member_link(); ?>
						<?php if ( bp_get_group_member_is_banned() ) _e( '(banned)', 'youplay' ); ?>
					</td>
					<td class="p-15 text-right text-mute">
						<small>
							<?php if ( bp_get_group_member_is_banned() ) : ?>

								<a href="<?php bp_group_member_unban_link(); ?>" class="button confirm member-unban" title="<?php esc_attr_e( 'Unban this member', 'youplay' ); ?>"><?php _e( 'Remove Ban', 'youplay' ); ?></a>

							<?php else : ?>

								<a href="<?php bp_group_member_ban_link(); ?>" class="button confirm member-ban" title="<?php esc_attr_e( 'Kick and ban this member', 'youplay' ); ?>"><?php _e( 'Kick &amp; Ban', 'youplay' ); ?></a>
								<a href="<?php bp_group_member_promote_mod_link(); ?>" class="button confirm member-promote-to-mod" title="<?php esc_attr_e( 'Promote to Mod', 'youplay' ); ?>"><?php _e( 'Promote to Mod', 'youplay' ); ?></a>
								<a href="<?php bp_group_member_promote_admin_link(); ?>" class="button confirm member-promote-to-admin" title="<?php esc_attr_e( 'Promote to Admin', 'youplay' ); ?>"><?php _e( 'Promote to Admin', 'youplay' ); ?></a>

							<?php endif; ?>

								<a href="<?php bp_group_member_remove_link(); ?>" class="button confirm" title="<?php esc_attr_e( 'Remove this member', 'youplay' ); ?>"><?php _e( 'Remove from group', 'youplay' ); ?></a>

								<?php

								/**
								 * Fires inside the display of a member admin item in group management area.
								 *
								 * @since 1.1.0
								 */
								do_action( 'bp_group_manage_members_admin_item' ); ?>
						</small>
					</td>
				</tr>
				<?php endwhile; ?>

			</table>

		<?php else: ?>

			<div id="message" class="info">
				<p><?php _e( 'This group has no members.', 'youplay' ); ?></p>
			</div>

		<?php endif; ?>

	</div>

	<?php

	/**
	 * Fires after the group manage members admin display.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_manage_members_admin' ); ?>

<?php endif; ?>

<?php /* Manage Membership Requests */ ?>
<?php if ( bp_is_group_admin_screen( 'membership-requests' ) ) : ?>

	<?php

	/**
	 * Fires before the display of group membership requests admin.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_membership_requests_admin' ); ?>

		<div class="requests">

			<?php bp_get_template_part( 'groups/single/requests-loop' ); ?>

		</div>

	<?php

	/**
	 * Fires after the display of group membership requests admin.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_membership_requests_admin' ); ?>

<?php endif; ?>

<?php

/**
 * Fires inside the group admin template.
 *
 * Allows plugins to add custom group edit screens.
 *
 * @since 1.1.0
 */
do_action( 'groups_custom_edit_steps' ); ?>

<?php /* Delete Group Option */ ?>
<?php if ( bp_is_group_admin_screen( 'delete-group' ) ) : ?>

	<?php

	/**
	 * Fires before the display of group delete admin.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_delete_admin' ); ?>

	<div id="message" class="info">
		<p><?php _e( 'WARNING: Deleting this group will completely remove ALL content associated with it. There is no way back, please be careful with this option.', 'youplay' ); ?></p>
	</div>

	<div class="youplay-checkbox ml-10">
		<input type="checkbox" name="delete-group-understand" id="delete-group-understand" value="1" onclick="if(this.checked) { document.getElementById('delete-group-button').disabled = ''; } else { document.getElementById('delete-group-button').disabled = 'disabled'; }" />
		<label for="delete-group-understand"><?php _e( 'I understand the consequences of deleting this group.', 'youplay' ); ?></label>
	</div>

	<?php

	/**
	 * Fires after the display of group delete admin.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_delete_admin' ); ?>

	<div class="submit">
        <span class="btn btn-default">
            <?php esc_html_e( 'Save Changes', 'youplay' ); ?>
            <input type="submit" value="<?php esc_attr_e( 'Save Changes', 'youplay' ); ?>" id="delete-group-button" name="delete-group-button" />
        </span>
	</div>

	<?php wp_nonce_field( 'groups_delete_group' ); ?>

<?php endif; ?>

<?php /* This is important, don't forget it */ ?>
	<input type="hidden" name="group-id" id="group-id" value="<?php bp_group_id(); ?>" />

<?php

/**
 * Fires inside the group admin form and after the content.
 *
 * @since 1.1.0
 */
do_action( 'bp_after_group_admin_content' ); ?>

</form><!-- #group-settings-form -->

