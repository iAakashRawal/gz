<?php
/**
 * @package Youplay
 */
?>

<div id="post-<?php the_ID(); ?>" <?php post_class("news-one"); ?>>
    <div class="row vertical-gutter">
        <div class="col-md-4">
            <?php
            $hexagon = youplay_post_review_hexagon(true);
            youplay_post_thumbnail(false, $hexagon);
            ?>
        </div>
        <div class="col-md-8">
            <div class="entry-header clearfix">
                <?php the_title( sprintf( '<h2 class="entry-title pull-left m-0"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

                <?php if ( 'post' == get_post_type() ) : ?>
                    <span class="date pull-right">
                        <?php youplay_posted_on(); ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="tags">
                <?php youplay_post_tags(); ?>
            </div>

            <?php
            // we need to get this before the_excerpt() function
            // sometimes, when the post contains some shortcodes with WP_Query
            // youplay_read_more() function generates wrong post url
            ob_start();
            youplay_read_more();
            youplay_entry_footer();
            $post_footer = ob_get_clean();
            ?>

            <div class="entry-content description">
                <?php
                if ( ! post_password_required() ) {
                    the_excerpt();
                }
                ?>

                <?php
                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'youplay' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>

            <?php
                echo wp_kses_post( $post_footer );
            ?>
        </div>
    </div>
</div>
