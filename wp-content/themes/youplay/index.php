<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Youplay
 */

$side = strpos(yp_opts('archive_layout', true), 'side-cont') !== false
                    ? 'left'
                    : (strpos(yp_opts('archive_layout', true), 'cont-side') !== false
                      ? 'right'
                      : false);
$boxed_cont = yp_opts('archive_boxed_cont', true);
$banner = strpos(yp_opts('archive_layout', true), 'banner') !== false;
$banner = $banner && shortcode_exists( 'yp_banner' );

get_header(); ?>

    <section class="content-wrap <?php echo ($banner?'':'no-banner'); ?>">
        <?php
            // check if layout with banner
            if ($banner) {
                echo do_shortcode('[yp_banner img_src="' . yp_opts('archive_banner_image', true) . '" img_size="1920x1080" banner_size="' . yp_opts('archive_banner_size', true) . '" parallax="' . yp_opts('archive_banner_parallax', true) . '" parallax_speed="' . yp_opts('archive_banner_parallax_speed', true) . '" top_position="true"]<h1 class="entry-title h2">' . get_bloginfo('name') . '</h1>[/yp_banner]');
            } else {
                echo '<h1 class="' . ($boxed_cont?'container':'') . ' entry-title mb-0">' . get_bloginfo('name') . '</h1>';
            }
        ?>

        <div class="<?php echo ($boxed_cont?'container':'container-fluid'); ?> youplay-news youplay-content">
            <div class="row">
                <?php $layout = yp_get_layout_data(); ?>

                <main class="<?php echo esc_attr($layout['content_class']); ?>">

                    <?php if ( have_posts() ) : ?>

                        <?php while ( have_posts() ) : the_post(); ?>

                            <?php
                                /* Include the Post-Format-specific template for the content.
                                 * If you want to override this in a child theme, then include a file
                                 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                                 */
                                get_template_part( 'template-parts/content', get_post_format() );
                            ?>

                        <?php endwhile; // end of the loop. ?>

                        <ul class="pagination">
                            <?php yp_posts_navigation(); ?>
                        </ul>

                    <?php else : ?>

                        <?php get_template_part( 'template-parts/content', 'none' ); ?>

                    <?php endif; ?>

                </main>

                <?php get_sidebar(); ?>
            </div>
        </div>

<?php get_footer(); ?>
