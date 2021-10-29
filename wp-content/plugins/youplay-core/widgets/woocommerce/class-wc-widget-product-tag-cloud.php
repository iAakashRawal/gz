<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tag Cloud Widget
 *
 * @author   WooThemes
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @version  2.3.0
 * @extends  WC_Widget
 */
class yp_WC_Widget_Product_Tag_Cloud extends WC_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_product_tag_cloud';
		$this->widget_description = __( 'Your most used product tags in cloud format.', 'youplay-core' );
		$this->widget_id          = 'woocommerce_product_tag_cloud';
		$this->widget_name        = __( 'WooCommerce Product Tags', 'youplay-core' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Product Tags', 'youplay-core' ),
				'label' => __( 'Title', 'youplay-core' )
			)
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$current_taxonomy = $this->_get_current_taxonomy( $instance );

		if ( empty( $instance['title'] ) ) {
			$taxonomy = get_taxonomy( $current_taxonomy );
			$instance['title'] = $taxonomy->labels->name;
		}

		$this->widget_start( $args, $instance );

		echo '<div class="tagcloud block-content">';

		wp_tag_cloud( apply_filters( 'woocommerce_product_tag_cloud_widget_args', array(
			'taxonomy' => $current_taxonomy,
			'topic_count_text_callback' => array( $this, '_topic_count_text' ),
		) ) );

		echo '</div>';

		$this->widget_end( $args );
	}

	/**
	 * Return the taxonomy being displayed.
	 *
	 * @param  object $instance
	 * @return string
	 */
	public function _get_current_taxonomy( $instance ) {
		return 'product_tag';
	}

	/**
	 * Retuns topic count text.
	 *
	 * @since 2.6.0
	 * @param int $count
	 * @return string
	 */
	public function _topic_count_text( $count ) {
		/* translators: %s for product quantity, e.g. 1 product and 2 products */
		return sprintf( _n( '%s product', '%s products', $count, 'youplay-core' ), number_format_i18n( $count ) );
	}
}
