<?php
/**
 * BuddyPress - Members Single Messages Compose
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<form action="<?php bp_messages_form_action('compose' ); ?>" method="post" id="send_message_form" enctype="multipart/form-data">

	<?php

	/**
	 * Fires before the display of message compose content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_messages_compose_content' ); ?>

	<label for="send-to-input"><?php _e("Send To (Username or Friend's Name)", 'youplay' ); ?></label>
	<ul class="first acfb-holder">
		<?php bp_message_get_recipient_tabs(); ?>
		<li>
			<div class="youplay-input">
				<input type="text" name="send-to-input" class="send-to-input" id="send-to-input" />
			</div>
		</li>
	</ul>

	<?php if ( bp_current_user_can( 'bp_moderate' ) ) : ?>
		<div class="youplay-checkbox">
			<input id="send-notice" type="checkbox" name="send-notice" value="1">
			<label for="send-notice"><?php _e( "This is a notice to all users.", 'youplay' ); ?></label>
		</div>
	<?php endif; ?>

	<div>
		<label for="subject"><?php _e( 'Subject', 'youplay' ); ?></label>
		<div class="youplay-input">
			<input type="text" name="subject" id="subject" value="<?php bp_messages_subject_value(); ?>" />
		</div>
	</div>

	<div>
		<label for="message_content"><?php _e( 'Message', 'youplay' ); ?></label>
		<div class="youplay-textarea">
			<textarea name="content" id="message_content" rows="5" cols="40"><?php bp_messages_content_value(); ?></textarea>
		</div>
	</div>

	<input type="hidden" name="send_to_usernames" id="send-to-usernames" value="<?php bp_message_get_recipient_usernames(); ?>" class="<?php bp_message_get_recipient_usernames(); ?>" />

	<?php

	/**
	 * Fires after the display of message compose content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_messages_compose_content' ); ?>

	<div class="submit">
        <span class="btn btn-default">
            <?php esc_html_e( 'Send Message', 'youplay' ); ?>
            <input type="submit" value="<?php esc_attr_e( 'Send Message', 'youplay' ); ?>" id="send" name="send" />
        </span>
	</div>

	<?php wp_nonce_field( 'messages_send_message' ); ?>
</form>

<script type="text/javascript">
	document.getElementById("send-to-input").focus();
</script>

