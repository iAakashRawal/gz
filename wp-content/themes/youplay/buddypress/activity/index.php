<?php
/**
 * BuddyPress Activity templates
 *
 * @since 2.3.0
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

get_template_part( 'buddypress/start' );

/**
 * Fires before the activity directory listing.
 *
 * @since 1.5.0
 */
do_action( 'bp_before_directory_activity' ); ?>

	<?php

	/**
	 * Fires before the activity directory display content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_before_directory_activity_content' ); ?>

	<?php

	/**
	 * Fires towards the top of template pages for notice display.
	 *
	 * @since 1.0.0
	 */
	do_action( 'template_notices' ); ?>

	<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
		<ul class="pagination no-ajax pagination-sm">
			<li class="feed"><a href="<?php bp_sitewide_activity_feed_link(); ?>" title="<?php esc_attr_e( 'RSS Feed', 'youplay' ); ?>"><?php _e( 'RSS', 'youplay' ); ?></a></li>

			<?php

			/**
			 * Fires before the display of the activity syndication options.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_activity_syndication_options' ); ?>

			<li id="activity-filter-select" class="last">
				<label for="activity-filter-by"><?php _e( 'Show:', 'youplay' ); ?></label>
				<div class="youplay-select">
					<select id="activity-filter-by">
						<option value="-1"><?php _e( '&mdash; Everything &mdash;', 'youplay' ); ?></option>

						<?php bp_activity_show_filters(); ?>

						<?php

						/**
						 * Fires inside the select input for activity filter by options.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_activity_filter_options' ); ?>

					</select>

			</li>
		</ul>
	</div><!-- .item-list-tabs -->

	<?php if ( is_user_logged_in() ) : ?>

		<?php bp_get_template_part( 'activity/post-form' ); ?>

	<?php endif; ?>

	<?php

	/**
	 * Fires before the display of the activity list.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_before_directory_activity_list' ); ?>

	<div class="activity">

		<?php bp_get_template_part( 'activity/activity-loop' ); ?>

	</div><!-- .activity -->

	<?php

	/**
	 * Fires after the display of the activity list.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_after_directory_activity_list' ); ?>

	<?php

	/**
	 * Fires inside and displays the activity directory display content.
	 */
	do_action( 'bp_directory_activity_content' ); ?>

	<?php

	/**
	 * Fires after the activity directory display content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_directory_activity_content' ); ?>

	<?php

	/**
	 * Fires after the activity directory listing.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_after_directory_activity' ); ?>

<?php get_template_part( 'buddypress/end' ); ?>
