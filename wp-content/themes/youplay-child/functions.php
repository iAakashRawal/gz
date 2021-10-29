<?php
/**
 * Youplay Child functions and definitions
 *
 * @package Youplay Child
 */

add_action( 'wp_enqueue_scripts', 'yp_child_enqueue', 15 );
if ( ! function_exists( 'yp_child_enqueue' ) ) :
    /**
     * Enqueue child theme styles
     */
    function yp_child_enqueue () {
        wp_enqueue_style( 'youplay-child', get_stylesheet_directory_uri() . '/style.css' );
        wp_enqueue_script( 'youplay-child', get_stylesheet_directory_uri() . '/script.js', array( 'jquery' ) );
    }
endif;