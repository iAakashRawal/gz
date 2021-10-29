<?php
/**
 * YP Instagram Widget Class
 */
class YP_Instagram extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'yp_instagram', // Base ID
			__( '(Youplay) Instagram', 'youplay-core' ), // Name
			array( 'description' => esc_html__( 'Instagram images list.', 'youplay-core' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo wp_kses_post($args['before_widget']);
		if ( ! empty( $instance['title'] ) ) {
			echo wp_kses_post($args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title']);
		}
		echo '<div class="block-content p-0">';
        echo do_shortcode('[nk_instagram count="' . $instance['images_count'] . '"]');
        echo '</div>';

        echo wp_kses_post($args['after_widget']);
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Instagram', 'youplay-core' );
		$images_count = ! empty( $instance['images_count'] ) ? $instance['images_count'] : 6;
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:', 'youplay-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'images_count' )); ?>"><?php _e( 'Images Count:', 'youplay-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'images_count' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'images_count' )); ?>" type="text" value="<?php echo esc_attr( $images_count ); ?>">
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['images_count'] = ( ! empty( $new_instance['images_count'] ) ) ? strip_tags( $new_instance['images_count'] ) : '';

		return $instance;
	}

}

// register Instagram widget
if ( ! function_exists( 'yp_widget_instagram' ) ) :
    function yp_widget_instagram() {
        register_widget( 'YP_Instagram' );
    }
endif;
add_action( 'widgets_init', 'yp_widget_instagram' );
