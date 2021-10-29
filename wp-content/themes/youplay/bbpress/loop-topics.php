<?php

/**
 * Topics Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div class="pull-right">
  <?php bbp_forum_subscription_link(); ?>
</div>
<div class="clearfix"></div>

<?php do_action( 'bbp_template_before_topics_loop' ); ?>

<ul id="bbp-forum-<?php bbp_forum_id(); ?>" class="youplay-forum mr-10 mb-0">

	<li class="header">

		<ul>
      <li class="cell-icon"></li>
      <li class="cell-info"><?php _e( 'Topic', 'youplay' ); ?></li>
      <li class="cell-topic-count"><?php _e( 'Voices', 'youplay' ); ?></li>
      <li class="cell-reply-count"><?php bbp_show_lead_topic() ? _e( 'Replies', 'youplay' ) : _e( 'Posts', 'youplay' ); ?></li>
      <li class="cell-freshness"><?php _e( 'Freshness', 'youplay' ); ?></li>
    </ul>

	</li>

	<li class="body">

		<?php while ( bbp_topics() ) : bbp_the_topic(); ?>

			<?php bbp_get_template_part( 'loop', 'single-topic' ); ?>

		<?php endwhile; ?>

	</li>

</ul><!-- #bbp-forum-<?php bbp_forum_id(); ?> -->

<?php do_action( 'bbp_template_after_topics_loop' ); ?>
