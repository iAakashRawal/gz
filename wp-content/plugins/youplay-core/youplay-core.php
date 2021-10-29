<?php
/**
 * Plugin Name:  Youplay Core Plugin
 * Description:  Shortcodes and widgets for Youplay theme
 * Version:      1.1.1
 * Author:       nK
 * Author URI:   https://nkdev.info
 * License:      GPLv2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  youplay-core
 *
 * @package youplay-core
 */

// Make sure we don't expose any info if called directly.
if ( ! function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

if ( ! class_exists( 'Youplay_Core' ) ) :
    /**
     * Youplay_Core
     */
    class Youplay_Core {
        /**
         * The single class instance.
         *
         * @var $_instance
         */
        private static $_instance = null;

        /**
         * Main Instance
         * Ensures only one instance of this class exists in memory at any one time.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
                self::$_instance->init();
            }
            return self::$_instance;
        }

        /**
         * Path to the plugin directory
         *
         * @var $plugin_path
         */
        public $plugin_path;

        /**
         * URL to the plugin directory
         *
         * @var $plugin_url
         */
        public $plugin_url;

        /**
         * Init.
         */
        public function init() {
            $this->plugin_path = plugin_dir_path( __FILE__ );
            $this->plugin_url = plugin_dir_url( __FILE__ );

            load_plugin_textdomain( 'youplay-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

            require_once plugin_dir_path( __FILE__ ) . 'inc/extras.php';

            // Classes.
            require_once plugin_dir_path( __FILE__ ) . 'shortcodes/_all.php';
            require_once plugin_dir_path( __FILE__ ) . 'widgets/_all.php';
        }
    }
endif;

/**
 * Function works with the Youplay_Core class instance
 *
 * @return object Youplay_Core
 */
function youplay_core() {
    return youplay_Core::instance();
}
add_action( 'plugins_loaded', 'youplay_core' );
