<?php
// bbPress is active
if ( !class_exists( 'buddypress' ) ) {
    return;
}

if(!defined('BP_AVATAR_THUMB_WIDTH')) {
    define( 'BP_AVATAR_THUMB_WIDTH', 80 );
}
if(!defined('BP_AVATAR_THUMB_HEIGHT')) {
    define( 'BP_AVATAR_THUMB_HEIGHT', 80 );
}
if(!defined('BP_AVATAR_FULL_WIDTH')) {
    define( 'BP_AVATAR_FULL_WIDTH', 200 );
}
if(!defined('BP_AVATAR_FULL_HEIGHT')) {
    define( 'BP_AVATAR_FULL_HEIGHT', 200 );
}

/* Classes for timeline item */
if ( ! function_exists( 'youplay_get_activity_css_class' ) ) :
function youplay_get_activity_css_class($classes) {
    return str_replace('activity-item', 'activity-item youplay-timeline-block', $classes);
}
endif;
add_filter( 'bp_get_activity_css_class', 'youplay_get_activity_css_class' );


/* Classes for delete button */
if ( ! function_exists( 'youplay_get_activity_delete_link' ) ) :
function youplay_get_activity_delete_link($classes) {
    return str_replace('button item-button bp-secondary-action delete-activity confirm', '', $classes);
}
endif;
add_filter( 'bp_get_activity_delete_link', 'youplay_get_activity_delete_link' );


/* Avatar Sizes */
if ( ! function_exists( 'youplay_core_avatar_thumb_sizes' ) ) :
function youplay_core_avatar_thumb_sizes() {
    return 100;
}
endif;
add_filter( 'bp_core_avatar_thumb_width', 'youplay_core_avatar_thumb_sizes' );
add_filter( 'bp_core_avatar_thumb_height', 'youplay_core_avatar_thumb_sizes' );


/* Responsive oEmbeds */
if ( ! function_exists( 'youplay_bp_embed_oembed_html' ) ) :
function youplay_bp_embed_oembed_html($html) {
    return '<div class="responsive-embed responsive-embed-16x9">' . $html . '</div>';
}
endif;
add_filter( 'bp_embed_oembed_html', 'youplay_bp_embed_oembed_html' );


/**
 * Cover image callback
 *
 * @see bp_legacy_theme_cover_image() to discover the one used by BP Legacy
 */
if ( ! function_exists( 'youplay_cover_image_callback' ) ) :
function youplay_cover_image_callback( $params = array() ) {
    if ( empty( $params ) ) {
        return;
    }

    return '';
}
endif;

if ( ! function_exists( 'youplay_cover_image_css' ) ) :
function youplay_cover_image_css( $settings = array() ) {
    $settings['callback'] = 'youplay_cover_image_callback';
    return $settings;
}
endif;
add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'youplay_cover_image_css', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'youplay_cover_image_css', 10, 1 );


/**
 * Style default buddypress buttons
 */
if ( ! function_exists( 'youplay_bp_profile_buttons' ) ) :
function youplay_bp_profile_buttons( $button ) {
    $button['link_class'] .= ' btn btn-sm btn-default';
    $button['wrapper'] = false;
    return $button;
}
endif;
add_filter( 'bp_get_add_friend_button', 'youplay_bp_profile_buttons' );
add_filter( 'bp_get_send_public_message_button', 'youplay_bp_profile_buttons' );
add_filter( 'bp_get_send_message_button_args', 'youplay_bp_profile_buttons' );
add_filter( 'bp_get_group_join_button', 'youplay_bp_profile_buttons' );
add_filter( 'bp_get_group_new_topic_button', 'youplay_bp_profile_buttons' );



/* Changed BuddyPress tabs */
if ( ! function_exists( 'youplay_bp_get_options_nav' ) ) :
function youplay_bp_get_options_nav() {
    ob_start();
    bp_get_options_nav();
    $result = ob_get_contents();
    ob_end_clean();

    $result = str_replace('current selected', 'current selected active', $result);
    $result = str_replace('<span class="count">', '<span class="badge mnb-1">', $result);
    $result = str_replace('<span class="no-count">', '<span class="badge mnb-1 sr-only">', $result);
    $result = str_replace('<span>', '<span class="badge mnb-1">', $result);

    echo wp_kses_post( $result );
}
endif;
if ( ! function_exists( 'youplay_bp_get_displayed_user_nav' ) ) :
function youplay_bp_get_displayed_user_nav() {
    ob_start();
    bp_get_displayed_user_nav();
    $result = ob_get_contents();
    ob_end_clean();

    $result = str_replace('current selected', 'current selected active', $result);
    $result = str_replace('<span class="count">', '<span class="badge mnb-1">', $result);
    $result = str_replace('<span class="no-count">', '<span class="badge mnb-1 sr-only">', $result);
    $result = str_replace('<span>', '<span class="badge mnb-1">', $result);

    echo wp_kses_post( $result );
}
endif;
if ( ! function_exists( 'youplay_bp_group_admin_tabs' ) ) :
function youplay_bp_group_admin_tabs() {
    ob_start();
    bp_group_admin_tabs();
    $result = ob_get_contents();
    ob_end_clean();

    $result = str_replace('current selected', 'current selected active', $result);
    $result = str_replace('<span class="count">', '<span class="badge mnb-1">', $result);
    $result = str_replace('<span class="no-count">', '<span class="badge mnb-1 sr-only">', $result);
    $result = str_replace('<span>', '<span class="badge mnb-1">', $result);

    echo wp_kses_post( $result );
}
endif;


/* Changed get template to return value, not print */
if ( ! function_exists( 'youplay_bp_get_template_part' ) ) :
function youplay_bp_get_template_part($slug, $name = null) {
    ob_start();
    bp_get_template_part($slug, $name);
    $result = ob_get_contents();
    ob_end_clean();

    return $result;
}
endif;


if ( ! function_exists( 'youplay_bp_message_thread_unread_count' ) ) :
function youplay_bp_message_thread_unread_count( $thread_id = false ) {
    if ( false === $thread_id ) {
        $thread_id = bp_get_message_thread_id();
    }

    $unread = bp_get_message_thread_unread_count( $thread_id );

    return $unread;
}
endif;


/* Groups */
if ( ! function_exists( 'youplay_bp_groups_members_filter' ) ) :
function youplay_bp_groups_members_filter() {
    ?>
    <li id="group_members-order-select" class="last filter">
        <label for="group_members-order-by"><?php _e( 'Order By:', 'youplay' ); ?></label>
        <div class="youplay-select">
            <select id="group_members-order-by">
                <option value="last_joined"><?php _e( 'Newest', 'youplay' ); ?></option>
                <option value="first_joined"><?php _e( 'Oldest', 'youplay' ); ?></option>

                <?php if ( bp_is_active( 'activity' ) ) : ?>
                    <option value="group_activity"><?php _e( 'Group Activity', 'youplay' ); ?></option>
                <?php endif; ?>

                <option value="alphabetical"><?php _e( 'Alphabetical', 'youplay' ); ?></option>

                <?php

                /**
                 * Fires at the end of the Group members filters select input.
                 *
                 * Useful for plugins to add more filter options.
                 *
                 * @since 2.0.0
                 */
                do_action( 'bp_groups_members_order_options' ); ?>

            </select>
        </div>
    </li>
    <?php
}
endif;
if ( ! function_exists( 'youplay_bp_groups_members_template_part' ) ) :
function youplay_bp_groups_members_template_part() {
    ?>
    <div class="item-list-tabs" id="subnav" role="navigation text-mute">
        <ul>
            <li role="search">
                <?php youplay_bp_directory_members_search_form(); ?>
            </li>

            <?php youplay_bp_groups_members_filter(); ?>
            <?php

            /**
             * Fires at the end of the group members search unordered list.
             *
             * Part of bp_groups_members_template_part().
             *
             * @since 1.5.0
             */
            do_action( 'bp_members_directory_member_sub_types' ); ?>

        </ul>
    </div>

    <div id="members-group-list" class="group_members dir-list">

        <?php bp_get_template_part( 'groups/single/members' ); ?>

    </div>
    <?php
}
endif;
if ( ! function_exists( 'youplay_bp_directory_members_search_form' ) ) :
function youplay_bp_directory_members_search_form() {
    $query_arg = bp_core_get_component_search_query_arg( 'members' );

    if ( ! empty( $_REQUEST[ $query_arg ] ) ) {
        $search_value = stripslashes( $_REQUEST[ $query_arg ] );
    } else {
        $search_value = bp_get_search_default_text( 'members' );
    }

    $search_form_html = '<form action="" method="get" id="search-members-form">
        <div class="youplay-input dib">
            <input type="text" name="' . esc_attr( $query_arg ) . '" id="members_search" placeholder="'. esc_attr( $search_value ) .'" />
        </div>
        <button type="submit" id="members_search_submit" name="members_search_submit" class="btn btn-default">' . __( 'Search', 'youplay' ) . '</button>
    </form>';

    echo apply_filters( 'bp_directory_members_search_form', $search_form_html );
}
endif;

if ( ! function_exists( 'youplay_bp_group_creation_tabs' ) ) :
function youplay_bp_group_creation_tabs() {
    $bp = buddypress();

    if ( !is_array( $bp->groups->group_creation_steps ) ) {
        return false;
    }

    if ( !bp_get_groups_current_create_step() ) {
        $keys = array_keys( $bp->groups->group_creation_steps );
        $bp->groups->current_create_step = array_shift( $keys );
    }

    $counter = 1;

    foreach ( (array) $bp->groups->group_creation_steps as $slug => $step ) {
        $is_enabled = bp_are_previous_group_creation_steps_complete( $slug ); ?>

        <li<?php if ( bp_get_groups_current_create_step() == $slug ) : ?> class="active"<?php endif; ?>><?php if ( $is_enabled ) : ?><a href="<?php bp_groups_directory_permalink(); ?>create/step/<?php echo esc_attr( $slug ) ?>/"><?php else: ?><span><?php endif; ?><?php echo esc_html( $counter ) ?> . <?php echo esc_html( $step['name'] ) ?><?php if ( $is_enabled ) : ?></a><?php else: ?></span><?php endif ?></li><?php
        $counter++;
    }

    unset( $is_enabled );

    /**
     * Fires at the end of the creation of the group tabs.
     *
     * @since 1.0.0
     */
    do_action( 'groups_creation_tabs' );
}
endif;




/* Group Tabs */
if ( ! function_exists( 'youplay_xprofile_filter_profile_group_tabs' ) ) :
function youplay_xprofile_filter_profile_group_tabs( $tabs = array('') ) {
    $new_tabs = array();
    foreach($tabs as $tab) {
        $new_tabs[] = str_replace('class="current"', 'class="current active"', $tab);
    }
    return $new_tabs;
}
endif;
add_filter( 'xprofile_filter_profile_group_tabs', 'youplay_xprofile_filter_profile_group_tabs' );

/* Checkbox */
if ( ! function_exists( 'youplay_bp_get_the_profile_field_options_checkbox' ) ) :
function youplay_bp_get_the_profile_field_options_checkbox( $html, $options = null, $id = null, $selected = null, $k = null ) {
    $new_html = sprintf( '<div class="youplay-checkbox ml-10"><input %1$s type="checkbox" name="%2$s" id="%3$s" value="%4$s"><label for="%3$s">%5$s</label></div>',
        $selected,
        esc_attr( "field_{$id}[]" ),
        esc_attr( "field_{$options->id}_{$k}" ),
        esc_attr( stripslashes( $options->name ) ),
        esc_html( stripslashes( $options->name ) )
    );
    return $new_html;
}
endif;
add_filter( 'bp_get_the_profile_field_options_checkbox', 'youplay_bp_get_the_profile_field_options_checkbox', 10, 5 );

/* Radio */
if ( ! function_exists( 'youplay_bp_get_the_profile_field_options_radio' ) ) :
function youplay_bp_get_the_profile_field_options_radio( $html, $options = null, $id = null, $selected = null, $k = null ) {
    $new_html = sprintf( '<div class="youplay-radio ml-10"><input %1$s type="radio" name="%2$s" id="%3$s" value="%4$s"><label for="%3$s">%5$s</label></div>',
        $selected,
        esc_attr( "field_{$id}[]" ),
        esc_attr( "field_{$options->id}_{$k}" ),
        esc_attr( stripslashes( $options->name ) ),
        esc_html( stripslashes( $options->name ) )
    );
    return $new_html;
}
endif;
add_filter( 'bp_get_the_profile_field_options_radio', 'youplay_bp_get_the_profile_field_options_radio', 10, 5 );
