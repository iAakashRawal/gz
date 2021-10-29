<?php
/**
 * @package Youplay
 */
?>
<div id="post-<?php the_ID(); ?>" <?php post_class("item col-lg-3 col-md-4 col-sm-6"); ?>>
  <div class="news-one">
    <?php
      $hexagon = youplay_post_review_hexagon(true);
      youplay_post_thumbnail(false, $hexagon);
    ?>
    <a class="angled-img" href="<?php echo esc_url( get_permalink() ); ?>">
      <span class="img img-offset"></span>
      <div class="bottom-info align-center">
        <?php the_title( '<h2 class="entry-title h3">', '</h2>' ); ?>
        
        <?php if ( 'post' == get_post_type() ) : ?>
          <span class="date">
            <?php youplay_posted_on( false, true, false ); ?>
          </span>
        <?php endif; ?>
      </div>
    </a>
  </div>
</div>