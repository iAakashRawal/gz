<?php
/**
 * bbPress Login Widget
 *
 * Adds a widget which displays the login form
 *
 * @since bbPress (r2827)
 *
 * @uses WP_Widget
 */
class yp_BBP_Login_Widget extends WP_Widget {

  /**
   * bbPress Login Widget
   *
   * Registers the login widget
   *
   * @since bbPress (r2827)
   *
   * @uses apply_filters() Calls 'bbp_login_widget_options' with the
   *                        widget options
   */
  public function __construct() {
    $widget_ops = apply_filters( 'bbp_login_widget_options', array(
      'classname'   => 'bbp_widget_login',
      'description' => __( 'A simple login form with optional links to sign-up and lost password pages.', 'youplay-core' )
    ) );

    parent::__construct( false, __( '(bbPress) Login Widget', 'youplay-core' ), $widget_ops );
  }

  /**
   * Register the widget
   *
   * @since bbPress (r3389)
   *
   * @uses register_widget()
   */
  public static function register_widget() {
    register_widget( 'yp_BBP_Login_Widget' );
  }

  /**
   * Displays the output, the login form
   *
   * @since bbPress (r2827)
   *
   * @param mixed $args Arguments
   * @param array $instance Instance
   * @uses apply_filters() Calls 'bbp_login_widget_title' with the title
   * @uses get_template_part() To get the login/logged in form
   */
  public function widget( $args = array(), $instance = array() ) {

    // Get widget settings
    $settings = $this->parse_settings( $instance );

    // Typical WordPress filter
    $settings['title'] = apply_filters( 'widget_title', $settings['title'], $instance, $this->id_base );

    // bbPress filters
    $settings['title']    = apply_filters( 'bbp_login_widget_title',    $settings['title'],    $instance, $this->id_base );
    $settings['register'] = apply_filters( 'bbp_login_widget_register', $settings['register'], $instance, $this->id_base );
    $settings['lostpass'] = apply_filters( 'bbp_login_widget_lostpass', $settings['lostpass'], $instance, $this->id_base );

    echo wp_kses_post($args['before_widget']);

    if ( !empty( $settings['title'] ) ) {
      echo wp_kses_post($args['before_title'] . $settings['title'] . $args['after_title']);
    }

    if ( !is_user_logged_in() ) : ?>

      <form method="post" action="<?php bbp_wp_login_action( array( 'context' => 'login_post' ) ); ?>" class="block-content">

        <p><?php _e( 'Username', 'youplay-core' ); ?>: </p>
        <div class="youplay-input mb-0">
          <input type="text" name="log" value="<?php bbp_sanitize_val( 'user_login', 'text' ); ?>" size="20" id="user_login" tabindex="<?php bbp_tab_index(); ?>" />
        </div>

        <p><?php _e( 'Password', 'youplay-core' ); ?>: </p>
        <div class="youplay-input">
          <input type="password" name="pwd" value="<?php bbp_sanitize_val( 'user_pass', 'password' ); ?>" size="20" id="user_pass" tabindex="<?php bbp_tab_index(); ?>" />
        </div>

        <div class="youplay-checkbox mb-10">
          <input type="checkbox" name="rememberme" value="forever" <?php checked( bbp_get_sanitize_val( 'rememberme', 'checkbox' ), true, true ); ?> id="rememberme" tabindex="<?php bbp_tab_index(); ?>" />
          <label for="rememberme"><?php _e( 'Remember Me', 'youplay-core' ); ?></label>
        </div>

        <?php do_action( 'login_form' ); ?>

        <button type="submit" name="user-submit" id="user-submit" tabindex="<?php bbp_tab_index(); ?>" class="btn btn-default btn-sm mr-0"><?php _e( 'Log In', 'youplay-core' ); ?></button>

        <?php bbp_user_login_fields(); ?>

        <?php if ( !empty( $settings['register'] ) || !empty( $settings['lostpass'] ) ) : ?>

          <div class="bbp-login-links">

            <?php if ( !empty( $settings['register'] ) ) : ?>

              <a href="<?php echo esc_url( $settings['register'] ); ?>" title="<?php esc_attr_e( 'Register', 'youplay-core' ); ?>" class="bbp-register-link"><?php _e( 'Register', 'youplay-core' ); ?></a>

            <?php endif; ?>

            <?php if ( !empty( $settings['lostpass'] ) ) : ?>

              <a href="<?php echo esc_url( $settings['lostpass'] ); ?>" title="<?php esc_attr_e( 'Lost Password', 'youplay-core' ); ?>" class="bbp-lostpass-link"><?php _e( 'Lost Password', 'youplay-core' ); ?></a>

            <?php endif; ?>

          </div>

        <?php endif; ?>

      </form>

    <?php else : ?>

      <div class="block-content">
        <a href="<?php bbp_user_profile_url( bbp_get_current_user_id() ); ?>" class="angled-img pull-left">
          <div class="img"><?php echo get_avatar( bbp_get_current_user_id(), '60' ); ?></div>
        </a>
        <div class="pull-right">
          <h4 class="mt-0"><?php bbp_user_profile_link( bbp_get_current_user_id() ); ?></h4>
          <?php bbp_logout_link(); ?>
        </div>
        <div class="clearfix"></div>
      </div>

    <?php endif;

    echo wp_kses_post($args['after_widget']);
  }

  /**
   * Update the login widget options
   *
   * @since bbPress (r2827)
   *
   * @param array $new_instance The new instance options
   * @param array $old_instance The old instance options
   */
  public function update( $new_instance, $old_instance ) {
    $instance             = $old_instance;
    $instance['title']    = strip_tags( $new_instance['title'] );
    $instance['register'] = esc_url_raw( $new_instance['register'] );
    $instance['lostpass'] = esc_url_raw( $new_instance['lostpass'] );

    return $instance;
  }

  /**
   * Output the login widget options form
   *
   * @since bbPress (r2827)
   *
   * @param $instance Instance
   * @uses BBP_Login_Widget::get_field_id() To output the field id
   * @uses BBP_Login_Widget::get_field_name() To output the field name
   */
  public function form( $instance = array() ) {

    // Get widget settings
    $settings = $this->parse_settings( $instance ); ?>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:', 'youplay-core' ); ?>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>" /></label>
    </p>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id( 'register' )); ?>"><?php _e( 'Register URI:', 'youplay-core' ); ?>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'register' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'register' )); ?>" type="text" value="<?php echo esc_url( $settings['register'] ); ?>" /></label>
    </p>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id( 'lostpass' )); ?>"><?php _e( 'Lost Password URI:', 'youplay-core' ); ?>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'lostpass' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'lostpass' )); ?>" type="text" value="<?php echo esc_url( $settings['lostpass'] ); ?>" /></label>
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
      'title'    => '',
      'register' => '',
      'lostpass' => ''
    ), 'login_widget_settings' );
  }
}
