<?php
/**
 * BuddyPress - Users Forums
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

		<li id="forums-order-select" class="last filter">

			<label for="forums-order-by"><?php _e( 'Order By:', 'youplay' ); ?></label>
			<div class="youplay-select">
				<select id="forums-order-by">
					<option value="active"><?php _e( 'Last Active', 'youplay' ); ?></option>
					<option value="popular"><?php _e( 'Most Posts', 'youplay' ); ?></option>
					<option value="unreplied"><?php _e( 'Unreplied', 'youplay' ); ?></option>

					<?php

					/**
					 * Fires inside the members forums order options select input.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_forums_directory_order_options' ); ?>

				</select>
			</div>
		</li>
	</ul>
</div><!-- .item-list-tabs -->

<?php

if ( bp_is_current_action( 'favorites' ) ) :
	bp_get_template_part( 'members/single/forums/topics' );

else :

	/**
	 * Fires before the display of member forums content.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_before_member_forums_content' ); ?>

	<div class="forums myforums">

		<?php bp_get_template_part( 'forums/forums-loop' ) ?>

	</div>

	<?php

	/**
	 * Fires after the display of member forums content.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_after_member_forums_content' ); ?>

<?php endif; ?>
