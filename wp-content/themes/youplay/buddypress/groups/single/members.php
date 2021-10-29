<?php
/**
 * BuddyPress - Groups Members
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php if ( bp_group_has_members( bp_ajax_querystring( 'group_members' ) ) ) : ?>

	<?php

	/**
	 * Fires before the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_content' ); ?>

	<div id="pag-top" class="pagination text-mute">

		<div class="pag-count" id="member-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires before the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_group_members_list' ); ?>

	<table id="member-list" class="item-list table table-hover">

		<?php while ( bp_group_members() ) : bp_group_the_member(); ?>

			<tr>
				<td class="item-avatar p-15" width="130">
					<a href="<?php bp_group_member_domain(); ?>" class="angled-img">
						<div class="img">
							<?php bp_group_member_avatar_thumb(); ?>
						</div>
					</a>
				</td>

				<td class="item-title p-15">
					<?php bp_group_member_link(); ?>
					<br>
					<span class="date"><?php bp_group_member_joined_since(); ?></span>
				</td>

				<td class="p-15 text-right">
					<?php

					/**
					 * Fires inside the listing of an individual group member listing item.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_group_members_list_item' ); ?>

					<?php if ( bp_is_active( 'friends' ) ) : ?>

						<div class="action">

							<?php bp_add_friend_button( bp_get_group_member_id(), bp_get_group_member_is_friend() ); ?>

							<?php

							/**
							 * Fires inside the action section of an individual group member listing item.
							 *
							 * @since 1.1.0
							 */
							do_action( 'bp_group_members_list_item_action' ); ?>

						</div>

					<?php endif; ?>
				</td>
			</tr>

		<?php endwhile; ?>

	</table>

	<?php

	/**
	 * Fires after the display of the group members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_list' ); ?>

	<div id="pag-bottom" class="pagination text-mute">

		<div class="pag-count" id="member-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php

	/**
	 * Fires after the display of the group members content.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_group_members_content' ); ?>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'No members were found.', 'youplay' ); ?></p>
	</div>

<?php endif; ?>
