<?php
/**
 * The template for displaying bbPress pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Youplay
 */

$side = strpos(yp_opts('press_layout', true), 'side-cont') !== false
					? 'left'
					: (strpos(yp_opts('press_layout', true), 'cont-side') !== false
					  ? 'right'
					  : false);
$boxed_cont = yp_opts('press_boxed_cont', true);
$banner = strpos(yp_opts('press_layout', true), 'banner') !== false;
$banner = $banner && shortcode_exists( 'yp_banner' );

get_header();

?>

  <section class="content-wrap <?php echo ($banner?'':'no-banner'); ?>">
		<?php
			// check if layout with banner
			if ($banner) {
				if(bbp_is_single_user()) {
					$banner_cont = '
						<div class="youplay-user">
					    <a href="' . yp_get_avatar_url( get_avatar( bbp_get_displayed_user_field( 'user_email', 'raw' ), apply_filters( 'bbp_single_user_details_avatar_size', 1080 ) ) ) . '" class="angled-img image-popup" title="' . get_the_title() . '">
					      <div class="img">
					        ' . get_avatar( bbp_get_displayed_user_field( 'user_email', 'raw' ), apply_filters( 'bbp_single_user_details_avatar_size', 150 ) ) . '
					      </div>
					      <i class="fa fa-search-plus icon"></i>
					    </a>
					    <div class="user-data">
					      <h1 class="mt-0 h2 entry-title">' . get_the_title() . '</h1>
					      <!-- <div class="location"><i class="fa fa-map-marker"></i> Los Angeles</div> -->
					      <div class="activity">
					        <div>
					          <div class="num">' . bbp_get_user_display_role(). '</div>
					          <div class="title">' . __('Role', 'youplay') . '</div>
					        </div>
					        <div>
					          <div class="num">' . bbp_get_user_topic_count_raw() . '</div>
					          <div class="title">' . __('Topics', 'youplay') . '</div>
					        </div>
					        <div>
					          <div class="num">' . bbp_get_user_reply_count_raw() . '</div>
					          <div class="title">' . __('Replies', 'youplay') . '</div>
					        </div>
					      </div>
					    </div>
					  </div>';
				} else {
					$banner_cont = '<h1 class="h2 entry-title">' . get_the_title() . '</h1>';
				}
                $banner_parallax_speed_metabox = yp_opts('press_banner_parallax_speed_type', true) === 'custom';
                $banner_parallax_speed = yp_opts('buddypress_banner_parallax_speed', $banner_parallax_speed_metabox);
				echo do_shortcode('[yp_banner img_src="' . yp_opts('press_banner_image', true) . '" img_size="1920x1080" banner_size="' . yp_opts('press_banner_size', true) . '" parallax="' . yp_opts('press_banner_parallax', true) . '" parallax_speed="' . $banner_parallax_speed . '" top_position="true"]' . $banner_cont . '[/yp_banner]');
			} else {
				the_title('<h1 class="' . ($boxed_cont?'container':'') . '">', '</h1>');
			}
		?>

		<div class="<?php echo ($boxed_cont?'container':'container-fluid'); ?> youplay-content">
            <div class="row">
                <?php $layout = yp_get_layout_data(); ?>

    			<main class="<?php echo esc_attr($layout['content_class']); ?>">

    				<?php while ( have_posts() ) : the_post(); ?>

    					<?php get_template_part( 'template-parts/content', 'page' ); ?>

    					<?php
    						// If comments are open or we have at least one comment, load up the comment template
    						if ( comments_open() || get_comments_number() ) :
    							comments_template();
    						endif;
    					?>

    				<?php endwhile; // end of the loop. ?>

    			</main>

    			<?php get_sidebar( 'bbpress' ); ?>
            </div>
		</div>


<?php get_footer(); ?>
