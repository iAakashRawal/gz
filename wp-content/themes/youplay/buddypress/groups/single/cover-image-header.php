<?php
/**
 * BuddyPress - Groups Cover Image Header.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires before the display of a group's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_group_header' ); ?>

<div class="youplay-user">

	<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
	    <a href="<?php bp_group_permalink(); ?>" class="angled-img" id="item-header-avatar">
	        <div class="img">
	            <?php bp_group_avatar(); ?>
	        </div>
	    </a>
	<?php endif; ?>

    <div class="user-data">
        <h2><?php bp_group_name(); ?></h2>

        <div id="item-header-content">

            <?php

            /**
             * Fires before the display of the group's header meta.
             *
             * @since 1.2.0
             */
            do_action( 'bp_before_group_header_meta' ); ?>

            <div id="item-meta">

                <?php

                /**
                 * Fires after the group header actions section.
                 *
                 * @since 1.2.0
                 */
                do_action( 'bp_group_header_meta' ); ?>

                <span class="highlight"><?php bp_group_type(); ?></span>
                <div class="mt-10">
                    <small class="text-muted">
                        <?php printf( __( 'active %s', 'youplay' ), bp_get_group_last_active() ); ?>
                    </small>
                </div>

            </div>
        </div><!-- #item-header-content -->
    </div>
</div>

<?php bp_group_description(); ?>


<div id="item-buttons" class="mt-20"><?php

	/**
	 * Fires in the group header actions section.
	 *
	 * @since 1.2.6
	 */
	do_action( 'bp_group_header_actions' ); ?>
</div><!-- #item-buttons -->

<!--
<div id="item-actions">

	<?php if ( bp_group_is_visible() ) : ?>

		<h3><?php _e( 'Group Admins', 'youplay' ); ?></h3>

		<?php bp_group_list_admins();

		/**
		 * Fires after the display of the group's administrators.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_group_menu_admins' );

		if ( bp_group_has_moderators() ) :

			/**
			 * Fires before the display of the group's moderators, if there are any.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_before_group_menu_mods' ); ?>

			<h3><?php _e( 'Group Mods' , 'youplay' ); ?></h3>

			<?php bp_group_list_mods();

			/**
			 * Fires after the display of the group's moderators, if there are any.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_after_group_menu_mods' );

		endif;

	endif; ?>

</div>
 -->

<?php

/**
 * Fires after the display of a group's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_group_header' );


