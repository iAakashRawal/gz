<?php
/**
 * AJAX Demo Import
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_nkth_demo_import_action', 'nkth_demo_import_action' );

/**
 * Demo Importer AJAX Action
 */
function nkth_demo_import_action() {
    // reset info about current demo.
    nkth()->theme_dashboard()->update_option( 'active_demo', '' );

    $demo_options = nkth()->theme_dashboard()->options;

    // phpcs:ignore
    $demo_name = isset( $_GET['demo_name'] ) ? sanitize_text_field( wp_unslash( $_GET['demo_name'] ) ) : 'main';

    // get demo data.
    if ( ! isset( $demo_options ) || ! isset( $demo_options['demos'] ) || ! count( $demo_options['demos'] ) || ! isset( $demo_options['demos'][ $demo_name ] ) ) {
        exit();
    }
    $demo_data = $demo_options['demos'][ $demo_name ]['demo_data'];

    // start import.
    nkth()->demo_importer()->stream_import( $demo_data );

    // save info about current active demo.
    nkth()->theme_dashboard()->update_option( 'active_demo', $demo_name );

    exit();
}
