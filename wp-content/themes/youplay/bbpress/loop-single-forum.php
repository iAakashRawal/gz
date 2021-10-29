<?php

/**
 * Forums Loop - Single Forum
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" <?php bbp_forum_class(); ?>>

	<li class="cell-icon pt-30"><i class="fa fa-folder-open-o"></i></li>

	<li class="cell-info">

		<?php if ( bbp_is_user_home() && bbp_is_subscriptions() ) : ?>

			<span class="bbp-row-actions">

				<?php do_action( 'bbp_theme_before_forum_subscription_action' ); ?>

				<?php bbp_forum_subscription_link( array( 'before' => '', 'subscribe' => '+', 'unsubscribe' => '&times;' ) ); ?>

				<?php do_action( 'bbp_theme_after_forum_subscription_action' ); ?>

			</span>

		<?php endif; ?>

		<?php do_action( 'bbp_theme_before_forum_title' ); ?>

		<a class="title h4" href="<?php bbp_forum_permalink(); ?>"><?php bbp_forum_title(); ?></a>

		<?php do_action( 'bbp_theme_after_forum_title' ); ?>

		<?php do_action( 'bbp_theme_before_forum_description' ); ?>

		<div class="entry-content description"><?php bbp_forum_content(); ?></div>

		<?php do_action( 'bbp_theme_after_forum_description' ); ?>

		<?php do_action( 'bbp_theme_before_forum_sub_forums' ); ?>

		<?php bbp_list_forums(array (
				'before'              => '<ul class="forums-list">',
				'after'               => '</ul>',
				'link_before'         => '<li><i class="fa fa-folder-open-o"></i> ',
				'link_after'          => '</li>',
				'show_topic_count'    => false,
				'show_reply_count'    => false,
			)); ?>

		<?php do_action( 'bbp_theme_after_forum_sub_forums' ); ?>

		<?php bbp_forum_row_actions(); ?>

	</li>

	<li class="cell-topic-count"><?php bbp_forum_topic_count(); ?></li>

	<li class="cell-reply-count"><?php bbp_show_lead_topic() ? bbp_forum_reply_count() : bbp_forum_post_count(); ?></li>

	<li class="cell-freshness">

		<?php do_action( 'bbp_theme_before_forum_freshness_link' ); ?>

		<?php bbp_forum_freshness_link(); ?>

		<?php do_action( 'bbp_theme_after_forum_freshness_link' ); ?>

		<p>

			<?php do_action( 'bbp_theme_before_topic_author' ); ?>

			<?php bbp_author_link( array( 'post_id' => bbp_get_forum_last_active_id(), 'size' => 25 ) ); ?>

			<?php do_action( 'bbp_theme_after_topic_author' ); ?>

		</p>
	</li>

</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->
