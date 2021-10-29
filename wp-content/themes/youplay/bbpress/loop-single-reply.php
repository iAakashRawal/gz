<?php

/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<ul class="youplay-forum">
    <li>
        <div id="post-<?php bbp_reply_id(); ?>">
          <div class="top">
        	  <span class="author h5 pull-left">
            	<a href="<?php echo bbp_get_reply_author_url(); ?>"><?php echo bbp_get_reply_author(); ?></a>
            	<small class="ml-10">
            		<?php echo bbp_get_user_display_role( bbp_get_reply_author_id() ); ?>
            	</small>
        	  </span>
        		<div class="pull-right date">
        			<span class="mr-15">
        				<i class="fa fa-calendar"></i> <?php bbp_reply_post_date(); ?>
        			</span>
        			<a href="<?php bbp_reply_url(); ?>">#<?php bbp_reply_id(); ?></a>
        		</div>

        		<?php if ( bbp_is_single_user_replies() ) : ?>

        			<span>
        				<?php _e( 'in reply to: ', 'youplay' ); ?>
        				<a class="bbp-topic-permalink" href="<?php bbp_topic_permalink( bbp_get_reply_topic_id() ); ?>"><?php bbp_topic_title( bbp_get_reply_topic_id() ); ?></a>
        			</span>

        		<?php endif; ?>

          </div>

          <div class="w-100 pull-left">
        		<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>

        	  <a class="avatar" href="<?php echo bbp_get_reply_author_url(); ?>">
        			<?php echo bbp_get_reply_author_avatar(0, 100); ?>
        	  </a>

        		<?php if ( bbp_is_user_keymaster() ) : ?>

        			<?php do_action( 'bbp_theme_before_reply_author_admin_details' ); ?>

        			<div class="bbp-reply-ip small text-muted mt-5"><?php bbp_author_ip( bbp_get_reply_id() ); ?></div>

        			<?php do_action( 'bbp_theme_after_reply_author_admin_details' ); ?>

        		<?php endif; ?>

        		<?php do_action( 'bbp_theme_after_reply_author_details' ); ?>
          </div>

          <div class="reply clearfix">
            <div class="text">
        			<?php do_action( 'bbp_theme_before_reply_content' ); ?>

              <?php bbp_reply_content(); ?>

        			<?php do_action( 'bbp_theme_after_reply_content' ); ?>
            </div>
          </div>

          <div class="clearfix text-right">
        		<?php do_action( 'bbp_theme_before_reply_admin_links' ); ?>

        		<?php bbp_reply_admin_links(); ?>

        		<?php do_action( 'bbp_theme_after_reply_admin_links' ); ?>
          </div>
       </div>
    </li><!-- #post-<?php bbp_reply_id(); ?> -->
</ul>
