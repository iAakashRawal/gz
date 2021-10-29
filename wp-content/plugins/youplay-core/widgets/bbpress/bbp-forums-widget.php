<?php

/**
 * bbPress Forum Widget
 *
 * Adds a widget which displays the forum list
 *
 * @since bbPress (r2653)
 *
 * @uses WP_Widget
 */
class yp_BBP_Forums_Widget extends WP_Widget {

  /**
   * bbPress Forum Widget
   *
   * Registers the forum widget
   *
   * @since bbPress (r2653)
   *
   * @uses apply_filters() Calls 'bbp_forums_widget_options' with the
   *                        widget options
   */
  public function __construct() {
    $widget_ops = apply_filters( 'bbp_forums_widget_options', array(
      'classname'   => 'widget_display_forums',
      'description' => __( 'A list of forums with an option to set the parent.', 'youplay-core' )
    ) );

    parent::__construct( false, __( '(bbPress) Forums List', 'youplay-core' ), $widget_ops );
  }

  /**
   * Register the widget
   *
   * @since bbPress (r3389)
   *
   * @uses register_widget()
   */
  public static function register_widget() {
    register_widget( 'yp_BBP_Forums_Widget' );
  }

  /**
   * Displays the output, the forum list
   *
   * @since bbPress (r2653)
   *
   * @param mixed $args Arguments
   * @param array $instance Instance
   * @uses apply_filters() Calls 'bbp_forum_widget_title' with the title
   * @uses get_option() To get the forums per page option
   * @uses current_user_can() To check if the current user can read
   *                           private() To resety name
   * @uses bbp_has_forums() The main forum loop
   * @uses bbp_forums() To check whether there are more forums available
   *                     in the loop
   * @uses bbp_the_forum() Loads up the current forum in the loop
   * @uses bbp_forum_permalink() To display the forum permalink
   * @uses bbp_forum_title() To display the forum title
   */
  public function widget( $args, $instance ) {

    // Get widget settings
    $settings = $this->parse_settings( $instance );

    // Typical WordPress filter
    $settings['title'] = apply_filters( 'widget_title',           $settings['title'], $instance, $this->id_base );

    // bbPress filter
    $settings['title'] = apply_filters( 'bbp_forum_widget_title', $settings['title'], $instance, $this->id_base );

    // Note: private and hidden forums will be excluded via the
    // bbp_pre_get_posts_normalize_forum_visibility action and function.
    $widget_query = new WP_Query( array(
      'post_type'           => bbp_get_forum_post_type(),
      'post_parent'         => $settings['parent_forum'],
      'post_status'         => bbp_get_public_status_id(),
      'posts_per_page'      => get_option( '_bbp_forums_per_page', 50 ),
      'ignore_sticky_posts' => true,
      'no_found_rows'       => true,
      'orderby'             => 'menu_order title',
      'order'               => 'ASC'
    ) );

    // Bail if no posts
    if ( ! $widget_query->have_posts() ) {
      return;
    }

    echo wp_kses_post($args['before_widget']);

    if ( !empty( $settings['title'] ) ) {
      echo wp_kses_post($args['before_title'] . $settings['title'] . $args['after_title']);
    } ?>

    <ul class="block-content">

      <?php while ( $widget_query->have_posts() ) : $widget_query->the_post(); ?>

        <li><a class="bbp-forum-title" href="<?php bbp_forum_permalink( $widget_query->post->ID ); ?>"><?php bbp_forum_title( $widget_query->post->ID ); ?></a></li>

      <?php endwhile; ?>

    </ul>

    <?php echo wp_kses_post($args['after_widget']);

    // Reset the $post global
    wp_reset_postdata();
  }

  /**
   * Update the forum widget options
   *
   * @since bbPress (r2653)
   *
   * @param array $new_instance The new instance options
   * @param array $old_instance The old instance options
   */
  public function update( $new_instance, $old_instance ) {
    $instance                 = $old_instance;
    $instance['title']        = strip_tags( $new_instance['title'] );
    $instance['parent_forum'] = sanitize_text_field( $new_instance['parent_forum'] );

    // Force to any
    if ( !empty( $instance['parent_forum'] ) && !is_numeric( $instance['parent_forum'] ) ) {
      $instance['parent_forum'] = 'any';
    }

    return $instance;
  }

  /**
   * Output the forum widget options form
   *
   * @since bbPress (r2653)
   *
   * @param $instance Instance
   * @uses BBP_Forums_Widget::get_field_id() To output the field id
   * @uses BBP_Forums_Widget::get_field_name() To output the field name
   */
  public function form( $instance ) {

    // Get widget settings
    $settings = $this->parse_settings( $instance ); ?>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:', 'youplay-core' ); ?>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" />
      </label>
    </p>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id( 'parent_forum' )); ?>"><?php _e( 'Parent Forum ID:', 'youplay-core' ); ?>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'parent_forum' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'parent_forum' )); ?>" type="text" value="<?php echo esc_attr( $settings['parent_forum'] ); ?>" />
      </label>

      <br />

      <small><?php _e( '"0" to show only root - "any" to show all', 'youplay-core' ); ?></small>
    </p>

    <?php
  }

  /**
   * Merge the widget settings into defaults array.
   *
   * @since bbPress (r4802)
   *
   * @param $instance Instance
   * @uses bbp_parse_args() To merge widget settings into defaults
   */
  public function parse_settings( $instance = array() ) {
    return bbp_parse_args( $instance, array(
      'title'        => __( 'Forums', 'youplay-core' ),
      'parent_forum' => 0
    ), 'forum_widget_settings' );
  }
}
