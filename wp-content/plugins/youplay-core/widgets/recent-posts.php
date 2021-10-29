<?php
/**
 * YP Recent Posts Widget Class
 */
class YP_Recent_Posts extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'yp_recent_posts', // Base ID
			__( '(Youplay) Recent Posts', 'youplay-core' ), // Name
			array( 'description' => esc_html__( 'List with recent posts.', 'youplay-core' ), ) // Args
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

      $the_query = new WP_Query( 'showposts=' . $instance['posts_count'] );
      while ($the_query -> have_posts()) : $the_query -> the_post();
      	$title = get_the_title();
      	$date = youplay_posted_on( true, $instance['show_date']?true:false, $instance['show_author']?true:false );
      	$img = youplay_post_thumbnail( true );

			  echo '
				  <div class="row youplay-side-news">
				    <div class="col-xs-3 col-md-4">
				      ' . $img . '
				    </div>
				    <div class="col-xs-9 col-md-8">
				      <h4 class="ellipsis"><a href="' . esc_url(get_permalink()) . '" title="' . esc_attr($title) . '">' . esc_html($title) . '</a></h4>
				      <span class="date">' . $date . '</span>
				    </div>
				  </div>';
      endwhile;

		echo '</div>';

		echo wp_kses_post($args['after_widget']);

		wp_reset_postdata();
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'youplay-core' );
		$posts_count = ! empty( $instance['posts_count'] ) ? $instance['posts_count'] : 3;
		$show_date = ! empty( $instance['show_date'] ) ? $instance['show_date'] : '';
		$show_author = ! empty( $instance['show_author'] ) ? $instance['show_author'] : '';
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:', 'youplay-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'posts_count' )); ?>"><?php _e( 'Posts Count:', 'youplay-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'posts_count' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'posts_count' )); ?>" type="text" value="<?php echo esc_attr( $posts_count ); ?>">
		</p>
		<p>
			<input id="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_date' )); ?>" type="checkbox" <?php checked($show_date, 'on'); ?>>
    		<label for="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>"><?php _e( 'Show Date', 'youplay-core' ); ?></label>
		</p>
		<p>
			<input id="<?php echo esc_attr($this->get_field_id( 'show_author' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_author' )); ?>" type="checkbox" <?php checked($show_author, 'on'); ?>>
    		<label for="<?php echo esc_attr($this->get_field_id( 'show_author' )); ?>"><?php _e( 'Show Author', 'youplay-core' ); ?></label>
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
		$instance['posts_count'] = ( ! empty( $new_instance['posts_count'] ) ) ? strip_tags( $new_instance['posts_count'] ) : '';
		$instance['show_date'] = $new_instance['show_date'];
		$instance['show_author'] = $new_instance['show_author'];

		return $instance;
	}

}

// register Recent Posts widget.
if ( ! function_exists( 'yp_widget_recent_posts' ) ) :
    function yp_widget_recent_posts() {
        register_widget( 'YP_Recent_Posts' );
    }
endif;
add_action( 'widgets_init', 'yp_widget_recent_posts' );
