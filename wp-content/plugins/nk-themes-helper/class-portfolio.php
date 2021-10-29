<?php
/**
 * Portfolio custom post type
 *
 * Example:
 *
 *   // init
 *   nkth()->portfolio(array(
 *      'categories' => true,
 *      'tags' => true
 *   ));
 *
 *   // get available categories
 *   nkth()->portfolio()->get_categories('compact');
 *
 *   // get available tags
 *   nkth()->portfolio()->get_tags('compact');
 *
 *   // get array with all portfolio items
 *   nkth()->portfolio()->get_portfolio_items('compact');
 *
 * @package nk-themes-helper
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class NKTH_Portfolio
 */
class NKTH_Portfolio {
    /**
     * The single class instance.
     *
     * @var object
     */
    private static $instance = null;

    /**
     * Main Instance
     * Ensures only one instance of this class exists in memory at any one time.
     *
     * @param array $options - options.
     *
     * @return NKTH_Theme_Dashboard|object
     */
    public static function instance( $options = array() ) {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
            self::$instance->setup_options( $options );
            self::$instance->init_hooks();
        }
        return self::$instance;
    }

    /**
     * Categories
     *
     * @var array
     */
    private $categories;

    /**
     * Tags
     *
     * @var array
     */
    private $tags;

    /**
     * Setup options
     *
     * @param array $options - options.
     */
    private function setup_options( $options = array() ) {
        $options = array_merge(
            array(
                'categories' => true,
                'tags'       => true,
            ),
            $options
        );

        $this->categories = $options['categories'];
        $this->tags       = $options['tags'];
    }

    /**
     * Init hooks
     */
    private function init_hooks() {
        // enqueue admin styles.
        add_action( 'admin_init', array( $this, 'enqueue_admin_styles' ) );

        // add portfolio.
        add_action( 'init', array( $this, 'register_portfolio_post_type' ) );

        // add categories.
        if ( $this->categories ) {
            add_action( 'init', array( $this, 'register_categories_taxonomy' ) );
        }

        // add tags.
        if ( $this->tags ) {
            add_action( 'init', array( $this, 'register_tags_taxonomy' ) );
        }

        // add messages.
        add_filter( 'post_updated_messages', array( $this, 'get_messages' ) );

        // add custom column with thumbnail.
        add_filter( 'manage_portfolio_posts_columns', array( $this, 'portfolio_columns' ) );
        add_action( 'manage_portfolio_posts_custom_column', array( $this, 'portfolio_custom_columns' ), 10, 2 );
    }

    /**
     * Enqueue admin styles
     */
    public function enqueue_admin_styles() {
        global $typenow;

        // phpcs:ignore
        if ( empty( $typenow ) && isset( $_GET['post'] ) ) {
            // phpcs:ignore
            $post = isset( $_GET['vatomi_item_id'] ) ? sanitize_text_field( wp_unslash( $_GET['vatomi_item_id'] ) ) : null;

            if ( $post && isset( $post->post_type ) ) {
                // phpcs:ignore
                $typenow = $post->post_type;
            }
        }
        if ( is_admin() && 'portfolio' === $typenow ) {
            wp_enqueue_style( 'nk-portfolio-admin', nkth()->plugin_url . 'assets/css/portfolio.min.css', array(), '1.7.3' );
            wp_style_add_data( 'nk-portfolio-admin', 'rtl', 'replace' );
            wp_style_add_data( 'nk-portfolio-admin', 'suffix', '.min' );
        }
    }

    /**
     * Register custom post type Portfolio
     */
    public function register_portfolio_post_type() {
        register_post_type(
            'portfolio',
            apply_filters(
                // phpcs:ignore
                'nk_portfolio_params',
                array(
                    'labels'          => array(
                        'name'               => esc_html__( 'Portfolio', 'nk-themes-helper' ),
                        'singular_name'      => esc_html__( 'Portfolio Item', 'nk-themes-helper' ),
                        'menu_name'          => esc_html_x( 'Portfolio', 'admin menu', 'nk-themes-helper' ),
                        'name_admin_bar'     => esc_html_x( 'Portfolio Item', 'add new on admin bar', 'nk-themes-helper' ),
                        'add_new'            => esc_html__( 'Add New Item', 'nk-themes-helper' ),
                        'add_new_item'       => esc_html__( 'Add New Portfolio Item', 'nk-themes-helper' ),
                        'new_item'           => esc_html__( 'Add New Portfolio Item', 'nk-themes-helper' ),
                        'edit_item'          => esc_html__( 'Edit Portfolio Item', 'nk-themes-helper' ),
                        'view_item'          => esc_html__( 'View Item', 'nk-themes-helper' ),
                        'all_items'          => esc_html__( 'All Portfolio Items', 'nk-themes-helper' ),
                        'search_items'       => esc_html__( 'Search Portfolio', 'nk-themes-helper' ),
                        'parent_item_colon'  => esc_html__( 'Parent Portfolio Item:', 'nk-themes-helper' ),
                        'not_found'          => esc_html__( 'No portfolio items found', 'nk-themes-helper' ),
                        'not_found_in_trash' => esc_html__( 'No portfolio items found in trash', 'nk-themes-helper' ),
                    ),
                    'supports'        => array(
                        'title',
                        'editor',
                        'excerpt',
                        'thumbnail',
                        'revisions',
                    ),
                    'public'          => true,
                    'capability_type' => 'post',
                    'rewrite'         => array( 'slug' => 'portfolio' ),
                    'menu_position'   => 5,
                    'menu_icon'       => ( version_compare( $GLOBALS['wp_version'], '3.8', '>=' ) ) ? 'dashicons-layout' : false,
                )
            )
        );
    }

    /**
     * Register Categories
     */
    public function register_categories_taxonomy() {
        register_taxonomy(
            'portfolio_category',
            array( 'portfolio' ),
            apply_filters(
                // phpcs:ignore
                'nk_portfolio_categories_params',
                array(
                    'labels'            => array(
                        'name'                       => esc_html__( 'Portfolio Categories', 'nk-themes-helper' ),
                        'singular_name'              => esc_html__( 'Portfolio Category', 'nk-themes-helper' ),
                        'menu_name'                  => esc_html__( 'Portfolio Categories', 'nk-themes-helper' ),
                        'edit_item'                  => esc_html__( 'Edit Portfolio Category', 'nk-themes-helper' ),
                        'update_item'                => esc_html__( 'Update Portfolio Category', 'nk-themes-helper' ),
                        'add_new_item'               => esc_html__( 'Add New Portfolio Category', 'nk-themes-helper' ),
                        'new_item_name'              => esc_html__( 'New Portfolio Category Name', 'nk-themes-helper' ),
                        'parent_item'                => esc_html__( 'Parent Portfolio Category', 'nk-themes-helper' ),
                        'parent_item_colon'          => esc_html__( 'Parent Portfolio Category:', 'nk-themes-helper' ),
                        'all_items'                  => esc_html__( 'All Portfolio Categories', 'nk-themes-helper' ),
                        'search_items'               => esc_html__( 'Search Portfolio Categories', 'nk-themes-helper' ),
                        'popular_items'              => esc_html__( 'Popular Portfolio Categories', 'nk-themes-helper' ),
                        'separate_items_with_commas' => esc_html__( 'Separate portfolio categories with commas', 'nk-themes-helper' ),
                        'add_or_remove_items'        => esc_html__( 'Add or remove portfolio categories', 'nk-themes-helper' ),
                        'choose_from_most_used'      => esc_html__( 'Choose from the most used portfolio categories', 'nk-themes-helper' ),
                        'not_found'                  => esc_html__( 'No portfolio categories found.', 'nk-themes-helper' ),
                    ),
                    'public'            => true,
                    'show_in_nav_menus' => true,
                    'show_ui'           => true,
                    'show_tagcloud'     => true,
                    'hierarchical'      => true,
                    'rewrite'           => array( 'slug' => 'portfolio_category' ),
                    'show_admin_column' => true,
                    'query_var'         => true,
                )
            )
        );
    }

    /**
     * Register Tags
     */
    public function register_tags_taxonomy() {
        register_taxonomy(
            'portfolio_tag',
            array( 'portfolio' ),
            apply_filters(
                // phpcs:ignore
                'nk_portfolio_tags_params',
                array(
                    'labels'            => array(
                        'name'                       => esc_html__( 'Portfolio Tags', 'nk-themes-helper' ),
                        'singular_name'              => esc_html__( 'Portfolio Tag', 'nk-themes-helper' ),
                        'menu_name'                  => esc_html__( 'Portfolio Tags', 'nk-themes-helper' ),
                        'edit_item'                  => esc_html__( 'Edit Portfolio Tag', 'nk-themes-helper' ),
                        'update_item'                => esc_html__( 'Update Portfolio Tag', 'nk-themes-helper' ),
                        'add_new_item'               => esc_html__( 'Add New Portfolio Tag', 'nk-themes-helper' ),
                        'new_item_name'              => esc_html__( 'New Portfolio Tag Name', 'nk-themes-helper' ),
                        'parent_item'                => esc_html__( 'Parent Portfolio Tag', 'nk-themes-helper' ),
                        'parent_item_colon'          => esc_html__( 'Parent Portfolio Tag:', 'nk-themes-helper' ),
                        'all_items'                  => esc_html__( 'All Portfolio Tags', 'nk-themes-helper' ),
                        'search_items'               => esc_html__( 'Search Portfolio Tags', 'nk-themes-helper' ),
                        'popular_items'              => esc_html__( 'Popular Portfolio Tags', 'nk-themes-helper' ),
                        'separate_items_with_commas' => esc_html__( 'Separate portfolio tags with commas', 'nk-themes-helper' ),
                        'add_or_remove_items'        => esc_html__( 'Add or remove portfolio tags', 'nk-themes-helper' ),
                        'choose_from_most_used'      => esc_html__( 'Choose from the most used portfolio tags', 'nk-themes-helper' ),
                        'not_found'                  => esc_html__( 'No portfolio tags found.', 'nk-themes-helper' ),
                    ),
                    'public'            => true,
                    'show_in_nav_menus' => true,
                    'show_ui'           => true,
                    'show_tagcloud'     => true,
                    'hierarchical'      => true,
                    'rewrite'           => array( 'slug' => 'portfolio_tag' ),
                    'show_admin_column' => true,
                    'query_var'         => true,
                )
            )
        );
    }

    /**
     * Custom Column for Thumbnail
     *
     * @param array $columns - columns.
     *
     * @return array
     */
    public function portfolio_columns( $columns = array() ) {
        $column_meta = array( 'portfolio_post_thumbs' => esc_html__( 'Thumbnail', 'nk-themes-helper' ) );
        $columns     = array_slice( $columns, 0, 1, true ) + $column_meta + array_slice( $columns, 1, null, true );

        return $columns;
    }

    /**
     * Custom Column for Thumbnail
     *
     * @param bool $column_name - columns name.
     */
    public function portfolio_custom_columns( $column_name = false ) {
        if ( 'portfolio_post_thumbs' === $column_name && has_post_thumbnail() ) {
            echo '<a href="' . esc_url( get_edit_post_link() ) . '" class="nk-portfolio-thumbnail">';
            the_post_thumbnail( 'medium' );
            echo '</a>';
        }
    }

    /**
     * Get Messages
     */
    public function get_messages() {
        $post             = get_post();
        $post_type        = get_post_type( $post );
        $post_type_object = get_post_type_object( $post_type );

        $messages = array(
            0  => '', // Unused. Messages start at index 1.
            1  => esc_html__( 'Portfolio item updated.', 'nk-themes-helper' ),
            2  => esc_html__( 'Custom field updated.', 'nk-themes-helper' ),
            3  => esc_html__( 'Custom field deleted.', 'nk-themes-helper' ),
            4  => esc_html__( 'Portfolio item updated.', 'nk-themes-helper' ),
            // phpcs:ignore
            /* translators: %s: date and time of the revision */
            // phpcs:ignore
            5  => isset( $_GET['revision'] ) ? sprintf( esc_html__( 'Portfolio item restored to revision from %s', 'nk-themes-helper' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => esc_html__( 'Portfolio item published.', 'nk-themes-helper' ),
            7  => esc_html__( 'Portfolio item saved.', 'nk-themes-helper' ),
            8  => esc_html__( 'Portfolio item submitted.', 'nk-themes-helper' ),
            9  => sprintf(
                // translators: %1$s.
                esc_html__( 'Portfolio item scheduled for: <strong>%1$s</strong>.', 'nk-themes-helper' ),
                /* translators: Publish box date format, see http://php.net/date */
                date_i18n( esc_html__( 'M j, Y @ G:i', 'nk-themes-helper' ), strtotime( $post->post_date ) )
            ),
            10 => esc_html__( 'Portfolio item draft updated.', 'nk-themes-helper' ),
        );

        if ( $post_type_object->publicly_queryable ) {
            $permalink         = get_permalink( $post->ID );
            $preview_permalink = add_query_arg( 'preview', 'true', $permalink );

            $view_link    = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), esc_html__( 'View portfolio item', 'nk-themes-helper' ) );
            $preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), esc_html__( 'Preview portfolio item', 'nk-themes-helper' ) );

            $messages[1]  .= $view_link;
            $messages[6]  .= $view_link;
            $messages[9]  .= $view_link;
            $messages[8]  .= $preview_link;
            $messages[10] .= $preview_link;
        }
        return $messages;
    }



    /**
     * Helper Public Methods
     */

    /**
     * Get taxonomies
     *
     * @param string $name - taxonomy name.
     * @param string $type return type (compact, full).
     *
     * @return array
     */
    private function get_taxonomies( $name = 'portfolio_category', $type = 'full' ) {
        $result = array();
        $terms  = get_terms( $name );

        foreach ( $terms as $term ) {
            switch ( $type ) {
                case 'compact':
                    $result[ $term->term_id ] = $term->name;
                    break;
                default:
                    $result[] = array(
                        'term_id'     => $term->term_id,
                        'name'        => $term->name,
                        'slug'        => $term->slug,
                        'taxonomy'    => $term->taxonomy,
                        'description' => $term->description,
                        'count'       => $term->count,
                    );
                    break;
            }
        }

        return $result;
    }

    /**
     * Get categories
     *
     * @param string $type return type (compact, full).
     *
     * @return array
     */
    public function get_categories( $type = 'full' ) {
        return $this->get_taxonomies( 'portfolio_category', $type );
    }

    /**
     * Get tags
     *
     * @param string $type return type (compact, full).
     *
     * @return array
     */
    public function get_tags( $type = 'full' ) {
        return $this->get_taxonomies( 'portfolio_tag', $type );
    }

    /**
     * Portfolio items list
     *
     * @param string $type return type (compact, full).
     * @param array  $args - args.
     *
     * @return array
     */
    public function get_portfolio_items( $type = 'full', $args = array() ) {
        $result = array();

        $args = array_merge(
            array(
                'post_type'      => 'portfolio',
                // @codingStandardsIgnoreLine
                'posts_per_page' => -1,
            ),
            $args
        );

        $loop = new WP_Query( $args );

        if ( $loop->have_posts() ) {
            while ( $loop->have_posts() ) {
                $loop->the_post();

                switch ( $type ) {
                    case 'compact':
                        $result[ get_the_ID() ] = get_the_title();
                        break;
                    default:
                        $result[] = array(
                            'id'          => get_the_ID(),
                            'title'       => get_the_title(),
                            'permalink'   => get_permalink(),
                            'content'     => get_the_content(),
                            'thumbnail'   => get_post_thumbnail_id(),
                            'categories'  => $this->categories ? get_the_terms( get_the_ID(), 'portfolio_category' ) : array(),
                            'tags'        => $this->tags ? get_the_terms( get_the_ID(), 'portfolio_tag' ) : array(),
                        );
                        break;
                }
            }
        }
        wp_reset_postdata();

        return $result;
    }
}
