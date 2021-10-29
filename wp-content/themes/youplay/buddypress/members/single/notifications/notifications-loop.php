<?php
/**
 * BuddyPress - Members Notifications Loop
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<form action="" method="post" id="notifications-bulk-management">
	<table class="notifications table table-hover">
		<thead>
			<tr style="background: none;">
				<th class="icon"></th>
				<th class="bulk-select-all">
					<div class="youplay-checkbox">
						<input id="select-all-notifications" type="checkbox">
						<label for="select-all-notifications" class="mnr-20 ml-15"><span class="sr-only"><?php _e( 'Select all', 'youplay' ); ?></span></label>
					</div>
				</th>
				<th class="title"><?php _e( 'Notification', 'youplay' ); ?></th>
				<th class="date"><?php _e( 'Date Received', 'youplay' ); ?></th>
				<th class="actions"><?php _e( 'Actions',    'youplay' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php while ( bp_the_notifications() ) : bp_the_notification(); ?>

				<tr>
					<td></td>
					<td class="bulk-select-check">
						<div class="youplay-checkbox">
							<input id="<?php bp_the_notification_id(); ?>" type="checkbox" name="notifications[]" value="<?php bp_the_notification_id(); ?>" class="notification-check">
							<label for="<?php bp_the_notification_id(); ?>" class="mnr-20 ml-15"><span class="sr-only"><?php _e( 'Select this notification', 'youplay' ); ?></span></label>
						</div>
					</td>
					<td class="notification-description"><?php bp_the_notification_description();  ?></td>
					<td class="notification-since"><?php bp_the_notification_time_since();   ?></td>
					<td class="notification-actions"><?php bp_the_notification_action_links(); ?></td>
				</tr>

			<?php endwhile; ?>

		</tbody>
	</table>

	<div class="notifications-options-nav">
		<?php bp_notifications_bulk_management_dropdown(); ?>
	</div><!-- .notifications-options-nav -->

	<?php wp_nonce_field( 'notifications_bulk_nonce', 'notifications_bulk_nonce' ); ?>
</form>
