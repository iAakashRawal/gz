<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$verified = wc_review_is_from_verified_owner( $comment->comment_ID );

?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review" <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

	<article id="comment-<?php comment_ID(); ?>">

		<div class="review-avatar pull-left">
			<?php echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '90' ), '' ); ?>
		</div>

		<div class="review-cont clearfix">
            <?php
            /**
             * The woocommerce_review_before_comment_meta hook.
             *
             * @hooked woocommerce_review_display_rating - 10
             */
            do_action( 'woocommerce_review_before_comment_meta', $comment );

            /**
			 * The woocommerce_review_meta hook.
			 *
			 * @hooked woocommerce_review_display_meta - 10
			 */
			do_action( 'woocommerce_review_meta', $comment );

			do_action( 'woocommerce_review_before_comment_text', $comment ); ?>

			<div class="review-text" itemprop="description">
                <?php
                /**
    			 * The woocommerce_review_comment_text hook
    			 *
    			 * @hooked woocommerce_review_display_comment_text - 10
    			 */
    			do_action( 'woocommerce_review_comment_text', $comment );
                ?>
			</div>

			<?php do_action( 'woocommerce_review_after_comment_text', $comment ); ?>

		</div>
	</article>
