<?php
/**
 * Plugin Name:  nK Themes Helper
 * Description:  Helper for nK themes
 * Version:      1.7.3
 * Author:       nK
 * Author URI:   https://nkdev.info
 * License:      GPLv2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  nk-themes-helper
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


/**
 * NK Theme Helper Class
 */
class NKTH {
    /**
     * The single class instance.
     *
     * @var $instance
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
     * Plugin name
     *
     * @var $plugin_name
     */
    public $plugin_name;

    /**
     * Plugin version
     *
     * @var $plugin_version
     */
    public $plugin_version;

    /**
     * Plugin slug
     *
     * @var $plugin_slug
     */
    public $plugin_slug;

    /**
     * Plugin name sanitized
     *
     * @var $plugin_name_sanitized
     */
    public $plugin_name_sanitized;

    /**
     * Upload dir path
     *
     * @var $upload_dir
     */
    public $upload_dir;

    /**
     * Compile folder name
     *
     * @var $compile_folder
     */
    public $compile_folder;

    /**
     * Compile folder path
     *
     * @var $compile_path
     */
    public $compile_path;

    /**
     * Compile folder url
     *
     * @var $compile_url
     */
    public $compile_url;

    /**
     * NK constructor.
     */
    public function __construct() {
        $this->init_options();
        $this->init_hooks();

        // load textdomain.
        load_plugin_textdomain( 'nk-themes-helper', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    /**
     * Init options
     */
    public function init_options() {
        $this->plugin_path = plugin_dir_path( __FILE__ );
        $this->plugin_url  = plugin_dir_url( __FILE__ );

        // prepare variables for compilation.
        $this->compile_folder = 'nk-custom-styles';
        $this->upload_dir     = wp_upload_dir();
        $this->compile_path   = trailingslashit( $this->upload_dir['basedir'] ) . $this->compile_folder;
        $this->compile_url    = trailingslashit( $this->upload_dir['baseurl'] ) . $this->compile_folder;

        // create compilation folder.
        if ( ! is_dir( trailingslashit( $this->compile_path ) ) ) {
            // @codingStandardsIgnoreLine
            mkdir( $this->compile_path, 0777, true );
        }

        // include helper files.
        $this->include_dependencies();

        // clear caches.
        $this->clear_expired_caches();
    }

    /**
     * Init hooks
     */
    public function init_hooks() {
        add_action( 'admin_init', array( $this, 'admin_init' ) );
    }

    /**
     * Activation hook
     */
    public function activation_hook() {
        flush_rewrite_rules();
    }

    /**
     * Deactivation hook
     */
    public function deactivation_hook() {
        flush_rewrite_rules();
    }

    /**
     * Admin init
     */
    public function admin_init() {
        // get current plugin data.
        $data                        = get_plugin_data( __FILE__ );
        $this->plugin_name           = $data['Name'];
        $this->plugin_version        = $data['Version'];
        $this->plugin_slug           = plugin_basename( __FILE__, '.php' );
        $this->plugin_name_sanitized = basename( __FILE__, '.php' );

        // init updater class to plugin updates check.
        $this->updater();
    }

    /**
     * Include dependencies
     */
    private function include_dependencies() {
        require_once $this->plugin_path . 'class-demo-importer.php';
        require_once $this->plugin_path . 'class-twitter.php';
        require_once $this->plugin_path . 'class-instagram.php';
        require_once $this->plugin_path . 'class-updater.php';
        require_once $this->plugin_path . 'class-theme-dashboard.php';
        require_once $this->plugin_path . 'class-portfolio.php';
        require_once $this->plugin_path . 'class-custom-post-type.php';

        require_once $this->plugin_path . 'plugins-updater/acf-pro.php';
        require_once $this->plugin_path . 'plugins-updater/vc.php';
    }


    /**
     * Additional Classes
     */

    /**
     * Demo Importer
     *
     * @return NKTH_Demo_Importer|object
     */
    public function demo_importer() {
        return NKTH_Demo_Importer::instance();
    }

    /**
     * Twitter
     *
     * @return NKTH_Twitter|null
     */
    public function twitter() {
        return NKTH_Twitter::instance();
    }

    /**
     * Instagram
     *
     * @return NKTH_Instagram|null
     */
    public function instagram() {
        return NKTH_Instagram::instance();
    }

    /**
     * Updater
     *
     * @return NKTH_Updater|null
     */
    public function updater() {
        return NKTH_Updater::instance();
    }

    /**
     * Theme Dashboard
     *
     * @param array $options - options.
     *
     * @return NKTH_Theme_Dashboard|null
     */
    public function theme_dashboard( $options = array() ) {
        return NKTH_Theme_Dashboard::instance( $options );
    }

    /**
     * Portfolio
     *
     * @param array $options - options.
     *
     * @return NKTH_Portfolio|null
     */
    public function portfolio( $options = array() ) {
        return NKTH_Portfolio::instance( $options );
    }

    /**
     * Custom post type
     *
     * @param string|array $post_type_names - type names.
     * @param array        $options - options.
     *
     * @return NKTH_Custom_Post_Type
     */
    public function custom_post_type( $post_type_names, $options = array() ) {
        return new NKTH_Custom_Post_Type( $post_type_names, $options );
    }


    /**
     * Add shortcode
     *
     * @param string $tag - shortcode name.
     * @param string $func - shortcode function.
     *
     * @return mixed
     */
    public function reg_shortcode( $tag, $func ) {
        return add_shortcode( $tag, $func );
    }

    /**
     * Options
     */

    /**
     * Get options
     *
     * @return mixed
     */
    private function get_options() {
        $options_slug = 'nk_theme_helper_options';
         // phpcs:ignore
        return unserialize( get_option( $options_slug, 'a:0:{}' ) );
    }

    /**
     * Update option
     *
     * @param string $name - option name.
     * @param mixed  $value - option value.
     */
    public function update_option( $name, $value ) {
        $options_slug                           = 'nk_theme_helper_options';
        $options                                = self::get_options();
        $options[ self::sanitize_key( $name ) ] = $value;
         // phpcs:ignore
        update_option( $options_slug, serialize( $options ) );
    }

    /**
     * Get option value
     *
     * @param string $name - option name.
     * @param mixed  $default - default value.
     *
     * @return null
     */
    public function get_option( $name, $default = null ) {
        $options = self::get_options();
        $name    = self::sanitize_key( $name );
        return isset( $options[ $name ] ) ? $options[ $name ] : $default;
    }

    /**
     * Sanitize key
     *
     * @param string $key - key.
     *
     * @return mixed
     */
    private function sanitize_key( $key ) {
        return preg_replace( '/[^A-Za-z0-9\_]/i', '', str_replace( array( '-', ':' ), '_', $key ) );
    }


    /**
     * Cache
     * $time in seconds
     */

    /**
     * Get all caches
     *
     * @return mixed
     */
    private function get_caches() {
        $caches_slug = 'cache';
        return $this->get_option( $caches_slug, array() );
    }

    /**
     * Set cache
     *
     * @param string $name - cache name.
     * @param mixed  $value - cache value.
     * @param int    $time - expiration in seconds.
     */
    public function set_cache( $name, $value, $time = 3600 ) {
        if ( ! $time || $time <= 0 ) {
            return;
        }
        $caches_slug = 'cache';
        $caches      = self::get_caches();

        $caches[ self::sanitize_key( $name ) ] = array(
            'value'   => $value,
            'expired' => time() + ( (int) $time ? $time : 0 ),
        );
        $this->update_option( $caches_slug, $caches );
    }

    /**
     * Get cache.
     *
     * @param string $name - cache name.
     * @param mixed  $default - default value.
     *
     * @return null
     */
    public function get_cache( $name, $default = null ) {
        $caches = self::get_caches();
        $name   = self::sanitize_key( $name );
        return isset( $caches[ $name ]['value'] ) ? $caches[ $name ]['value'] : $default;
    }

    /**
     * Clear cache
     *
     * @param string $name - cache name.
     */
    public function clear_cache( $name ) {
        $caches_slug = 'cache';
        $caches      = self::get_caches();
        $name        = self::sanitize_key( $name );
        if ( isset( $caches[ $name ] ) ) {
            $caches[ $name ] = null;
            $this->update_option( $caches_slug, $caches );
        }
    }

    /**
     * Clear all expired caches
     */
    public function clear_expired_caches() {
        $caches_slug = 'cache';
        $caches      = self::get_caches();
        foreach ( $caches as $k => $cache ) {
            if ( isset( $cache ) && isset( $cache['expired'] ) && $cache['expired'] < time() ) {
                $caches[ $k ] = null;
            }
        }
        $this->update_option( $caches_slug, $caches );
    }


    /**
     * File Operations
     */

    /**
     * Put contents
     *
     * @param string $file_name - file name.
     * @param string $content - file content.
     *
     * @return mixed
     */
    public function file_put_contents( $file_name = null, $content = '' ) {
        // @codingStandardsIgnoreLine
        return file_put_contents( $file_name, $content );
    }

    /**
     * Get contents
     *
     * @param string $file_name - file name.
     *
     * @return mixed
     */
    public function file_get_contents( $file_name = null ) {
         // phpcs:ignore
        return file_get_contents( $file_name );
    }

    /**
     * File exists
     *
     * @param string $file_name - file name.
     *
     * @return mixed
     */
    public function file_exists( $file_name = null ) {
        return file_exists( $file_name );
    }

    /**
     * Get file version (based on file change date)
     *
     * @param string $file_name - file name.
     *
     * @return mixed
     */
    public function get_file_version( $file_name = null ) {
        return filemtime( $file_name );
    }

    /**
     * Unlink (remove file)
     *
     * @param string $file_name - file name.
     *
     * @return mixed
     */
    public function unlink( $file_name = null ) {
        // @codingStandardsIgnoreLine
        return unlink( $file_name );
    }


    /**
     * Compile SCSS and Less
     */

    /**
     * Get compiled CSS path
     *
     * @param string $file_name - file name.
     *
     * @return bool|string
     */
    public function get_compiled_css_path( $file_name = 'nk-custom-style.css' ) {
        $file_path = trailingslashit( $this->compile_path ) . $file_name;
        return $this->file_exists( $file_path ) ? $file_path : false;
    }

    /**
     * Get compiled CSS url
     *
     * @param string $file_name - file name.
     *
     * @return bool|string
     */
    public function get_compiled_css_url( $file_name = 'nk-custom-style.css' ) {
        return $this->get_compiled_css_path( $file_name ) ? trailingslashit( $this->compile_url ) . $file_name : false;
    }

    /**
     * Get compiled CSS version
     *
     * @param string $file_name - file name.
     *
     * @return bool|mixed
     */
    public function get_compiled_css_version( $file_name = 'nk-custom-style.css' ) {
        $file_path = $this->get_compiled_css_path( $file_name );
        return $file_path ? $this->get_file_version( $file_path ) : false;
    }

    /**
     * Compise SCSS
     *
     * @param string $file_name - file name to compile.
     * @param string $path - path to compilation folder.
     * @param string $custom_scss - string with custom SCSS to compile.
     *
     * @return bool|string
     */
    public function scss( $file_name = 'nk-custom-style.css', $path = null, $custom_scss = '' ) {
        $stored_scss = get_option( $this->compile_folder . $file_name );

        // When cached, return false.
        if ( $stored_scss === $custom_scss ) {
            return false;
        }

        require_once $this->plugin_path . 'vendor/scssphp/scss.inc.php';

        // if there is no cached version - let's compile!
        $scss = new ScssPhp\ScssPhp\Compiler();
        $scss->addImportPath( $path );
        $scss->setFormatter( 'ScssPhp\ScssPhp\Formatter\Compressed' );

        // Find all SCSS files and remove unsupported math.div module.
        $scss_files = glob( trailingslashit( $path ) . '**/*.scss' );

        foreach ( $scss_files as $scss_file_path ) {
            // phpcs:ignore
            $file_contents = file_get_contents( $scss_file_path );

            if ( $file_contents ) {
                // find module include.
                $file_contents = str_replace( '@use "sass:math";', '', $file_contents );

                // find math.div calls.
                preg_match_all( '/math\.div(?=\()(?:(?=.*?\((?!.*?\1)(.*\)(?!.*\2).*))(?=.*?\)(?!.*?\2)(.*)).)+?.*?(?=\1)[^(]*(?=\2$)/ms', $file_contents, $file_contents_calls );
                if ( ! empty( $file_contents_calls[0] ) ) {
                    foreach ( $file_contents_calls[0] as $content ) {
                        $new_file_contents = $content;
                        $new_file_contents = str_replace( 'math.div', '', $new_file_contents );
                        $new_file_contents = preg_replace( '/\,/', ' /', $new_file_contents, 1 );

                        $file_contents = str_replace( $content, $new_file_contents, $file_contents );
                    }
                }

                // phpcs:ignore
                file_put_contents( $scss_file_path, $file_contents );
            }
        }

        // Compile scss.
        $result = $scss->compile( $custom_scss );

        $this->file_put_contents( trailingslashit( $this->compile_path ) . $file_name, $result );

        // cache.
        update_option( $this->compile_folder . $file_name, $custom_scss );

        return $result;
    }

    /**
     * Compile SCSS
     *
     * @param string $file_name - file name to compile.
     * @param string $src_file - source file.
     * @param array  $variables - less variables.
     *
     * @return bool|string
     */
    public function less( $file_name = 'nk-custom-style.css', $src_file = null, $variables = array() ) {
        require_once $this->plugin_path . 'vendor/lessphp/lessc.inc.php';

        $stored_less = get_option( $this->compile_folder . $file_name );

        // cache.
        if ( $stored_less === $variables ) {
            return false;
        } else {
            update_option( $this->compile_folder . $file_name, $variables );
        }

        $less = new Less_Parser();
        $less->parseFile( $src_file );
        $less->ModifyVars( $variables );
        $result = $less->getCss();

        $this->file_put_contents( trailingslashit( $this->compile_path ) . $file_name, $result );

        return $result;
    }

    /**
     * This function transforms the php.ini notation for numbers (like '2M') to an integer
     *
     * @param string $size - string to transform.
     *
     * @return bool|int|string
     */
    public function let_to_num( $size ) {
        $l   = substr( $size, -1 );
        $ret = substr( $size, 0, -1 );
        switch ( strtoupper( $l ) ) {
            case 'P':
                $ret *= 1024;
                // no break.
            case 'T':
                $ret *= 1024;
                // no break.
            case 'G':
                $ret *= 1024;
                // no break.
            case 'M':
                $ret *= 1024;
                // no break.
            case 'K':
                $ret *= 1024;
                // no break.
        }
        return $ret;
    }
}

/**
 * Function works with the NKTH class instance
 *
 * @return object NKTH
 */
function nkth() {
    return NKTH::instance();
}
add_action( 'plugins_loaded', 'nkth' );

register_deactivation_hook( __FILE__, array( nkth(), 'deactivation_hook' ) );
register_activation_hook( __FILE__, array( nkth(), 'activation_hook' ) );

/**
 * Fallback
 */
// phpcs:ignore
function nk_theme() {
    return nkth();
}
