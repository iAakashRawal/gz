<?php
/**
 * Youplay Plugins Activation
 *
 * @package Youplay
 */
require nk_admin()->admin_path . '/vendor/class-tgm-plugin-activation.php';


/**
 * Register Required Plugins
 */
add_action( 'tgmpa_register', 'youplay_register_required_plugins' );
if ( ! function_exists( 'youplay_register_required_plugins' ) ) :
function youplay_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(

        // nK Themes Helper
        array(
            'name'       => 'nK Themes Helper',
            'slug'       => 'nk-themes-helper',
            'source'     => 'https://a.nkdev.info/wp-plugins/nk-themes-helper.zip',
            'required'   => true,
        ),

        // Youplay Core
        array(
            'name'       => 'Youplay Core',
            'slug'       => 'youplay-core',
            'source'     => 'https://a.nkdev.info/wp-plugins/youplay-core.zip',
            'version'    => '1.1.0',
            'required'   => true,
        ),

        // OptionTree
        array(
            'name'       => 'OptionTree',
            'slug'       => 'option-tree',
            'required'   => true,
        ),

        // AWB
        array(
            'name'       => 'Advanced WordPress Backgrounds',
            'slug'       => 'advanced-backgrounds',
            'required'   => false
        ),

        // Sociality
        array(
            'name'       => 'Sociality',
            'slug'       => 'sociality',
            'required'   => false,
        ),

        // Visual Composer
        array(
            'name'       => 'WPBakery Visual Composer',
            'slug'       => 'js_composer',
            'source'     => 'https://a.nkdev.info/wp-plugins/js_composer.zip',
            'required'   => true,
        ),

        // Rev Slider
        array(
            'name'       => 'Slider Revolution',
            'slug'       => 'revslider',
            'source'     => 'https://a.nkdev.info/wp-plugins/revslider.zip',
            'required'   => false,
        ),

        // YP ClanWars
        array(
            'name'       => 'YP ClanWars',
            'slug'       => 'yp-clanwars',
            'source'     => 'https://a.nkdev.info/wp-plugins/yp-clanwars.zip',
            'required'   => false,
        ),

        // WooCommerce
        array(
            'name'       => 'WooCommerce',
            'slug'       => 'woocommerce',
            'required'   => false,
        ),

        // BuddyPress
        array(
            'name'       => 'BuddyPress',
            'slug'       => 'buddypress',
            'required'   => false,
        ),

        // bbPress
        array(
            'name'       => 'bbPress',
            'slug'       => 'bbpress',
            'required'   => false,
        ),

        // Login With Ajax
        array(
            'name'       => 'Login With Ajax',
            'slug'       => 'login-with-ajax',
            'required'   => false,
        ),

        // Facebook Widget
        array(
            'name'       => 'Facebook Widget',
            'slug'       => 'facebook-pagelike-widget',
            'required'   => false,
        ),

    );

    $config = array(
        'domain'       => 'youplay',
        'default_path' => '',
        'has_notices'  => true,
        'message'      => ''
    );

    tgmpa( $plugins, $config );
}
endif;


// Visual Composer as theme
add_action( 'vc_before_init', 'youplay_vc_setastheme' );
function youplay_vc_setastheme() {
    vc_set_as_theme();
}

// Revolution Slider as theme
if(function_exists( 'set_revslider_as_theme' )) {
    add_action( 'init', 'youplay_rev_setastheme' );
    function youplay_rev_setastheme() {
        set_revslider_as_theme();
    }
}
