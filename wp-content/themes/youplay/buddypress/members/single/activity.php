<?php
/**
 * BuddyPress - Users Activity
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<div class="item-list-tabs no-ajax clearfix" id="subnav" role="navigation">
	<ul>
		<li>
			<ul class="pagination no-ajax pagination-sm">
				<?php youplay_bp_get_options_nav(); ?>
			</ul>
		</li>

		<li id="activity-filter-select" class="last">
			<label for="activity-filter-by"><?php _e( 'Show:', 'youplay' ); ?></label>
			<div class="youplay-select">
				<select id="activity-filter-by">
					<option value="-1"><?php _e( '&mdash; Everything &mdash;', 'youplay' ); ?></option>

					<?php bp_activity_show_filters(); ?>

					<?php

					/**
					 * Fires inside the select input for member activity filter options.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_member_activity_filter_options' ); ?>

				</select>
			</div>
		</li>
	</ul>
</div><!-- .item-list-tabs -->

<div class="clearfix"></div>

<?php

/**
 * Fires before the display of the member activity post form.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_activity_post_form' ); ?>

<?php
if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) )
	bp_get_template_part( 'activity/post-form' );

/**
 * Fires after the display of the member activity post form.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_activity_post_form' );

/**
 * Fires before the display of the member activities list.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_activity_content' ); ?>

<div class="activity">

	<?php bp_get_template_part( 'activity/activity-loop' ) ?>

</div><!-- .activity -->

<?php

/**
 * Fires after the display of the member activities list.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_activity_content' ); ?>
