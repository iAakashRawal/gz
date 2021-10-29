<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Youplay
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-content">
        <?php the_content(); ?>
        <div class="clearfix"></div>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'youplay' ),
				'after'  => '</div>',
			) );
		?>
	</div>

	<footer class="entry-footer">
		<?php edit_post_link( esc_html__( 'Edit', 'youplay' ), '<span class="edit-link">', '</span>' ); ?>
	</footer>
</article>
