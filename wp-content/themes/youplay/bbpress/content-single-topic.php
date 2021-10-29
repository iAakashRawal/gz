<?php

/**
 * Single Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div>

	<?php yp_bb_breadcrumb( false ); ?>

	<?php do_action( 'bbp_template_before_single_topic' ); ?>

	<?php if ( post_password_required() ) : ?>

		<?php bbp_get_template_part( 'form', 'protected' ); ?>

	<?php else : ?>

		<?php bbp_topic_tag_list( 0, array(
			'before' => '<div class="bbp-topic-tags mt-10 mb-20 pull-right">' . __( 'Tagged:', 'youplay' ) . '&nbsp;',
			'sep'    => ', ',
			'after'  => '</div>'
		) ); ?>

		<div class="clearfix"></div>

		<?php bbp_single_topic_description( array(
					'topic_id'  => bbp_get_topic_id(),
					'before'    => '<div class="alert">',
					'after'     => '</div>',
				) ); ?>

		<?php if ( bbp_show_lead_topic() ) : ?>

			<?php bbp_get_template_part( 'content', 'single-topic-lead' ); ?>

		<?php endif; ?>

		<?php if ( bbp_has_replies() ) : ?>

			<?php bbp_get_template_part( 'pagination', 'replies' ); ?>

			<?php bbp_get_template_part( 'loop',       'replies' ); ?>

			<?php bbp_get_template_part( 'pagination', 'replies' ); ?>

			<div class="clearfix"></div>

		<?php endif; ?>

		<?php bbp_get_template_part( 'form', 'reply' ); ?>

	<?php endif; ?>

	<?php do_action( 'bbp_template_after_single_topic' ); ?>

</div>
