<?php
/**
 * BuddyPress - Activity Loop
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires before the start of the activity loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_activity_loop' ); ?>

<?php if ( bp_has_activities( bp_ajax_querystring( 'activity' ) ) ) : ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		<ul id="activity-stream" class="youplay-timeline activity-list">

	<?php endif; ?>

	<?php while ( bp_activities() ) : bp_the_activity(); ?>

		<?php bp_get_template_part( 'activity/entry' ); ?>

	<?php endwhile; ?>

	<?php if ( empty( $_POST['page'] ) ) : ?>

		</ul>

	<?php endif; ?>

	<?php if ( bp_activity_has_more_items() ) : ?>

		<div class="load-more clearfix mt-50">
			<a href="<?php bp_activity_load_more_link() ?>" class="btn btn-default"><?php _e( 'Load More', 'youplay' ); ?></a>
		</div>

	<?php endif; ?>

<?php else : ?>

	<div id="message" class="alert alert-warning">
		<?php _e( 'Sorry, there was no activity found. Please try a different filter.', 'youplay' ); ?>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the finish of the activity loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_activity_loop' ); ?>

<?php if ( empty( $_POST['page'] ) ) : ?>

	<form action="" name="activity-loop-form" id="activity-loop-form" method="post">

		<?php wp_nonce_field( 'activity_filter', '_wpnonce_activity_filter' ); ?>

	</form>

<?php endif; ?>
