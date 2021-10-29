<?php
/**
 * BuddyPress - Private Message Content.
 *
 * This template is used in /messages/single.php during the message loop to
 * display each message and when a new message is created via AJAX.
 *
 * @since 2.4.0
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<tr class="<?php bp_the_thread_message_css_class(); ?>">

    <td class="item-avatar p-15" width="100">
        <a href="<?php bp_get_the_thread_message_sender_link() ? bp_the_thread_message_sender_link() : '#' ; ?>" class="angled-img">
            <div class="img">
				<?php bp_the_thread_message_sender_avatar( 'type=thumb&width=100&height=100' ); ?>
            </div>
        </a>

    </td>
    
    <td class="item p-15">
		<?php

		/**
		 * Fires before the single message header is displayed.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_message_meta' ); ?>

        <div class="item-title">
			<?php if ( bp_get_the_thread_message_sender_link() ) : ?>
				<a href="<?php bp_the_thread_message_sender_link(); ?>" title="<?php bp_the_thread_message_sender_name(); ?>">
			<?php endif; ?>

				<?php bp_the_thread_message_sender_name(); ?>

			<?php if ( bp_get_the_thread_message_sender_link() ) : ?>
				</a>
			<?php endif; ?>
		</div>
        
        <div class="item-meta"><span class="date"><?php bp_the_thread_message_time_since(); ?></span></div>

		<?php

		/**
		 * Fires after the single message header is displayed.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_message_meta' ); ?>

        <div class="item-desc">
			<?php

			/**
			 * Fires before the message content for a private message.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_before_message_content' ); ?>

			<?php bp_the_thread_message_content(); ?>

			<?php

			/**
			 * Fires after the message content for a private message.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_after_message_content' ); ?>
        </div>
    </td>

    <td class="p-15">
		<?php if ( bp_is_active( 'messages', 'star' ) ) : ?>
			<?php bp_the_message_star_action_link(); ?>
		<?php endif; ?>
    </td>
</tr>
