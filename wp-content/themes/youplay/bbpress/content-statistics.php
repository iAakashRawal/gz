<?php

/**
 * Statistics Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Get the statistics
$stats = bbp_get_statistics(); ?>

<ul role="main" class="block-content text-left">

	<?php do_action( 'bbp_before_statistics' ); ?>

	<li>
		<?php _e( 'Registered Users', 'youplay' ); ?>
		<strong class="pull-right"><?php echo esc_html( $stats['user_count'] ); ?></strong>
	</li>

	<li class="mt-4">
		<?php _e( 'Forums', 'youplay' ); ?>
		<strong class="pull-right"><?php echo esc_html( $stats['forum_count'] ); ?></strong>
	</li>

	<li class="mt-4">
		<?php _e( 'Topics', 'youplay' ); ?>
		<strong class="pull-right"><?php echo esc_html( $stats['topic_count'] ); ?></strong>
	</li>

	<li class="mt-4">
		<?php _e( 'Replies', 'youplay' ); ?>
		<strong class="pull-right"><?php echo esc_html( $stats['reply_count'] ); ?></strong>
	</li>

	<li class="mt-4">
		<?php _e( 'Topic Tags', 'youplay' ); ?>
		<strong class="pull-right"><?php echo esc_html( $stats['topic_tag_count'] ); ?></strong>
	</li>

	<?php if ( !empty( $stats['empty_topic_tag_count'] ) ) : ?>

		<li class="mt-4">
			<?php _e( 'Empty Topic Tags', 'youplay' ); ?>
			<strong class="pull-right"><?php echo esc_html( $stats['empty_topic_tag_count'] ); ?></strong>
		</li>

	<?php endif; ?>

	<?php if ( !empty( $stats['topic_count_hidden'] ) ) : ?>

		<li class="mt-4">
			<?php _e( 'Hidden Topics', 'youplay' ); ?>
			<strong class="pull-right">
				<abbr title="<?php echo esc_attr( $stats['hidden_topic_title'] ); ?>"><?php echo esc_html( $stats['topic_count_hidden'] ); ?></abbr>
			</strong>
		</li>

	<?php endif; ?>

	<?php if ( !empty( $stats['reply_count_hidden'] ) ) : ?>

		<li class="mt-4">
			<?php _e( 'Hidden Replies', 'youplay' ); ?>
			<strong class="pull-right">
				<abbr title="<?php echo esc_attr( $stats['hidden_reply_title'] ); ?>"><?php echo esc_html( $stats['reply_count_hidden'] ); ?></abbr>
			</strong>
		</li>

	<?php endif; ?>

	<?php do_action( 'bbp_after_statistics' ); ?>

</ul>

<?php unset( $stats );
