<?php
/**
 * The template for displaying all single posts.
 *
 * @package Youplay
 */

$side = strpos(yp_opts('single_post_layout', true), 'side-cont') !== false
					? 'left'
					: (strpos(yp_opts('single_post_layout', true), 'cont-side') !== false
					  ? 'right'
					  : false);
$boxed_cont = yp_opts('single_post_boxed_cont', true);
$banner = strpos(yp_opts('single_post_layout', true), 'banner') !== false;
$banner_cont = yp_opts('single_post_banner_cont', true);
$rev_slider = yp_opts('single_post_revslider', true) && function_exists('putRevSlider');
$rev_slider_alias = yp_opts('single_post_revslider_alias', true);

get_header();

$banner = $banner && shortcode_exists( 'yp_banner' );

if($rev_slider) {
	putRevSlider($rev_slider_alias);
	$banner = true;
}

?>

  	<section class="content-wrap <?php echo ($banner?'':'no-banner'); ?>">
		<?php
			// check if layout with banner
			if ($banner && !$rev_slider) {
                $banner_parallax_speed_metabox = yp_opts('single_post_banner_parallax_speed_type', true) === 'custom';
                $banner_parallax_speed = yp_opts('single_post_banner_parallax_speed', $banner_parallax_speed_metabox);
				echo do_shortcode('[yp_banner img_src="' . yp_opts('single_post_banner_image', true) . '" img_size="1920x1080" banner_size="' . yp_opts('single_post_banner_size', true) . '" parallax="' . yp_opts('single_post_banner_parallax', true) . '" parallax_speed="' . $banner_parallax_speed . '" top_position="true"]' . ($banner_cont?wp_kses_post($banner_cont):'<h1 class="entry-title h2">' . get_the_title() . '</h1>') . '[/yp_banner]');
			} else if(!$rev_slider) {
				the_title('<h1 class="' . ($boxed_cont?'container':'') . ' entry-title">', '</h1>');
			}
		?>

		<div class="<?php echo esc_attr($boxed_cont?'container':'container-fluid'); ?> youplay-news youplay-post">
            <div class="row">
                <?php $layout = yp_get_layout_data(); ?>

    			<main class="<?php echo esc_attr($layout['content_class']); ?>">

    				<?php while ( have_posts() ) : the_post(); ?>

    					<?php get_template_part( 'template-parts/content', 'single' ); ?>

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
