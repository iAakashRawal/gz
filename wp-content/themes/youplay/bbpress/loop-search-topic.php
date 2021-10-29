<?php

/**
 * Search Loop - Single Topic
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<ul id="bbp-topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

	<li class="cell-icon pt-30">
		<?php if(bbp_is_topic_spam()): ?>
			<i class="fa fa-times-circle-o"></i>
		<?php elseif(bbp_is_topic_sticky()): ?>
			<i class="fa fa-thumb-tack"></i>
		<?php elseif(bbp_is_topic_closed()): ?>
			<i class="fa fa-lock"></i>
		<?php else: ?>
			<i class="fa fa-folder-open-o"></i>
		<?php endif; ?>
	</li>

	<li class="cell-info">

		<?php if ( bbp_is_user_home() ) : ?>

			<?php if ( bbp_is_favorites() ) : ?>

				<span class="bbp-row-actions">

					<?php do_action( 'bbp_theme_before_topic_favorites_action' ); ?>

					<?php bbp_topic_favorite_link( array( 'before' => '', 'favorite' => '+', 'favorited' => '&times;' ) ); ?>

					<?php do_action( 'bbp_theme_after_topic_favorites_action' ); ?>

				</span>

			<?php elseif ( bbp_is_subscriptions() ) : ?>

				<span class="bbp-row-actions">

					<?php do_action( 'bbp_theme_before_topic_subscription_action' ); ?>

					<?php bbp_topic_subscription_link( array( 'before' => '', 'subscribe' => '+', 'unsubscribe' => '&times;' ) ); ?>

					<?php do_action( 'bbp_theme_after_topic_subscription_action' ); ?>

				</span>

			<?php endif; ?>

		<?php endif; ?>

		<?php do_action( 'bbp_theme_before_topic_title' ); ?>

		<a class="title h4" href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>

		<?php do_action( 'bbp_theme_after_topic_title' ); ?>

		<div class="entry-content description">

			<?php do_action( 'bbp_theme_before_topic_meta' ); ?>

			<?php do_action( 'bbp_theme_before_topic_started_by' ); ?>

      <?php printf( __( 'Started by: %1$s', 'youplay' ), bbp_get_topic_author_link( array( 'size' => '15' ) ) ); ?>

			<?php do_action( 'bbp_theme_after_topic_started_by' ); ?>

			<?php if ( !bbp_is_single_forum() || ( bbp_get_topic_forum_id() !== bbp_get_forum_id() ) ) : ?>

				<?php do_action( 'bbp_theme_before_topic_started_in' ); ?>

				<span class="bbp-topic-started-in"><?php printf( __( 'in: <a href="%1$s">%2$s</a>', 'youplay' ), bbp_get_forum_permalink( bbp_get_topic_forum_id() ), bbp_get_forum_title( bbp_get_topic_forum_id() ) ); ?></span>

				<?php do_action( 'bbp_theme_after_topic_started_in' ); ?>

			<?php endif; ?>

		</div>

		<?php do_action( 'bbp_theme_after_topic_meta' ); ?>

		<div class="forums-list"><?php bbp_topic_pagination(); ?></div>

		<?php bbp_topic_row_actions(); ?>

	</li>

	<li class="cell-topic-count"><?php bbp_topic_voice_count(); ?></li>

	<li class="cell-reply-count"><?php bbp_show_lead_topic() ? bbp_topic_reply_count() : bbp_topic_post_count(); ?></li>

	<li class="cell-freshness">
		<?php do_action( 'bbp_theme_before_topic_freshness_link' ); ?>

		<?php bbp_topic_freshness_link(); ?>

		<?php do_action( 'bbp_theme_after_topic_freshness_link' ); ?>

		<p>

			<?php do_action( 'bbp_theme_before_topic_freshness_author' ); ?>

			<?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'size' => 25 ) ); ?>

			<?php do_action( 'bbp_theme_after_topic_freshness_author' ); ?>

		</p>
	</li>

</ul><!-- #bbp-topic-<?php bbp_topic_id(); ?> -->
