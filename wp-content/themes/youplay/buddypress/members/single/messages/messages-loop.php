<?php
/**
 * BuddyPress - Members Messages Loop
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires before the members messages loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_messages_loop' ); ?>

<?php if ( bp_has_message_threads( bp_ajax_querystring( 'messages' ) ) ) : ?>

	<div class="pagination no-ajax text-mute" id="user-pag">

		<div class="pag-count" id="messages-dir-count">
			<?php bp_messages_pagination_count(); ?>
		</div>

		<div class="pagination-links" id="messages-dir-pag">
			<?php bp_messages_pagination(); ?>
		</div>

	</div><!-- .pagination -->

	<?php

	/**
	 * Fires after the members messages pagination display.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_messages_pagination' ); ?>

	<?php

	/**
	 * Fires before the members messages threads.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_member_messages_threads' ); ?>

	<form action="<?php echo bp_loggedin_user_domain() . bp_get_messages_slug() . '/' . bp_current_action() ?>/bulk-manage/" method="post" id="messages-bulk-management">

		<table id="message-threads" class="youplay-messages table table-hover">

			<thead>
				<tr>
					<th scope="col" class="thread-checkbox bulk-select-all">
						<div class="youplay-checkbox">
							<input id="select-all-messages" type="checkbox">
							<label for="select-all-messages" class="mnr-20 ml-15"><span class="sr-only"><?php _e( 'Select all', 'youplay' ); ?></span></label>
						</div>
					</th>
					<th scope="col"></th>
					<th scope="col"></th>

					<?php

					/**
					 * Fires inside the messages box table header to add a new column.
					 *
					 * This is to primarily add a <th> cell to the messages box table header. Use
					 * the related 'bp_messages_inbox_list_item' hook to add a <td> cell.
					 *
					 * @since 2.3.0
					 */
					do_action( 'bp_messages_inbox_list_header' ); ?>

					<?php if ( bp_is_active( 'messages', 'star' ) ) : ?>
						<th scope="col"></th>
					<?php endif; ?>

					<th scope="col"></th>
				</tr>
			</thead>

			<tbody>

				<?php while ( bp_message_threads() ) : bp_message_thread(); ?>

					<tr id="m-<?php bp_message_thread_id(); ?>" class="<?php bp_message_css_class(); ?><?php if ( bp_message_thread_has_unread() ) : ?> message-unread<?php endif; ?>">
						<td class="bulk-select-check">
							<div class="youplay-checkbox">
								<input id="bp-message-thread-<?php bp_message_thread_id(); ?>" type="checkbox" name="message_ids[]" value="<?php bp_message_thread_id(); ?>" class="message-check">
								<label for="bp-message-thread-<?php bp_message_thread_id(); ?>" class="mnr-20 ml-15"><span class="sr-only"><?php _e( 'Select this message', 'youplay' ); ?></span></label>
							</div>
						</td>

						<td class="message-from">
                            <a href="#" class="angled-img">
                                <div class="img">
                                    <?php bp_message_thread_avatar( array( 'width' => 80, 'height' => 80 ) ); ?>
                                </div>
                            </a>

							<?php if ( 'sentbox' != bp_current_action() ) : ?>
								<span class="message-from-name"><?php bp_message_thread_from(); ?></span>
							<?php else: ?>
								<span><?php _e( 'To:', 'youplay' ); ?></span>
								<span class="message-from-name"><?php bp_message_thread_to(); ?></span>
							<?php endif; ?>
							<br>

							<span class="date"><?php bp_message_thread_last_post_date(); ?></span>
						</td>

						<td class="message-description">
							<a href="<?php bp_message_thread_view_link(); ?>" title="<?php esc_attr_e( "View Message", 'youplay' ); ?>" class="message-description-name"><?php bp_message_thread_subject(); ?></a>
							<br>
							<div class="message-excerpt"><?php bp_message_thread_excerpt(); ?></div>
						</td>

						<?php

						/**
						 * Fires inside the messages box table row to add a new column.
						 *
						 * This is to primarily add a <td> cell to the message box table. Use the
						 * related 'bp_messages_inbox_list_header' hook to add a <th> header cell.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_messages_inbox_list_item' ); ?>

						<?php if ( bp_is_active( 'messages', 'star' ) ) : ?>
							<td class="thread-star">
								<?php bp_the_message_star_action_link( array( 'thread_id' => bp_get_message_thread_id() ) ); ?>
							</td>
						<?php endif; ?>

						<td class="message-action">
							<?php if($unread_count = bp_message_thread_unread_count()): ?>
                            	<span class="messages-count">+<?php echo esc_html( $unread_count ); ?></span>
                            <?php endif; ?>
							<a class="message-delete" href="<?php bp_message_thread_delete_link(); ?>"><i class="fa fa-times"></i></a>
						</td>
					</tr>

				<?php endwhile; ?>

			</tbody>

		</table><!-- #message-threads -->

		<div class="messages-options-nav">
			<?php bp_messages_bulk_management_dropdown(); ?>
		</div><!-- .messages-options-nav -->

		<?php wp_nonce_field( 'messages_bulk_nonce', 'messages_bulk_nonce' ); ?>
	</form>

	<?php

	/**
	 * Fires after the members messages threads.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_messages_threads' ); ?>

	<?php

	/**
	 * Fires and displays member messages options.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_member_messages_options' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, no messages were found.', 'youplay' ); ?></p>
	</div>

<?php endif;?>

<?php

/**
 * Fires after the members messages loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_messages_loop' ); ?>
