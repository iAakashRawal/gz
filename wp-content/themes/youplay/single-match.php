<?php
/**
 * The template for displaying all single matches.
 *
 * @package Youplay
 */
$post = get_post(get_the_ID());

$side = strpos(yp_opts('single_match_layout', true), 'side-cont') !== false
                    ? 'left'
                    : (strpos(yp_opts('single_match_layout', true), 'cont-side') !== false
                      ? 'right'
                      : false);
$boxed_cont = yp_opts('single_match_boxed_cont', true);
$banner = strpos(yp_opts('single_match_layout', true), 'banner') !== false;
$banner = $banner && shortcode_exists( 'yp_banner' );
$banner_content = $post->post_excerpt;
$rev_slider = yp_opts('single_match_revslider', true) && function_exists('putRevSlider');
$rev_slider_alias = yp_opts('single_match_revslider_alias', true);

get_header();

if($rev_slider) {
    putRevSlider($rev_slider_alias);
    $banner = true;
}
?>

    <section class="content-wrap <?php echo ($banner?'':'no-banner'); ?>">
        <?php
            // check if layout with banner
            if ($banner && !$rev_slider) {
                $title = '<h1 class="entry-title mb-30">' . get_the_title() . '</h1>';
                if ($banner_content) {
                    $title = '
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                ' . $title . '
                            </div>
                        </div>';
                }
                $banner_parallax_speed_metabox = yp_opts('single_match_banner_parallax_speed_type', true) === 'custom';
                $banner_parallax_speed = yp_opts('single_match_banner_parallax_speed', $banner_parallax_speed_metabox);

                echo do_shortcode('[yp_banner img_src="' . yp_opts('single_match_banner_image', true) . '" img_size="1920x1080" banner_size="' . yp_opts('single_match_banner_size', true) . '" parallax="' . yp_opts('single_match_banner_parallax', true) . '" parallax_speed="' . $banner_parallax_speed . '" top_position="true"]'
                        . $title
                        . ($banner_content ? wp_kses_post($banner_content) : '')
                    . '[/yp_banner]');
            } else if (!$rev_slider) {
                the_title('<h1 class="text-center ' . ($boxed_cont ? 'container' : '') . ' entry-title">', '</h1>');
                if ( $banner_content ) {
                    echo '<div class="' . ($boxed_cont ? 'container' : '') . '">' . do_shortcode( wp_kses_post($banner_content) ) . '</div>';
                }
            }
        ?>

        <div class="<?php echo esc_attr($boxed_cont?'container':'container-fluid'); ?> youplay-news youplay-post">
            <div class="row">
                <?php $layout = yp_get_layout_data(); ?>

                <main class="<?php echo esc_attr($layout['content_class']); ?>">

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php get_template_part( 'template-parts/content', 'single-match' ); ?>

                        <?php
                            // If comments are open or we have at least one comment, load up the comment template
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;
                        ?>

                    <?php endwhile; // end of the loop. ?>

                </main>

                <?php get_sidebar(); ?>
            </div>
        </div>

<?php get_footer(); ?>
