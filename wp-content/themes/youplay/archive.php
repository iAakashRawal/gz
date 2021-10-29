<?php
/**
 * The template for displaying archive pages.
 *
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
				echo do_shortcode('[yp_banner img_src="' . yp_opts('archive_banner_image', true) . '" img_size="1920x1080" banner_size="' . yp_opts('archive_banner_size', true) . '" parallax="' . yp_opts('archive_banner_parallax', true) . '" parallax_speed="' . yp_opts('archive_banner_parallax_speed') . '" top_position="true"]<h1 class="h2 entry-title">' . get_the_archive_title() . '</h1>[/yp_banner]');
			} else {
				the_archive_title( '<h1 class="' . ($boxed_cont?'container':'') . ' entry-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description ' . ($boxed_cont?'container':'') . '">', '</div>' );
			}
		?>

		<div class="<?php echo esc_attr(($boxed_cont?'container ':'container-fluid ') . $no_padding . ' youplay-news youplay-content'); ?>">
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
