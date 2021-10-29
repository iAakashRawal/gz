<?php
/**
 * AJAX Activation
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'wp_ajax_nkth_activation_action', 'nkth_activation_action' );

/**
 * Theme Activation Action
 */
function nkth_activation_action() {
    check_ajax_referer( 'nkdev_info_activation', 'ajax_nonce' );

    $type        = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : null; // input var okay.
    $edd_license = isset( $_POST['edd_license'] ) ? sanitize_text_field( wp_unslash( $_POST['edd_license'] ) ) : null; // input var okay.

    $edd_name = nkth()->theme_dashboard()->options['edd_name'];

    if ( 'deactivate' === $type ) {
        if ( nkth()->theme_dashboard()->get_option( 'edd_license' ) ) {
            // phpcs:ignore
            $response = wp_remote_get( 'https://nkdev.info/?edd_action=deactivate_license&item_name=' . urlencode( $edd_name ) . '&license=' . esc_html( nkth()->theme_dashboard()->get_option( 'edd_license' ) ) . '&url=' . esc_url( home_url( '/' ) ) );

            if ( wp_remote_retrieve_response_code( $response ) === 200 && wp_remote_retrieve_body( $response ) ) {
                $response = json_decode( wp_remote_retrieve_body( $response ) );

                if ( isset( $response->success ) && isset( $response->license ) && 'deactivated' === $response->license ) {
                    nkth()->theme_dashboard()->update_option( 'edd_license', null );
                    echo 'ok';
                } elseif ( isset( $response->error ) ) {
                    echo esc_html( $response->error );
                }
            } else {
                if ( is_wp_error( $response ) ) {
                    echo 'Error: ' . esc_html( $response->get_error_message() );
                } else {
                    echo 'Error: failed connection.';
                }
            }
        }
    } else {
        $response = false;

        // verify purchase code.
        if ( null !== $edd_license && $edd_name ) {
            // phpcs:ignore
            $response = wp_remote_get( 'https://nkdev.info/?edd_action=activate_license&item_name=' . urlencode( $edd_name ) . '&license=' . esc_html( $edd_license ) . '&url=' . esc_url( home_url( '/' ) ) );
        } else {
            echo 'Error: purchase code was not specified.';
        }

        if ( $response ) {
            if ( wp_remote_retrieve_response_code( $response ) === 200 && wp_remote_retrieve_body( $response ) ) {
                $response = json_decode( wp_remote_retrieve_body( $response ) );

                if ( isset( $response->success ) ) {
                    if ( isset( $response->error ) ) {
                        echo 'Error: ' . esc_html( $response->error );
                    } else {
                        if ( $response->license && 'valid' === $response->license ) {
                            nkth()->theme_dashboard()->update_option( 'edd_license', $edd_license );
                            echo 'ok';
                        } else {
                            echo 'Error: Something went wrong. Please, contact us here https://nk.ticksy.com/';
                        }
                    }
                } elseif ( isset( $response->error ) ) {
                    echo esc_html( $response->response );
                }
            } else {
                if ( is_wp_error( $response ) ) {
                    echo 'Error: ' . esc_html( $response->get_error_message() );
                } else {
                    echo 'Error: failed connection.';
                }
            }
        }
    }

    die();
}
