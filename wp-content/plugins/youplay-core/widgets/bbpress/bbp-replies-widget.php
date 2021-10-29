<?php

/**
 * bbPress Replies Widget
 *
 * Adds a widget which displays the replies list
 *
 * @since bbPress (r2653)
 *
 * @uses WP_Widget
 */
class yp_BBP_Replies_Widget extends WP_Widget {

  /**
   * bbPress Replies Widget
   *
   * Registers the replies widget
   *
   * @since bbPress (r2653)
   *
   * @uses apply_filters() Calls 'bbp_replies_widget_options' with the
   *                        widget options
   */
  public function __construct() {
    $widget_ops = apply_filters( 'bbp_replies_widget_options', array(
      'classname'   => 'widget_display_replies',
      'description' => __( 'A list of the most recent replies.', 'youplay-core' )
    ) );

    parent::__construct( false, __( '(bbPress) Recent Replies', 'youplay-core' ), $widget_ops );
  }

  /**
   * Register the widget
   *
   * @since bbPress (r3389)
   *
   * @uses register_widget()
   */
  public static function register_widget() {
    register_widget( 'yp_BBP_Replies_Widget' );
  }

  /**
   * Displays the output, the replies list
   *
   * @since bbPress (r2653)
   *
   * @param mixed $args
   * @param array $instance
   * @uses apply_filters() Calls 'bbp_reply_widget_title' with the title
   * @uses bbp_get_reply_author_link() To get the reply author link
   * @uses bbp_get_reply_id() To get the reply id
   * @uses bbp_get_reply_url() To get the reply url
   * @uses bbp_get_reply_excerpt() To get the reply excerpt
   * @uses bbp_get_reply_topic_title() To get the reply topic title
   * @uses get_the_date() To get the date of the reply
   * @uses get_the_time() To get the time of the reply
   */
  public function widget( $args, $instance ) {

    // Get widget settings
    $settings = $this->parse_settings( $instance );

    // Typical WordPress filter
    $settings['title'] = apply_filters( 'widget_title',             $settings['title'], $instance, $this->id_base );

    // bbPress filter
    $settings['title'] = apply_filters( 'bbp_replies_widget_title', $settings['title'], $instance, $this->id_base );

    // Note: private and hidden forums will be excluded via the
    // bbp_pre_get_posts_normalize_forum_visibility action and function.
    $widget_query = new WP_Query( array(
      'post_type'           => bbp_get_reply_post_type(),
      'post_status'         => array( bbp_get_public_status_id(), bbp_get_closed_status_id() ),
      'posts_per_page'      => (int) $settings['max_shown'],
      'ignore_sticky_posts' => true,
      'no_found_rows'       => true,
    ) );

    // Bail if no replies
    if ( ! $widget_query->have_posts() ) {
      return;
    }

    echo wp_kses_post($args['before_widget']);

    if ( !empty( $settings['title'] ) ) {
      echo wp_kses_post($args['before_title'] . $settings['title'] . $args['after_title']);
    } ?>

    <ul class="block-content">

      <?php while ( $widget_query->have_posts() ) : $widget_query->the_post(); ?>

        <li>

          <?php

          // Verify the reply ID
          $reply_id   = bbp_get_reply_id( $widget_query->post->ID );
          $reply_link = '<a class="bbp-reply-topic-title" href="' . esc_url( bbp_get_reply_url( $reply_id ) ) . '" title="' . esc_attr( bbp_get_reply_excerpt( $reply_id, 50 ) ) . '">' . bbp_get_reply_topic_title( $reply_id ) . '</a>';

          // Only query user if showing them
          if ( ! empty( $settings['show_user'] ) ) :
            $author_link = bbp_get_reply_author_link( array( 'post_id' => $reply_id, 'type' => 'both', 'size' => 14 ) );
          else :
            $author_link = false;
          endif;

          // Reply author, link, and timestamp
          if ( ! empty( $settings['show_date'] ) && !empty( $author_link ) ) :

            // translators: 1: reply author, 2: reply link, 3: reply timestamp
            printf( _x( '%1$s on %2$s %3$s', 'widgets', 'youplay-core' ), $author_link, $reply_link, '<div>' . bbp_get_time_since( get_the_time( 'U' ) ) . '</div>' );

          // Reply link and timestamp
          elseif ( ! empty( $settings['show_date'] ) ) :

            // translators: 1: reply link, 2: reply timestamp
            printf( _x( '%1$s %2$s',         'widgets', 'youplay-core' ), $reply_link,  '<div>' . bbp_get_time_since( get_the_time( 'U' ) ) . '</div>'              );

          // Reply author and title
          elseif ( !empty( $author_link ) ) :

            // translators: 1: reply author, 2: reply link
            printf( _x( '%1$s on %2$s',      'widgets', 'youplay-core' ), $author_link, $reply_link                                                                 );

          // Only the reply title
          else :

            // translators: 1: reply link
            printf( _x( '%1$s',              'widgets', 'youplay-core' ), $reply_link                                                                               );

          endif;

          ?>

        </li>

      <?php endwhile; ?>

    </ul>

    <?php echo wp_kses_post($args['after_widget']);

    // Reset the $post global
    wp_reset_postdata();
  }

  /**
   * Update the reply widget options
   *
   * @since bbPress (r2653)
   *
   * @param array $new_instance The new instance options
   * @param array $old_instance The old instance options
   */
  public function update( $new_instance = array(), $old_instance = array() ) {
    $instance              = $old_instance;
    $instance['title']     = strip_tags( $new_instance['title'] );
    $instance['show_date'] = (bool) $new_instance['show_date'];
    $instance['show_user'] = (bool) $new_instance['show_user'];
    $instance['max_shown'] = (int) $new_instance['max_shown'];

    return $instance;
  }

  /**
   * Output the reply widget options form
   *
   * @since bbPress (r2653)
   *
   * @param $instance Instance
   * @uses BBP_Replies_Widget::get_field_id() To output the field id
   * @uses BBP_Replies_Widget::get_field_name() To output the field name
   */
  public function form( $instance = array() ) {

    // Get widget settings
    $settings = $this->parse_settings( $instance ); ?>

    <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:', 'youplay-core' ); ?> <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" /></label></p>
    <p><label for="<?php echo esc_attr($this->get_field_id( 'max_shown' )); ?>"><?php _e( 'Maximum replies to show:', 'youplay-core' ); ?> <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'max_shown' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'max_shown' )); ?>" type="text" value="<?php echo esc_attr( $settings['max_shown'] ); ?>" /></label></p>
    <p><label for="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>"><?php _e( 'Show post date:', 'youplay-core' ); ?> <input type="checkbox" id="<?php echo esc_attr($this->get_field_id( 'show_date' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_date' )); ?>" <?php checked( true, $settings['show_date'] ); ?> value="1" /></label></p>
    <p><label for="<?php echo esc_attr($this->get_field_id( 'show_user' )); ?>"><?php _e( 'Show reply author:', 'youplay-core' ); ?> <input type="checkbox" id="<?php echo esc_attr($this->get_field_id( 'show_user' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'show_user' )); ?>" <?php checked( true, $settings['show_user'] ); ?> value="1" /></label></p>

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
      'title'     => __( 'Recent Replies', 'youplay-core' ),
      'max_shown' => 5,
      'show_date' => false,
      'show_user' => false
    ),
    'replies_widget_settings' );
  }
}
