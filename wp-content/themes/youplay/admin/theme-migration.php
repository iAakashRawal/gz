<?php

/**
 * Class Youplay_Migration
 */
class Youplay_Migration {
    /**
     * The version.
     *
     * @var string
     */
    protected $version = '3.7.4';

    /**
     * Initial version.
     *
     * @var string
     */
    protected $initial_version = '';

    /**
     * The theme version as stored in the db.
     *
     * @var string
     */
    protected $db_version;

    /**
     * Youplay_Migration constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
    }

    /**
     * Init
     */
    public function init() {
        $this->get_versions();
        $this->run_migrations();
        $this->update_version();
    }

    /**
     * Get theme version to work with
     */
    protected function get_versions() {
        // Add initial version so we know the first time a user activated the theme
        if ( ! get_option( 'nk_theme_youplay_initial_version' ) ) {
            update_option( 'nk_theme_youplay_initial_version', $this->version );
        }
        $this->initial_version = get_option( 'nk_theme_youplay_initial_version' );

        // Get user theme version.
        $this->db_version = get_option( 'nk_theme_youplay_version' );
        $this->db_version = $this->db_version ? : '3.5.4'; // Version is required and was added in v3.5.4
    }

    /**
     * Run migration process
     */
    protected function run_migrations() {
        /**
         * UPDATE: 3.6.0
         */
        if (
            version_compare( '3.6.0', $this->db_version, '>' ) &&
            version_compare( '3.6.0', $this->initial_version, '>=' )
        ) {
            // Don't use WP_Query on the admin side https://core.trac.wordpress.org/ticket/18408 .
            $posts_query = get_posts(
                array(
                    'post_type'       => 'post',
                    // phpcs:ignore
                    'posts_per_page'  => -1,
                    'showposts'       => -1,
                    'paged'           => -1,
                )
            );
            foreach ( $posts_query as $post ) {
                $like_count = get_post_meta( $post->ID, "_post_like_count", true );

                // remove old post likes and add new one if exists.
                if ( $like_count ) {
                    update_post_meta( $post->ID, "_post_likes_count", $like_count );
                    delete_post_meta( $post->ID, "_post_like_count", $like_count );
                }
            }
        }
    }

    /**
     * Update theme version in DB.
     */
    protected function update_version() {
        // Do not update the version in the db
        // if the current version is greater than the one we're updating to.
        if ( version_compare( $this->version, $this->db_version, '<=' ) ) {
            return;
        }

        update_option( 'nk_theme_youplay_version', $this->version );
    }
}
new Youplay_Migration();
