<?php

/**
 * New/Edit Topic
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php if ( !bbp_is_single_forum() ) : ?>

<div>

	<?php yp_bb_breadcrumb( false ); ?>

<?php endif; ?>

<?php if ( bbp_is_topic_edit() ) : ?>

	<?php bbp_topic_tag_list( bbp_get_topic_id(), array(
			'before' => '<div class="bbp-topic-tags mt-10 mb-20 pull-right">' . __( 'Tagged:', 'youplay' ) . '&nbsp;',
			'sep'    => ', ',
			'after'  => '</div>'
		) ); ?>

	<div class="clearfix"></div>

	<?php bbp_single_topic_description( array(
					'topic_id' => bbp_get_topic_id(),
					'before'    => '<div class="alert">',
					'after'     => '</div>',
				) ); ?>

<?php endif; ?>

<div class="clearfix"></div>

<?php if ( bbp_current_user_can_access_create_topic_form() ) : ?>

	<div id="new-topic-<?php bbp_topic_id(); ?>" class="bbp-topic-form">

		<form id="new-post" name="new-post" method="post" action="<?php the_permalink(); ?>">

			<?php do_action( 'bbp_theme_before_topic_form' ); ?>

			<div class="h2">

				<?php
					if ( bbp_is_topic_edit() )
						printf( __( 'Now Editing &ldquo;%s&rdquo;', 'youplay' ), bbp_get_topic_title() );
					else
						bbp_is_single_forum() ? printf( __( 'Create New Topic in &ldquo;%s&rdquo;', 'youplay' ), bbp_get_forum_title() ) : _e( 'Create New Topic', 'youplay' );
				?>

			</div>

			<?php do_action( 'bbp_theme_before_topic_form_notices' ); ?>

			<?php if ( !bbp_is_topic_edit() && bbp_is_forum_closed() ) : ?>

				<div class="alert">
					<?php _e( 'This forum is marked as closed to new topics, however your posting capabilities still allow you to do so.', 'youplay' ); ?>
				</div>

			<?php endif; ?>

			<?php if ( current_user_can( 'unfiltered_html' ) ) : ?>

				<div class="alert">
					<?php _e( 'Your account has the ability to post unrestricted HTML content.', 'youplay' ); ?>
				</div>

			<?php endif; ?>

			<?php do_action( 'bbp_template_notices' ); ?>

			<div>

				<?php bbp_get_template_part( 'form', 'anonymous' ); ?>

				<?php do_action( 'bbp_theme_before_topic_form_title' ); ?>

				<p><?php printf( __( 'Topic Title (Maximum Length: %d):', 'youplay' ), bbp_get_title_max_length() ); ?></p>
				<div class="youplay-input">
					<input type="text" value="<?php bbp_form_topic_title(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_title" maxlength="<?php bbp_title_max_length(); ?>" placeholder="<?php _e('Topic Title', 'youplay'); ?>" />
				</div>

				<?php do_action( 'bbp_theme_after_topic_form_title' ); ?>

				<?php do_action( 'bbp_theme_before_topic_form_content' ); ?>

				<?php bbp_the_content( array( 'context' => 'topic' ) ); ?>

				<?php do_action( 'bbp_theme_after_topic_form_content' ); ?>

				<?php if ( ! ( bbp_use_wp_editor() || current_user_can( 'unfiltered_html' ) ) ) : ?>

					<p class="form-allowed-tags">
						<label><?php _e( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes:','youplay' ); ?></label><br />
						<code><?php bbp_allowed_tags(); ?></code>
					</p>

				<?php endif; ?>

				<?php if ( bbp_allow_topic_tags() && current_user_can( 'assign_topic_tags' ) ) : ?>

					<?php do_action( 'bbp_theme_before_topic_form_tags' ); ?>

					<p><?php _e( 'Topic Tags:', 'youplay' ); ?></p>
					<div class="youplay-input">
						<input type="text" value="<?php bbp_form_topic_tags(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_tags" id="bbp_topic_tags" <?php disabled( bbp_is_topic_spam() ); ?> placeholder="<?php _e( 'Tags', 'youplay' ); ?>" />
					</div>

					<?php do_action( 'bbp_theme_after_topic_form_tags' ); ?>

				<?php endif; ?>

				<?php if ( !bbp_is_single_forum() ) : ?>

					<?php do_action( 'bbp_theme_before_topic_form_forum' ); ?>

					<p><?php _e( 'Forum:', 'youplay' ); ?></p>
					<label class="youplay-select">
						<?php
							bbp_dropdown( array(
								'show_none' => __( '(No Forum)', 'youplay' ),
								'selected'  => bbp_get_form_topic_forum()
							) );
						?>
					</label>

					<?php do_action( 'bbp_theme_after_topic_form_forum' ); ?>

				<?php endif; ?>

				<?php if ( current_user_can( 'moderate' ) ) : ?>

					<?php do_action( 'bbp_theme_before_topic_form_type' ); ?>

					<p><?php _e( 'Topic Type:', 'youplay' ); ?></p>

					<label class="youplay-select">
						<?php bbp_form_topic_type_dropdown(); ?>
					</label>

					<?php do_action( 'bbp_theme_after_topic_form_type' ); ?>

					<?php do_action( 'bbp_theme_before_topic_form_status' ); ?>

						<p><?php _e( 'Topic Status:', 'youplay' ); ?></p>

						<div class="youplay-select">
							<?php bbp_form_topic_status_dropdown(); ?>
						</div>

					<?php do_action( 'bbp_theme_after_topic_form_status' ); ?>

				<?php endif; ?>

				<?php if ( bbp_is_subscriptions_active() && !bbp_is_anonymous() && ( !bbp_is_topic_edit() || ( bbp_is_topic_edit() && !bbp_is_topic_anonymous() ) ) ) : ?>

					<?php do_action( 'bbp_theme_before_topic_form_subscriptions' ); ?>

					<div class="youplay-checkbox mt-20">
						<input name="bbp_topic_subscription" id="bbp_topic_subscription" type="checkbox" value="bbp_subscribe" <?php bbp_form_topic_subscribed(); ?> tabindex="<?php bbp_tab_index(); ?>" />
						<?php if ( bbp_is_topic_edit() && ( bbp_get_topic_author_id() !== bbp_get_current_user_id() ) ) : ?>

							<label for="bbp_topic_subscription"><?php _e( 'Notify the author of follow-up replies via email', 'youplay' ); ?></label>

						<?php else : ?>

							<label for="bbp_topic_subscription"><?php _e( 'Notify me of follow-up replies via email', 'youplay' ); ?></label>

						<?php endif; ?>
					</div>

					<?php do_action( 'bbp_theme_after_topic_form_subscriptions' ); ?>

				<?php endif; ?>

				<?php if ( bbp_allow_revisions() && bbp_is_topic_edit() ) : ?>

					<?php do_action( 'bbp_theme_before_topic_form_revisions' ); ?>

					<div class="youplay-checkbox">
						<input name="bbp_log_topic_edit" id="bbp_log_topic_edit" type="checkbox" value="1" <?php bbp_form_topic_log_edit(); ?> tabindex="<?php bbp_tab_index(); ?>" />
						<label for="bbp_log_topic_edit" class="h3"><?php _e( 'Keep a log of this edit:', 'youplay' ); ?></label><br />
					</div>

					<p><?php printf( __( 'Optional reason for editing:', 'youplay' ), bbp_get_current_user_name() ); ?></p>
					<div class="youplay-input">
						<input type="text" value="<?php bbp_form_topic_edit_reason(); ?>" tabindex="<?php bbp_tab_index(); ?>" size="40" name="bbp_topic_edit_reason" id="bbp_topic_edit_reason" placeholder="<?php _e( 'Reason', 'youplay' ); ?>" />
					</div>

					<?php do_action( 'bbp_theme_after_topic_form_revisions' ); ?>

				<?php endif; ?>

				<?php do_action( 'bbp_theme_before_topic_form_submit_wrapper' ); ?>

					<?php do_action( 'bbp_theme_before_topic_form_submit_button' ); ?>

					<button type="submit" tabindex="<?php bbp_tab_index(); ?>" id="bbp_topic_submit" name="bbp_topic_submit" class="btn btn-default btn-lg pull-right"><?php _e( 'Submit', 'youplay' ); ?></button>

					<div class="clearfix"></div>

					<?php do_action( 'bbp_theme_after_topic_form_submit_button' ); ?>

				<?php do_action( 'bbp_theme_after_topic_form_submit_wrapper' ); ?>

			</div>

			<?php bbp_topic_form_fields(); ?>

			<?php do_action( 'bbp_theme_after_topic_form' ); ?>

		</form>
	</div>

<?php elseif ( bbp_is_forum_closed() ) : ?>

	<div id="no-topic-<?php bbp_topic_id(); ?>" class="bbp-no-topic">
		<div class="alert">
			<?php printf( __( 'The forum &#8216;%s&#8217; is closed to new topics and replies.', 'youplay' ), bbp_get_forum_title() ); ?>
		</div>
	</div>

<?php else : ?>

	<div class="clearfix"></div>
	<div id="no-topic-<?php bbp_topic_id(); ?>" class="bbp-no-topic">
		<div class="alert">
			<?php is_user_logged_in() ? _e( 'You cannot create new topics.', 'youplay' ) : _e( 'You must be logged in to create new topics.', 'youplay' ); ?>
		</div>
	</div>

<?php endif; ?>

<?php if ( !bbp_is_single_forum() ) : ?>

</div>

<?php endif; ?>
