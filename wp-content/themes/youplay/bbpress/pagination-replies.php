<?php

/**
 * Pagination for pages of replies (when viewing a topic)
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php do_action( 'bbp_template_before_pagination_loop' ); ?>

<div class="pull-left">
	<div class="bbp-pagination-count">

		<?php bbp_topic_pagination_count(); ?>

	</div>

	<?php $page_links = bbp_get_topic_pagination_links(); ?>

  <div class="clearfix"></div>

  <?php if(is_array($page_links)): ?>
    <ul class='pagination mt-10 mb-10'>
      <?php foreach($page_links as $cur) : ?>
        <li <?php echo (strpos($cur, 'current') !== false ? 'class="active"' : ''); ?>>
          <?php echo wp_kses_post($cur); ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php do_action( 'bbp_template_after_pagination_loop' ); ?>
