<?php
/**
 * Update Purchase Platform
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_nkth_update_purchase_platform', 'nkth_update_purchase_platform' );

/**
 * Theme Activation Action
 */
function nkth_update_purchase_platform() {
    // phpcs:ignore
    $platform = isset( $_GET['platform'] ) ? sanitize_text_field( wp_unslash( $_GET['platform'] ) ) : null;

    if ( null !== $platform ) {
        nkth()->theme_dashboard()->update_option( 'purchase_platform', $platform );
        echo 'ok';
    }

    die();
}
