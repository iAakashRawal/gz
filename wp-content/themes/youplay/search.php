<?php
/**
 * The template for displaying search results pages.
 *
 * @package Youplay
 */

$side = strpos(yp_opts('search_page_layout', true), 'side-cont') !== false
					? 'left'
					: (strpos(yp_opts('search_page_layout', true), 'cont-side') !== false
					  ? 'right'
					  : false);
$boxed_cont = yp_opts('search_page_boxed_cont', true);
$banner = strpos(yp_opts('search_page_layout', true), 'banner') !== false;
$banner = $banner && shortcode_exists( 'yp_banner' );

get_header(); ?>

    <section class="content-wrap <?php echo ($banner?'':'no-banner'); ?>">
        <?php
            // check if layout with banner
            if ($banner) {
                echo do_shortcode('[yp_banner img_src="' . yp_opts('search_page_banner_image', true) . '" img_size="1920x1080" banner_size="' . yp_opts('search_page_banner_size', true) . '" parallax="' . yp_opts('search_page_banner_parallax', true) . '" parallax_speed="' . yp_opts('search_page_banner_parallax_speed', true) . '" top_position="true"]<h1 class="entry-title h2">' . sprintf( esc_html__( 'Search Results for: %s', 'youplay' ), '<span>' . get_search_query() . '</span>' ) . '</h1>[/yp_banner]');
            } else {
                echo '<h1 class="' . ($boxed_cont?'container':'') . ' entry-title mb-0">' . sprintf( esc_html__( 'Search Results for: %s', 'youplay' ), '<span>' . get_search_query() . '</span>' ) . '</h1>';
            }
        ?>

        <div class="<?php echo esc_attr($boxed_cont?'container':'container-fluid'); ?> youplay-content youplay-news">
            <div class="row">
                <?php $layout = yp_get_layout_data(); ?>

                <main class="<?php echo esc_attr($layout['content_class']); ?>">

                    <?php if ( have_posts() ) : ?>

                        <?php while ( have_posts() ) : the_post(); ?>

                            <?php
    							/**
    							 * Run the loop for the search to output the results.
    							 * If you want to overload this in a child theme then include a file
    							 * called content-search.php and that will be used instead.
    							 */
    							get_template_part( 'template-parts/content', 'search' );
                            ?>

                        <?php endwhile; // end of the loop. ?>

                        <?php yp_posts_navigation(); ?>

                    <?php else : ?>

                        <?php get_template_part( 'template-parts/content', 'none' ); ?>

                    <?php endif; ?>

                </main>

                <?php get_sidebar(); ?>
            </div>
        </div>

<?php get_footer(); ?>
