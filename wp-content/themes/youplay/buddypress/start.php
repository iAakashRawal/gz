<?php
/**
 * Start of BuddyPress main pages
 *
 * @package Youplay
 */

$id = get_queried_object_id();
$side = strpos(yp_opts('buddypress_layout', true, $id, 'single_page_layout'), 'side-cont') !== false
                    ? 'left'
                    : (strpos(yp_opts('buddypress_layout', true, $id, 'single_page_layout'), 'cont-side') !== false
                      ? 'right'
                      : false);
$boxed_cont = yp_opts('buddypress_boxed_cont', true, $id, 'single_page_boxed_cont');
$banner = strpos(yp_opts('buddypress_layout', true, $id, 'single_page_layout'), 'banner') !== false;
$banner = $banner && shortcode_exists( 'yp_banner' );
$banner_cont = '';
$banner_size = yp_opts('buddypress_banner_size', true, $id, 'single_page_banner_size');
$banner_parallax = yp_opts('buddypress_banner_parallax', true, $id, 'single_page_banner_parallax');
$banner_parallax_speed_metabox = yp_opts('single_page_banner_parallax_speed_type', true, $id, 'single_page_banner_parallax_speed_type') === 'custom';
$banner_parallax_speed = yp_opts('buddypress_banner_parallax_speed', $banner_parallax_speed_metabox, $id, 'single_page_banner_parallax_speed');

$show_title = yp_opts('single_page_show_title', true, $id)?'':'style="display:none;"';

// generate banner content and cover image
$banner_src = yp_opts('buddypress_banner_image', true, $id, 'single_page_banner_image');

if($banner) {
    $bp = buddypress();
    $bp_cover_id = bp_displayed_user_id();
    $bp_cover = null;

    if(bp_is_group()) {
        $bp_cover = bp_attachments_get_attachment('url', array(
            'object_dir' => 'groups',
            'item_id'    => $bp->groups->current_group->id,
        ));

        /**
         * If the cover image feature is enabled, use a specific header
         */
        if ( bp_group_use_cover_image_header() ) :
            $banner_cont = youplay_bp_get_template_part( 'groups/single/cover-image-header' );
        else :
            $banner_cont = youplay_bp_get_template_part( 'groups/single/group-header' );
        endif;

        // set navigation in bottom of banner
        ob_start(); ?>
            [yp_banner_content_bottom]
                <ul>
                    <?php youplay_bp_get_options_nav(); ?>

                    <?php

                    /**
                     * Fires after the display of group options navigation.
                     *
                     * @since 1.2.0
                     */
                    do_action( 'bp_group_options_nav' ); ?>
                </ul>
            [/yp_banner_content_bottom]
        <?php
        $banner_cont = ob_get_contents() . $banner_cont;
        ob_end_clean();
    } else if (bp_displayed_user_id()) {
        $bp_cover = bp_attachments_get_attachment('url', array(
            'item_id'   => $bp_cover_id
        ));

        /**
         * If the cover image feature is enabled, use a specific header
         */
        if ( bp_displayed_user_use_cover_image_header() ) :
            $banner_cont = youplay_bp_get_template_part( 'members/single/cover-image-header' );
        else :
            $banner_cont = youplay_bp_get_template_part( 'members/single/member-header' );
        endif;

        // set navigation in bottom of banner
        ob_start(); ?>
            [yp_banner_content_bottom]
                <ul>
                    <?php youplay_bp_get_displayed_user_nav(); ?>

                    <?php

                    /**
                     * Fires after the display of member options navigation.
                     *
                     * @since 1.2.4
                     */
                    do_action( 'bp_member_options_nav' ); ?>
                </ul>
            [/yp_banner_content_bottom]
        <?php
        $banner_cont = ob_get_contents() . $banner_cont;
        ob_end_clean();
    } else if ($bp->current_component == 'activity') {
        $banner_cont = '<h1 ' . $show_title . ' class="entry-title">' . get_the_title() . '</h1>';

        // set navigation in bottom of banner
        ob_start(); ?>
            [yp_banner_content_bottom]
                <ul>
                    <?php

                    /**
                     * Fires before the listing of activity type tabs.
                     *
                     * @since 1.2.0
                     */
                    do_action( 'bp_before_activity_type_tab_all' ); ?>

                    <li class="active" id="activity-all"><a href="<?php bp_activity_directory_permalink(); ?>" title="<?php esc_attr_e( 'The public activity for everyone on this site.', 'youplay' ); ?>"><?php printf( __( 'All Members %s', 'youplay' ), '<span class="badge mnb-1">' . bp_get_total_member_count() . '</span>' ); ?></a></li>

                    <?php if ( is_user_logged_in() ) : ?>

                        <?php

                        /**
                         * Fires before the listing of friends activity type tab.
                         *
                         * @since 1.2.0
                         */
                        do_action( 'bp_before_activity_type_tab_friends' ); ?>

                        <?php if ( bp_is_active( 'friends' ) ) : ?>

                            <?php if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

                                <li id="activity-friends"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/'; ?>" title="<?php esc_attr_e( 'The activity of my friends only.', 'youplay' ); ?>"><?php printf( __( 'My Friends %s', 'youplay' ), '<span class="badge mnb-1">' . bp_get_total_friend_count( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>

                            <?php endif; ?>

                        <?php endif; ?>

                        <?php

                        /**
                         * Fires before the listing of groups activity type tab.
                         *
                         * @since 1.2.0
                         */
                        do_action( 'bp_before_activity_type_tab_groups' ); ?>

                        <?php if ( bp_is_active( 'groups' ) ) : ?>

                            <?php if ( bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>

                                <li id="activity-groups"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/'; ?>" title="<?php esc_attr_e( 'The activity of groups I am a member of.', 'youplay' ); ?>"><?php printf( __( 'My Groups %s', 'youplay' ), '<span class="badge mnb-1">' . bp_get_total_group_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>

                            <?php endif; ?>

                        <?php endif; ?>

                        <?php

                        /**
                         * Fires before the listing of favorites activity type tab.
                         *
                         * @since 1.2.0
                         */
                        do_action( 'bp_before_activity_type_tab_favorites' ); ?>

                        <?php if ( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) : ?>

                            <li id="activity-favorites"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/'; ?>" title="<?php esc_attr_e( "The activity I've marked as a favorite.", 'youplay' ); ?>"><?php printf( __( 'My Favorites %s', 'youplay' ), '<span class="badge mnb-1">' . bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>

                        <?php endif; ?>

                        <?php if ( bp_activity_do_mentions() ) : ?>

                            <?php

                            /**
                             * Fires before the listing of mentions activity type tab.
                             *
                             * @since 1.2.0
                             */
                            do_action( 'bp_before_activity_type_tab_mentions' ); ?>

                            <li id="activity-mentions"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/'; ?>" title="<?php esc_attr_e( 'Activity that I have been mentioned in.', 'youplay' ); ?>"><?php _e( 'Mentions', 'youplay' ); ?><?php if ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) : ?> <strong><span class="badge mnb-1"><?php printf( _nx( '%s new', '%s new', bp_get_total_mention_count_for_user( bp_loggedin_user_id() ), 'Number of new activity mentions', 'youplay' ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ); ?></span></strong><?php endif; ?></a></li>

                        <?php endif; ?>

                    <?php endif; ?>

                    <?php

                    /**
                     * Fires after the listing of activity type tabs.
                     *
                     * @since 1.2.0
                     */
                    do_action( 'bp_activity_type_tabs' ); ?>
                </ul>
            [/yp_banner_content_bottom]
        <?php
        $banner_cont .= ob_get_contents();
        ob_end_clean();
    } else {
        $banner_cont = '<h1 ' . $show_title . ' class="entry-title">' . get_the_title() . '</h1>';
    }


    if($bp_cover) {
        $banner_src = $bp_cover;
    }
}

?>

<section class="content-wrap <?php echo ($banner?'':'no-banner'); ?>" id="buddypress">
    <?php
        // check if layout with banner
        if ($banner) {
            echo do_shortcode('[yp_banner img_src="' . $banner_src . '" img_size="1920x1080" banner_size="' . $banner_size . '" parallax="' . $banner_parallax . '" parallax_speed="' . $banner_parallax_speed . '" top_position="true"]' . $banner_cont . '[/yp_banner]');
        } else {
            the_title('<h1 class="' . ($boxed_cont?'container':'') . '">', '</h1>');
        }

    ?>

    <div class="<?php echo esc_attr(($boxed_cont?'container ':'container-fluid ') . ' youplay-content'); ?> ">
        <div class="row">
            <?php $layout = yp_get_layout_data(); ?>

            <main class="<?php echo esc_attr($layout['content_class']); ?>">
