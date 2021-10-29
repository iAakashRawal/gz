<?php
/**
 * AJAX disable ask for activation notice.
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_nkth_ask_for_activation_disable', 'nkth_ask_for_activation_disable' );

/**
 * Disable ask for activation notice action.
 */
function nkth_ask_for_activation_disable() {
    nkth()->update_option( 'ask_for_activation_status', 'disabled' );

    die();
}
