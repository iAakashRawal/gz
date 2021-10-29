<?php
/**
 * The template for displaying BuddyPress pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Youplay
 */

get_header();

while ( have_posts() ) : the_post();

    the_content();

    wp_link_pages( array(
        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'youplay' ),
        'after'  => '</div>',
    ) );


	// If comments are open or we have at least one comment, load up the comment template
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;

endwhile; // end of the loop.

get_footer();
