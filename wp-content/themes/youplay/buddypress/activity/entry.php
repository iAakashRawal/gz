<?php
/**
 * BuddyPress - Activity Stream (Single Item)
 *
 * This template is used by activity-loop.php and AJAX functions to show
 * each activity.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires before the display of an activity entry.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_activity_entry' ); ?>

<li class="<?php bp_activity_css_class(); ?> youplay-timeline-block" id="activity-<?php bp_activity_id(); ?>">
	<div class="youplay-timeline-icon bg-primary">
		<a href="<?php bp_activity_user_link(); ?>">
			<?php bp_activity_avatar('type=thumb'); ?>
		</a>
	</div>

	<div class="youplay-timeline-content">

		<h3 class="activity-header">

			<?php
				$activity_action = bp_get_activity_action();
				$activity_action = str_replace('activity-time-since', 'youplay-timeline-date pt-5', $activity_action);
				echo wp_kses_post( $activity_action );
			?>

		</h3>

		<div class="clearfix"></div>

		<?php if ( bp_activity_has_content() ) : ?>

			<div class="activity-inner">

				<?php bp_activity_content_body(); ?>

			</div>

		<?php endif; ?>

		<?php

		/**
		 * Fires after the display of an activity entry content.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_activity_entry_content' ); ?>

		<div class="mt-20">

			<?php if ( bp_get_activity_type() == 'activity_comment' ) : ?>

				<a href="<?php bp_activity_thread_permalink(); ?>" class="btn btn-default" title="<?php esc_attr_e( 'View Conversation', 'youplay' ); ?>"><?php _e( 'View Conversation', 'youplay' ); ?></a>

			<?php endif; ?>

			<?php if ( is_user_logged_in() ) : ?>

				<?php if ( bp_activity_can_comment() ) : ?>

					<a href="<?php bp_activity_comment_link(); ?>" class="btn btn-sm btn-default acomment-reply" id="acomment-comment-<?php bp_activity_id(); ?>"><?php printf( __( 'Comment %s', 'youplay' ), '<span class="badge mnb-1">' . bp_activity_get_comment_count() . '</span>' ); ?></a>

				<?php endif; ?>

				<?php if ( bp_activity_can_favorite() ) : ?>

					<?php if ( !bp_get_activity_is_favorite() ) : ?>

						<a href="<?php bp_activity_favorite_link(); ?>" class="btn btn-sm btn-default" title="<?php esc_attr_e( 'Mark as Favorite', 'youplay' ); ?>"><?php _e( 'Favorite', 'youplay' ); ?></a>

					<?php else : ?>

						<a href="<?php bp_activity_unfavorite_link(); ?>" class="btn btn-sm btn-default" title="<?php esc_attr_e( 'Remove Favorite', 'youplay' ); ?>"><?php _e( 'Remove Favorite', 'youplay' ); ?></a>

					<?php endif; ?>

				<?php endif; ?>

				<?php if ( bp_activity_user_can_delete() ) bp_activity_delete_link(); ?>

				<?php

				/**
				 * Fires at the end of the activity entry meta data area.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_activity_entry_meta' ); ?>

			<?php endif; ?>

		</div>

	</div>

	<?php

	/**
	 * Fires before the display of the activity entry comments.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_activity_entry_comments' ); ?>

	<?php if ( ( bp_activity_get_comment_count() || bp_activity_can_comment() ) || bp_is_single_activity() ) : ?>

		<div class="activity-comments comments-block">

			<div class="comments-list">
				<?php bp_activity_comments(); ?>
			</div>

			<?php if ( is_user_logged_in() && bp_activity_can_comment() ) : ?>
					<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form comment-form comments-block"<?php bp_activity_comment_form_nojs_display(); ?>>
						<div class="comment-cont clearfix ac-reply-content">
							<div class="ac-textarea youplay-textarea">
								<textarea id="ac-input-<?php bp_activity_id(); ?>" class="ac-input bp-suggestions" name="ac_input_<?php bp_activity_id(); ?>" placeholder="<?php _e( 'Comment', 'youplay' ); ?>" rows="5"></textarea>
							</div>

                            <span class="btn btn-default btn-sm">
                                <?php esc_attr_e( 'Post', 'youplay' ); ?>
                                <input type="submit" name="ac_form_submit" value="<?php esc_attr_e( 'Post', 'youplay' ); ?>" />
                            </span>
							<a href="#" class="ac-reply-cancel"><?php _e( 'Cancel', 'youplay' ); ?></a>
							<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />

							<?php

							/**
							 * Fires after the activity entry comment form.
							 *
							 * @since 1.5.0
							 */
							do_action( 'bp_activity_entry_comments' ); ?>

							<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>
						</div>
					</form>
			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php

	/**
	 * Fires after the display of the activity entry comments.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_activity_entry_comments' ); ?>

</li>

<?php

/**
 * Fires after the display of an activity entry.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_activity_entry' ); ?>
