<?php
/**
 * BuddyPress - Groups Create
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

get_template_part( 'buddypress/start' );

/**
 * Fires at the top of the groups creation template file.
 *
 * @since 1.7.0
 */
do_action( 'bp_before_create_group_page' ); ?>

	<?php

	/**
	 * Fires before the display of group creation content.
	 *
	 * @since 1.6.0
	 */
	do_action( 'bp_before_create_group_content_template' ); ?>

	<form action="<?php bp_group_creation_form_action(); ?>" method="post" id="create-group-form" enctype="multipart/form-data">

		<?php

		/**
		 * Fires before the display of group creation.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_create_group' ); ?>

		<div class="item-list-tabs no-ajax" id="group-create-tabs" role="navigation">
			<ul class="pagination no-ajax pagination-sm">

				<?php youplay_bp_group_creation_tabs(); ?>

			</ul>
		</div>

		<?php

		/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
		do_action( 'template_notices' ); ?>

		<div class="item-body" id="group-create-body">

			<?php /* Group creation step 1: Basic group details */ ?>
			<?php if ( bp_is_group_creation_step( 'group-details' ) ) : ?>

				<?php

				/**
				 * Fires before the display of the group details creation step.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_before_group_details_creation_step' ); ?>

				<div>
					<label for="group-name"><?php _e( 'Group Name (required)', 'youplay' ); ?></label>
					<div class="youplay-input">
						<input type="text" name="group-name" id="group-name" aria-required="true" value="<?php bp_new_group_name(); ?>" />
					</div>
				</div>

				<div>
					<label for="group-desc"><?php _e( 'Group Description (required)', 'youplay' ); ?></label>
					<div class="youplay-textarea">
						<textarea name="group-desc" id="group-desc" aria-required="true" rows="5"><?php bp_new_group_description(); ?></textarea>
					</div>
				</div>

				<?php

				/**
				 * Fires after the display of the group details creation step.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_after_group_details_creation_step' );
				do_action( 'groups_custom_group_fields_editable' ); // @Deprecated

				wp_nonce_field( 'groups_create_save_group-details' ); ?>

			<?php endif; ?>

			<?php /* Group creation step 2: Group settings */ ?>
			<?php if ( bp_is_group_creation_step( 'group-settings' ) ) : ?>

				<?php

				/**
				 * Fires before the display of the group settings creation step.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_before_group_settings_creation_step' ); ?>

				<h3 class="mt-30 mb-20"><?php _e( 'Privacy Options', 'youplay' ); ?></h3>

				<div class="radio">

					<div class="youplay-radio ml-10">
						<input type="radio" name="group-status" id="group-status-public" value="public"<?php if ( 'public' == bp_get_new_group_status() || !bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> aria-describedby="public-group-description" />
						<label for="group-status-public"><?php _e( 'This is a public group', 'youplay' ); ?></label>
					</div>

					<ul id="public-group-description">
						<li><?php _e( 'Any site member can join this group.', 'youplay' ); ?></li>
						<li><?php _e( 'This group will be listed in the groups directory and in search results.', 'youplay' ); ?></li>
						<li><?php _e( 'Group content and activity will be visible to any site member.', 'youplay' ); ?></li>
					</ul>


					<div class="youplay-radio ml-10">
						<input type="radio" name="group-status" id="group-status-private" value="private"<?php if ( 'private' == bp_get_new_group_status() ) { ?> checked="checked"<?php } ?> aria-describedby="private-group-description" />
						<label for="group-status-private"><?php _e( 'This is a private group', 'youplay' ); ?></label>
					</div>

					<ul id="private-group-description">
						<li><?php _e( 'Only users who request membership and are accepted can join the group.', 'youplay' ); ?></li>
						<li><?php _e( 'This group will be listed in the groups directory and in search results.', 'youplay' ); ?></li>
						<li><?php _e( 'Group content and activity will only be visible to members of the group.', 'youplay' ); ?></li>
					</ul>


					<div class="youplay-radio ml-10">
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

					<div class="youplay-radio ml-10">
						<input type="radio" name="group-invite-status" id="group-invite-status-members" value="members"<?php bp_group_show_invite_status_setting( 'members' ); ?> />
						<label for="group-invite-status-members"><?php _e( 'All group members', 'youplay' ); ?></label>
					</div>

					<div class="youplay-radio ml-10">
						<input type="radio" name="group-invite-status" id="group-invite-status-mods" value="mods"<?php bp_group_show_invite_status_setting( 'mods' ); ?> />
						<label for="group-invite-status-mods"><?php _e( 'Group admins and mods only', 'youplay' ); ?></label>
					</div>

					<div class="youplay-radio ml-10">
						<input type="radio" name="group-invite-status" id="group-invite-status-admins" value="admins"<?php bp_group_show_invite_status_setting( 'admins' ); ?> />
						<label for="group-invite-status-admins"><?php _e( 'Group admins only', 'youplay' ); ?></label>
					</div>

				</div>

				<?php if ( bp_is_active( 'forums' ) ) : ?>

					<h3 class="mt-30 mb-20"><?php _e( 'Group Forums', 'youplay' ); ?></h3>

					<?php if ( bp_forums_is_installed_correctly() ) : ?>

						<p><?php _e( 'Should this group have a forum?', 'youplay' ); ?></p>

						<div class="youplay-checkbox ml-10">
							<input type="checkbox" name="group-show-forum" id="group-show-forum" value="1"<?php checked( bp_get_new_group_enable_forum(), true, true ); ?> />
							<label for="group-show-forum"><?php _e( 'Enable discussion forum', 'youplay' ); ?></label>
						</div>
					<?php elseif ( is_super_admin() ) : ?>

						<p><?php printf( __( '<strong>Attention Site Admin:</strong> Group forums require the <a href="%s">correct setup and configuration</a> of a bbPress installation.', 'youplay' ), bp_core_do_network_admin() ? network_admin_url( 'settings.php?page=bb-forums-setup' ) :  admin_url( 'admin.php?page=bb-forums-setup' ) ); ?></p>

					<?php endif; ?>

				<?php endif; ?>

				<?php

				/**
				 * Fires after the display of the group settings creation step.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_after_group_settings_creation_step' ); ?>

				<?php wp_nonce_field( 'groups_create_save_group-settings' ); ?>

			<?php endif; ?>

			<?php /* Group creation step 3: Avatar Uploads */ ?>
			<?php if ( bp_is_group_creation_step( 'group-avatar' ) ) : ?>

				<?php

				/**
				 * Fires before the display of the group avatar creation step.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_before_group_avatar_creation_step' ); ?>

				<?php if ( 'upload-image' == bp_get_avatar_admin_step() ) : ?>

					<div class="left-menu">

						<?php bp_new_group_avatar(); ?>

					</div><!-- .left-menu -->

					<div class="main-column">
						<p><?php _e( "Upload an image to use as a profile photo for this group. The image will be shown on the main group page, and in search results.", 'youplay' ); ?></p>

						<p>
							<label for="file" class="bp-screen-reader-text"><?php _e( 'Select an image', 'youplay' ); ?></label>
							<input type="file" name="file" id="file" />
							<input type="submit" name="upload" id="upload" value="<?php esc_attr_e( 'Upload Image', 'youplay' ); ?>" />
							<input type="hidden" name="action" id="action" value="bp_avatar_upload" />
						</p>

						<p><?php _e( 'To skip the group profile photo upload process, hit the "Next Step" button.', 'youplay' ); ?></p>
					</div><!-- .main-column -->

					<?php
					/**
					 * Load the Avatar UI templates
					 *
					 * @since 2.3.0
					 */
					bp_avatar_get_templates(); ?>

				<?php endif; ?>

				<?php if ( 'crop-image' == bp_get_avatar_admin_step() ) : ?>

					<h4><?php _e( 'Crop Group Profile Photo', 'youplay' ); ?></h4>

					<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-to-crop" class="avatar" alt="<?php esc_attr_e( 'Profile photo to crop', 'youplay' ); ?>" />

					<div id="avatar-crop-pane">
						<img src="<?php bp_avatar_to_crop(); ?>" id="avatar-crop-preview" class="avatar" alt="<?php esc_attr_e( 'Profile photo preview', 'youplay' ); ?>" />
					</div>

					<input type="submit" name="avatar-crop-submit" id="avatar-crop-submit" value="<?php esc_attr_e( 'Crop Image', 'youplay' ); ?>" />

					<input type="hidden" name="image_src" id="image_src" value="<?php bp_avatar_to_crop_src(); ?>" />
					<input type="hidden" name="upload" id="upload" />
					<input type="hidden" id="x" name="x" />
					<input type="hidden" id="y" name="y" />
					<input type="hidden" id="w" name="w" />
					<input type="hidden" id="h" name="h" />

				<?php endif; ?>

				<?php

				/**
				 * Fires after the display of the group avatar creation step.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_after_group_avatar_creation_step' ); ?>

				<?php wp_nonce_field( 'groups_create_save_group-avatar' ); ?>

			<?php endif; ?>

			<?php /* Group creation step 4: Cover image */ ?>
			<?php if ( bp_is_group_creation_step( 'group-cover-image' ) ) : ?>

				<?php

				/**
				 * Fires before the display of the group cover image creation step.
				 *
				 * @since 2.4.0
				 */
				do_action( 'bp_before_group_cover_image_creation_step' ); ?>

				<div id="header-cover-image"></div>

				<p><?php _e( 'The Cover Image will be used to customize the header of your group.', 'youplay' ); ?></p>

				<?php bp_attachments_get_template_part( 'cover-images/index' ); ?>

				<?php

				/**
				 * Fires after the display of the group cover image creation step.
				 *
				 * @since 2.4.0
				 */
				do_action( 'bp_after_group_cover_image_creation_step' ); ?>

				<?php wp_nonce_field( 'groups_create_save_group-cover-image' ); ?>

			<?php endif; ?>

			<?php /* Group creation step 5: Invite friends to group */ ?>
			<?php if ( bp_is_group_creation_step( 'group-invites' ) ) : ?>

				<?php

				/**
				 * Fires before the display of the group invites creation step.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_before_group_invites_creation_step' ); ?>

				<?php if ( bp_is_active( 'friends' ) && bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

					<div class="left-menu">

						<div id="invite-list">
							<ul>
								<?php bp_new_group_invite_friend_list(); ?>
							</ul>

							<?php wp_nonce_field( 'groups_invite_uninvite_user', '_wpnonce_invite_uninvite_user' ); ?>
						</div>

					</div><!-- .left-menu -->

					<div class="main-column">

						<div id="message" class="info">
							<p><?php _e('Select people to invite from your friends list.', 'youplay' ); ?></p>
						</div>

						<?php /* The ID 'friend-list' is important for AJAX support. */ ?>
						<ul id="friend-list" class="item-list">

						<?php if ( bp_group_has_invites() ) : ?>

							<?php while ( bp_group_invites() ) : bp_group_the_invite(); ?>

								<li id="<?php bp_group_invite_item_id(); ?>">

									<?php bp_group_invite_user_avatar(); ?>

									<h4><?php bp_group_invite_user_link(); ?></h4>
									<span class="activity"><?php bp_group_invite_user_last_active(); ?></span>

									<div class="action">
										<a class="remove" href="<?php bp_group_invite_user_remove_invite_url(); ?>" id="<?php bp_group_invite_item_id(); ?>"><?php _e( 'Remove Invite', 'youplay' ); ?></a>
									</div>
								</li>

							<?php endwhile; ?>

							<?php wp_nonce_field( 'groups_send_invites', '_wpnonce_send_invites' ); ?>

						<?php endif; ?>

						</ul>

					</div><!-- .main-column -->

				<?php else : ?>

					<div id="message" class="info">
						<p><?php _e( 'Once you have built up friend connections you will be able to invite others to your group.', 'youplay' ); ?></p>
					</div>

				<?php endif; ?>

				<?php wp_nonce_field( 'groups_create_save_group-invites' ); ?>

				<?php

				/**
				 * Fires after the display of the group invites creation step.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_after_group_invites_creation_step' ); ?>

			<?php endif; ?>

			<?php

			/**
			 * Fires inside the group admin template.
			 *
			 * Allows plugins to add custom group creation steps.
			 *
			 * @since 1.1.0
			 */
			do_action( 'groups_custom_create_steps' ); ?>

			<?php

			/**
			 * Fires before the display of the group creation step buttons.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_before_group_creation_step_buttons' ); ?>

			<?php if ( 'crop-image' != bp_get_avatar_admin_step() ) : ?>

				<div class="submit" id="previous-next">

					<?php /* Previous Button */ ?>
					<?php if ( !bp_is_first_group_creation_step() ) : ?>

                        <span class="btn btn-default">
                            <?php esc_html_e( 'Back to Previous Step', 'youplay' ); ?>
                            <input type="button" value="<?php esc_attr_e( 'Back to Previous Step', 'youplay' ); ?>" id="group-creation-previous" name="previous" onclick="location.href='<?php bp_group_creation_previous_link(); ?>'" />
                        </span>

					<?php endif; ?>

					<?php /* Next Button */ ?>
					<?php if ( !bp_is_last_group_creation_step() && !bp_is_first_group_creation_step() ) : ?>

                        <span class="btn btn-default">
                            <?php esc_html_e( 'Next Step', 'youplay' ); ?>
                            <input type="submit" value="<?php esc_attr_e( 'Next Step', 'youplay' ); ?>" id="group-creation-next" name="save" />
                        </span>

					<?php endif;?>

					<?php /* Create Button */ ?>
					<?php if ( bp_is_first_group_creation_step() ) : ?>

                        <span class="btn btn-default">
                            <?php esc_html_e( 'Create Group and Continue', 'youplay' ); ?>
                            <input type="submit" value="<?php esc_attr_e( 'Create Group and Continue', 'youplay' ); ?>" id="group-creation-create" name="save" />
                        </span>

					<?php endif; ?>

					<?php /* Finish Button */ ?>
					<?php if ( bp_is_last_group_creation_step() ) : ?>

                        <span class="btn btn-default">
                            <?php esc_html_e( 'Finish', 'youplay' ); ?>
                            <input type="submit" value="<?php esc_attr_e( 'Finish', 'youplay' ); ?>" id="group-creation-finish" name="save" />
                        </span>

					<?php endif; ?>
				</div>

			<?php endif;?>

			<?php

			/**
			 * Fires after the display of the group creation step buttons.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_after_group_creation_step_buttons' ); ?>

			<?php /* Don't leave out this hidden field */ ?>
			<input type="hidden" name="group_id" id="group_id" value="<?php bp_new_group_id(); ?>" />

			<?php

			/**
			 * Fires and displays the groups directory content.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_directory_groups_content' ); ?>

		</div><!-- .item-body -->

		<?php

		/**
		 * Fires after the display of group creation.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_after_create_group' ); ?>

	</form>

	<?php

	/**
	 * Fires after the display of group creation content.
	 *
	 * @since 1.6.0
	 */
	do_action( 'bp_after_create_group_content_template' ); ?>

<?php

/**
 * Fires at the bottom of the groups creation template file.
 *
 * @since 1.7.0
 */
do_action( 'bp_after_create_group_page' );

get_template_part( 'buddypress/end' );
