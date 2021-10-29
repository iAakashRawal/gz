<?php
/**
 * AJAX disable ask for review notice.
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_nkth_ask_for_review_disable', 'nkth_ask_for_review_disable' );

/**
 * Disable ask for review notice action.
 */
function nkth_ask_for_review_disable() {
    nkth()->update_option( 'ask_for_review_status', 'disabled' );
    nkth()->update_option( 'ask_for_review_pending', false );

    die();
}
