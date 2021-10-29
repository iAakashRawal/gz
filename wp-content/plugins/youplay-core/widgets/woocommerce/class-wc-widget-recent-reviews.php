<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Recent Reviews Widget
 *
 * @author   WooThemes
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @version  2.3.0
 * @extends  WC_Widget
 */
class yp_WC_Widget_Recent_Reviews extends WC_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_recent_reviews';
		$this->widget_description = __( 'Display a list of your most recent reviews on your site.', 'youplay-core' );
		$this->widget_id          = 'woocommerce_recent_reviews';
		$this->widget_name        = __( 'WooCommerce Recent Reviews', 'youplay-core' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Recent Reviews', 'youplay-core' ),
				'label' => __( 'Title', 'youplay-core' )
			),
			'number' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 10,
				'label' => __( 'Number of reviews to show', 'youplay-core' )
			)
		);

		parent::__construct();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 *
	 * @return void
	 */
	 public function widget( $args, $instance ) {
		global $comments, $comment;

		if ( $this->get_cached_widget( $args ) ) {
			return;
		}

		ob_start();

		$number   = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : $this->settings['number']['std'];
		$comments = get_comments( array( 'number' => $number, 'status' => 'approve', 'post_status' => 'publish', 'post_type' => 'product' ) );

		if ( $comments ) {
			$this->widget_start( $args, $instance );

			echo '<div class="block-content p-0">';

			foreach ( (array) $comments as $comment ) {

				$_product = wc_get_product( $comment->comment_post_ID );

				$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );

				$rating_html = yp_get_rating( $rating );


				echo '<div class="row youplay-side-news">';

					echo '
					  <div class="col-xs-3 col-md-4">
					    <a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '" class="angled-img">
					      <div class="img">
					        ' . $_product->get_image() . '
					       </div>
					    </a>
					  </div>';

					echo '
						<div class="col-xs-9 col-md-8">
					    <h4 class="ellipsis"><a href="' . esc_url( get_comment_link( $comment->comment_ID ) ) . '" title="' . esc_attr($_product->get_title()) . '">' . $_product->get_title() . '</a></h4>
					    ' . $rating_html . '
					    <div class="price">' . sprintf( '<span class="reviewer">' . _x( 'by %1$s', 'by comment author', 'youplay-core' ) . '</span>', get_comment_author() ) . '</div>
					  </div>';

				echo '</div>';

			}

			echo '</div>';

			$this->widget_end( $args );
		}

		$content = ob_get_clean();

		echo wp_kses_post( $content );

		$this->cache_widget( $args, $content );
	}
}
