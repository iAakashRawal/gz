<?php
/**
 * Instagram API
 * How to generate access token (or use google) - http://instagram.pixelunion.net/
 *
 * Example:
 *   nkth()->instagram()->set_data(array(
 *       'access_token' => '2955800576.e6b770c.298a4ea57ed94bf6be27544740bd10eb',
 *       'user_id'      => '10365980',
 *       'cachetime'    => 3700
 *   ));
 *   $instagram = nkth()->instagram()->get_instagram(10);
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class NKTH_Instagram
 */
class NKTH_Instagram {
    /**
     * The single class instance.
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Main Instance
     * Ensures only one instance of this class exists in memory at any one time.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * NKTH_Instagram constructor.
     */
    private function __construct() {
        /* We do nothing here! */
    }

    /**
     * Access token
     *
     * @var string
     */
    private $access_token;

    /**
     * User ID
     *
     * @var string
     */
    private $user_id;


    /**
     * Hash
     *
     * @var string
     */
    private $hash;

    /**
     * Cache name
     *
     * @var string
     */
    private $cache_name;


    /**
     * Cache time
     *
     * @var int
     */
    private $cache_time;

    /**
     * Error
     *
     * @var object
     */
    private $error;

    /**
     * Set data
     *
     * @param array $data - data.
     */
    public function set_data( $data = array() ) {
        $default            = array(
            'access_token' => '',
            'user_id'      => '',
            'cachetime'    => 3700,
        );
        $data               = array_merge( $default, $data );
        $this->access_token = $data['access_token'];
        $this->user_id      = $data['user_id'];

        $this->cache_time = (int) $data['cachetime'];

        // create names to store in database.
        $this->hash = md5(
            // phpcs:ignore
            json_encode(
                array(
                    $this->access_token,
                    $this->user_id,
                    $this->cache_time,
                )
            )
        );
        $this->cache_name = 'instagram-backup-' . $this->hash;
    }

    /**
     * Get instagram data
     *
     * @param int $count - items count.
     *
     * @return bool|mixed
     */
    public function get_instagram( $count = 6 ) {
        $cache_name = $this->cache_name . $count;
        $result     = nkth()->get_cache( $cache_name );
        if ( $result ) {
            return $result;
        }

        $result = $this->get( 'https://api.instagram.com/v1/users/' . $this->user_id . '/media/recent/?access_token=' . $this->access_token . '&count=' . $count );

        $is_error = ( $result && isset( $result->meta ) && isset( $result->meta->error_message ) ? $result->meta : false );
        if ( ! $is_error && $result ) {
            // Fetch succeeded.
            nkth()->set_cache( $cache_name, $result->data, $this->cache_time );
            return $result->data;
        } else {
            if ( $is_error ) {
                $this->set_error( $is_error );
            }
            nkth()->clear_cache( $cache_name );
            return false;
        }
    }

    /**
     * Get API url
     *
     * @param string $url - requested url.
     *
     * @return bool|mixed
     */
    public function get( $url ) {
        // Make Requests.
        $options_buf = wp_remote_get( $url );

        if ( ! is_wp_error( $options_buf ) && isset( $options_buf['body'] ) ) {
            return json_decode( $options_buf['body'] );
        } else {
            return false;
        }
    }

    /**
     * Has error
     *
     * @return bool
     */
    public function has_error() {
        return ! empty( $this->error );
    }

    /**
     * Get error
     *
     * @return object
     */
    public function get_error() {
        return $this->error;
    }

    /**
     * Set error
     *
     * @param object $error - error.
     */
    public function set_error( $error ) {
        $err_obj          = new stdClass();
        $err_obj->message = isset( $error->error_message ) ? $error->error_message : __( 'Something went wrong.', 'nk-themes-helper' );
        $err_obj->type    = isset( $error->error_type ) ? $error->error_type : 'error';
        $this->error      = $err_obj;
    }
}
