<?php
/**
 * Get changelog page content from url
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_nkth_get_changelog', 'nkth_get_changelog' );

/**
 * Theme Activation Action
 */
function nkth_get_changelog() {
    // phpcs:ignore
    $url = isset( $_GET['url'] ) ? sanitize_text_field( wp_unslash( $_GET['url'] ) ) : null;

    if ( null === $url ) {
        die();
    }

    $result = wp_remote_get( $url );
    $result = wp_remote_retrieve_body( $result );

    if ( $result ) {
        // phpcs:ignore
        echo $result;
    }

    die();
}
