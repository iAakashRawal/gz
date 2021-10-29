<?php
/**
 * BuddyPress - Members Single Message
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<div id="message-thread">

	<?php

	/**
	 * Fires before the display of a single member message thread content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_message_thread_content' ); ?>

	<?php if ( bp_thread_has_messages() ) : ?>

		<h3 id="message-subject"><?php bp_the_thread_subject(); ?></h3>

		<p id="message-recipients">
			<span class="highlight">

				<?php if ( bp_get_thread_recipients_count() <= 1 ) : ?>

					<?php _e( 'You are alone in this conversation.', 'youplay' ); ?>

				<?php elseif ( bp_get_max_thread_recipients_to_list() <= bp_get_thread_recipients_count() ) : ?>

					<?php printf( __( 'Conversation between %s recipients.', 'youplay' ), number_format_i18n( bp_get_thread_recipients_count() ) ); ?>

				<?php else : ?>

					<?php printf( __( 'Conversation between %s and you.', 'youplay' ), bp_get_thread_recipients_list() ); ?>

				<?php endif; ?>

			</span>

			<a class="button confirm" href="<?php bp_the_thread_delete_link(); ?>" title="<?php esc_attr_e( "Delete Conversation", 'youplay' ); ?>"><?php _e( 'Delete', 'youplay' ); ?></a>
		</p>

		<?php

		/**
		 * Fires before the display of the message thread list.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_message_thread_list' ); ?>

		<table class="item-list table table-hover">
		    <tbody>
				<?php while ( bp_thread_messages() ) : bp_thread_the_message(); ?>
					<?php bp_get_template_part( 'members/single/messages/message' ); ?>
				<?php endwhile; ?>
				<tr id="send-reply" style="background: none !important;">
				<td colspan="3">
					<?php

					/**
					 * Fires after the display of the message thread list.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_after_message_thread_list' ); ?>

					<?php

					/**
					 * Fires before the display of the message thread reply form.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_before_message_thread_reply' ); ?>

					<form action="<?php bp_messages_form_action(); ?>" class="comment-form comments-block" method="post">

						<?php

						/** This action is documented in bp-templates/bp-legacy/buddypress-functions.php */
						do_action( 'bp_before_message_meta' ); ?>

						<?php

						/** This action is documented in bp-templates/bp-legacy/buddypress-functions.php */
						do_action( 'bp_after_message_meta' ); ?>

						<div class="comment-cont clearfix">
							<?php

							/**
							 * Fires before the display of the message reply box.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_before_message_reply_box' ); ?>

							<label for="message_content" class="bp-screen-reader-text"><?php _e( 'Reply to Message', 'youplay' ); ?></label>
							<div class="youplay-textarea">
								<textarea name="content" id="message_content" rows="5" cols="40" placeholder="Reply"></textarea>
							</div>

							<?php

							/**
							 * Fires after the display of the message reply box.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_after_message_reply_box' ); ?>

							<div class="submit">
                                <span class="btn btn-default">
                                    <?php esc_html_e( 'Send Reply', 'youplay' ); ?>
                                    <input type="submit" value="<?php esc_attr_e( 'Send Reply', 'youplay' ); ?>" id="send_reply_button" name="send" />
                                </span>
							</div>

							<input type="hidden" id="thread_id" name="thread_id" value="<?php bp_the_thread_id(); ?>" />
							<input type="hidden" id="messages_order" name="messages_order" value="<?php bp_thread_messages_order(); ?>" />
							<?php wp_nonce_field( 'messages_send_message', 'send_message_nonce' ); ?>

						</div>

					</form><!-- #send-reply -->

					<?php

					/**
					 * Fires after the display of the message thread reply form.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_after_message_thread_reply' ); ?>
				</td></tr>
		    </tbody>
		</table>


	<?php endif; ?>

	<?php

	/**
	 * Fires after the display of a single member message thread content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_message_thread_content' ); ?>

</div>
