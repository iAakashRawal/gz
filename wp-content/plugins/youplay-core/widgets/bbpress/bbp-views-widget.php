<?php
/**
 * bbPress Views Widget
 *
 * Adds a widget which displays the view list
 *
 * @since bbPress (r3020)
 *
 * @uses WP_Widget
 */
class yp_BBP_Views_Widget extends WP_Widget {

  /**
   * bbPress View Widget
   *
   * Registers the view widget
   *
   * @since bbPress (r3020)
   *
   * @uses apply_filters() Calls 'bbp_views_widget_options' with the
   *                        widget options
   */
  public function __construct() {
    $widget_ops = apply_filters( 'bbp_views_widget_options', array(
      'classname'   => 'widget_display_views',
      'description' => __( 'A list of registered optional topic views.', 'youplay-core' )
    ) );

    parent::__construct( false, __( '(bbPress) Topic Views List', 'youplay-core' ), $widget_ops );
  }

  /**
   * Register the widget
   *
   * @since bbPress (r3389)
   *
   * @uses register_widget()
   */
  public static function register_widget() {
    register_widget( 'yp_BBP_Views_Widget' );
  }

  /**
   * Displays the output, the view list
   *
   * @since bbPress (r3020)
   *
   * @param mixed $args Arguments
   * @param array $instance Instance
   * @uses apply_filters() Calls 'bbp_view_widget_title' with the title
   * @uses bbp_get_views() To get the views
   * @uses bbp_view_url() To output the view url
   * @uses bbp_view_title() To output the view title
   */
  public function widget( $args = array(), $instance = array() ) {

    // Only output widget contents if views exist
    if ( ! bbp_get_views() ) {
      return;
    }

    // Get widget settings
    $settings = $this->parse_settings( $instance );

    // Typical WordPress filter
    $settings['title'] = apply_filters( 'widget_title',          $settings['title'], $instance, $this->id_base );

    // bbPress filter
    $settings['title'] = apply_filters( 'bbp_view_widget_title', $settings['title'], $instance, $this->id_base );

    echo wp_kses_post($args['before_widget']);

    if ( !empty( $settings['title'] ) ) {
      echo wp_kses_post($args['before_title'] . $settings['title'] . $args['after_title']);
    } ?>

    <ul class="block-content">

      <?php foreach ( array_keys( bbp_get_views() ) as $view ) : ?>

        <li><a class="bbp-view-title" href="<?php bbp_view_url( $view ); ?>"><?php bbp_view_title( $view ); ?></a></li>

      <?php endforeach; ?>

    </ul>

    <?php echo wp_kses_post($args['after_widget']);
  }

  /**
   * Update the view widget options
   *
   * @since bbPress (r3020)
   *
   * @param array $new_instance The new instance options
   * @param array $old_instance The old instance options
   */
  public function update( $new_instance = array(), $old_instance = array() ) {
    $instance          = $old_instance;
    $instance['title'] = strip_tags( $new_instance['title'] );

    return $instance;
  }

  /**
   * Output the view widget options form
   *
   * @since bbPress (r3020)
   *
   * @param $instance Instance
   * @uses BBP_Views_Widget::get_field_id() To output the field id
   * @uses BBP_Views_Widget::get_field_name() To output the field name
   */
  public function form( $instance = array() ) {

    // Get widget settings
    $settings = $this->parse_settings( $instance ); ?>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:', 'youplay-core' ); ?>
        <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" />
      </label>
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
      'title' => ''
    ), 'view_widget_settings' );
  }
}
