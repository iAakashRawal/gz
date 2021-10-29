<?php
/**
 * @package Youplay
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('news-one'); ?>>
	<div class="entry-content description">
        <?php the_content(); ?>
        <div class="clearfix"></div>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'youplay' ),
				'after'  => '</div>',
			) );
		?>
	</div>

	<?php youplay_post_review(); ?>

	<?php youplay_post_tags(); ?>

	<?php youplay_post_meta(); ?>

	<?php do_action( 'sociality-sharing' ); ?>

	<footer class="entry-footer">
		<?php youplay_entry_footer(); ?>
	</footer>
</article>
