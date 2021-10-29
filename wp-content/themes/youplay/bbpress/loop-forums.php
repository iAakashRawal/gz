<?php

/**
 * Forums Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_forums_loop' ); ?>

<ul id="forums-list-<?php bbp_forum_id(); ?>" class="youplay-forum mr-10 mb-0">

	<li class="header">

		<ul>
      <li class="cell-icon"></li>
      <li class="cell-info"><?php _e( 'Forum', 'youplay' ); ?></li>
      <li class="cell-topic-count"><?php _e( 'Voices', 'youplay' ); ?></li>
      <li class="cell-reply-count"><?php bbp_show_lead_topic() ? _e( 'Replies', 'youplay' ) : _e( 'Posts', 'youplay' ); ?></li>
      <li class="cell-freshness"><?php _e( 'Freshness', 'youplay' ); ?></li>
    </ul>

	</li>

	<li class="body">

		<?php while ( bbp_forums() ) : bbp_the_forum(); ?>

			<?php bbp_get_template_part( 'loop', 'single-forum' ); ?>

		<?php endwhile; ?>

	</li><!-- .bbp-body -->

</ul><!-- .forums-directory -->

<?php do_action( 'bbp_template_after_forums_loop' ); ?>
