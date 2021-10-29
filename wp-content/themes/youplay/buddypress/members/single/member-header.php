<?php
/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php

/**
 * Fires before the display of a member's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_header' ); ?>

<div class="youplay-user">

    <a href="<?php bp_displayed_user_link(); ?>" class="angled-img">
        <div class="img">
            <?php bp_displayed_user_avatar( 'type=full' ); ?>
        </div>
    </a>
    <div class="user-data">
		<?php if ( bp_is_active( 'activity' ) && bp_activity_do_mentions() ) : ?>
			<h2><?php the_title(); ?></h2>
			@<?php bp_displayed_user_mentionname(); ?>
		<?php endif; ?>

		<?php

		/**
		 * Fires before the display of the member's header meta.
		 *
		 * @since 1.2.0
		 */
		do_action( 'bp_before_member_header_meta' ); ?>

		<div class="activity">
			<div>
				<div class="title">
					<?php bp_last_activity( bp_displayed_user_id() ); ?>
				</div>
			</div>
		</div>
		<?php

		 /**
		  * Fires after the group header actions section.
		  *
		  * If you'd like to show specific profile fields here use:
		  * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
		  *
		  * @since 1.2.0
		  */
		 do_action( 'bp_profile_header_meta' );

		 ?>
    </div>
</div>


<?php

/**
 * Fires before the display of the member's header meta.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_header_meta' ); ?>

<div id="item-meta">

	<?php if ( bp_is_active( 'activity' ) ) : ?>

		<div id="latest-update" class="mt-20">

			<?php bp_activity_latest_update( bp_displayed_user_id() ); ?>

		</div>

	<?php endif; ?>

	<div id="item-buttons" class="mt-20">

		<?php

		/**
		 * Fires in the member header actions section.
		 *
		 * @since 1.2.6
		 */
		do_action( 'bp_member_header_actions' ); ?>

	</div><!-- #item-buttons -->

	<?php

	 /**
	  * Fires after the group header actions section.
	  *
	  * If you'd like to show specific profile fields here use:
	  * bp_member_profile_data( 'field=About Me' ); -- Pass the name of the field
	  *
	  * @since 1.2.0
	  */
	 do_action( 'bp_profile_header_meta' );

	 ?>

</div><!-- #item-meta -->

<?php

/**
 * Fires after the display of a member's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_header' ); ?>

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
do_action( 'template_notices' ); ?>
