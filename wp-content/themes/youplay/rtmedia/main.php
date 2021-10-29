<?php
/* * **************************************
 * Main.php
 *
 * The main template file, that loads the header, footer and sidebar
 * apart from loading the appropriate rtMedia template
 * *************************************** */
// by default it is not an ajax request
global $rt_ajax_request;
$rt_ajax_request = false;

// check if it is an ajax request
$_rt_ajax_request = rtm_get_server_var( 'HTTP_X_REQUESTED_WITH', 'FILTER_SANITIZE_STRING' );
if ( 'xmlhttprequest' === strtolower( $_rt_ajax_request ) ) {
    $rt_ajax_request = true;
}

//if it's not an ajax request, load headers
if ( ! $rt_ajax_request ) {
    get_template_part( 'buddypress/start' );
} // if ajax

// include the right rtMedia template
rtmedia_load_template();

if ( ! $rt_ajax_request ) {
    get_template_part( 'buddypress/end' );
}

