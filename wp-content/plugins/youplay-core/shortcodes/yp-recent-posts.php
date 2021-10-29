<?php
/**
 * YP Recent Posts
 *
 * Example:
 * [yp_recent_posts style="1" post_type="post" ids="" exclude_ids="" custom_query="" taxonomies="" count="5" pagination="false" boxed="false"]
 */

add_shortcode( 'yp_recent_posts', 'yp_recent_posts' );

if ( ! function_exists( 'yp_recent_posts' ) ) :
function yp_recent_posts($atts, $content = null) {
    extract(shortcode_atts(array(
        "style"            => 1,
        "post_type"        => "post",
        "taxonomies"       => "",
        "taxonomies_relation" => "OR",
        "ids"              => "",
        "custom_query"     => "",
        "exclude_ids"      => "",
        "orderby"          => "post_date",
        "order"            => "DESC",
        "count"            => 5,
        "pagination"       => false, // false, true, 'load_more', 'infinitie'
        "boxed"            => false,
        "class"            => ""
    ), $atts));


    /**
     * Set Up Query
     */
    $paged = 0;
    if (yp_check($pagination)) {
        $paged = max(1, get_query_var('page'), get_query_var('paged'));
    }
    $query_opts = array(
        'showposts' => intval($count),
        'posts_per_page' => intval($count),
        'paged' => $paged,
        'order' => $order
    );

    // Order By
    switch ($orderby) {
        case 'title':
            $query_opts['orderby'] = 'title';
            break;

        case 'id':
            $query_opts['orderby'] = 'ID';
            break;

        case 'post__in':
            $query_opts['orderby'] = 'post__in';
            break;

        default:
            $query_opts['orderby'] = 'post_date';
            break;
    }

    // Exclude IDs
    $exclude_ids = explode(",", $exclude_ids);
    if ($exclude_ids) {
        $query_opts['post__not_in'] = $exclude_ids;
    }

    // IDs
    if ($post_type == 'ids') {
        $ids = explode(",", $ids);
        $query_opts['post_type'] = 'any';
        $query_opts['post__in'] = $ids;
    } // Custom Query
    else if ($post_type == 'custom_query') {
        $tmp_arr = array();
        parse_str(html_entity_decode($custom_query), $tmp_arr);
        $query_opts = array_merge($query_opts, $tmp_arr);
    } else {
        // Taxonomies
        $taxonomies = $taxonomies ? explode(",", $taxonomies) : array();
        if (!empty($taxonomies)) {
            $all_terms = yp_get_terms();
            $query_opts['tax_query'] = array(
                'relation' => yp_check($taxonomies_relation) ? $taxonomies_relation : 'OR'
            );
            foreach ($taxonomies as $taxonomy) {
                $taxonomy_name = null;

                foreach ($all_terms as $term) {
                    if ($term['value'] == $taxonomy) {
                        $taxonomy_name = $term['group'];
                        continue;
                    }
                }

                if ($taxonomy_name) {
                    $query_opts['tax_query'][] = array(
                        'taxonomy' => $taxonomy_name,
                        'field' => 'id',
                        'terms' => $taxonomy
                    );
                }
            }
        }
        $query_opts['post_type'] = $post_type;
    }


    /**
     * Work with printing posts
     */
    if(yp_check($pagination === 'infinitie')) {
      $class .= ' nk-infinitie-scroll-container';
    }
    if(yp_check($pagination === 'load_more')) {
      $class .= ' nk-load-more-container';
    }

    $before = $after = '';

    if($style == 3) {
        $before = '<div class="isotope ' . (yp_check($boxed) ? 'container' : '') . '">';
        $after = '</div>';
        $class .= ' isotope isotope-list news-grid row';
    } else if(yp_check($boxed)) {
        $class .= " container";
    }

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    ob_start();
    echo wp_kses_post($before);

    ?> <div class="youplay-news <?php echo esc_attr($class); ?>"> <?php
      $yp_query = new WP_Query($query_opts);
      $counter = 0;
      while ($yp_query->have_posts()) : $yp_query->the_post();
        get_template_part( 'template-parts/content', $style );
      endwhile;
    ?>
    </div>

    <div class="clearfix"></div>
    <?php if($pagination) {
      yp_posts_navigation($yp_query);

      if($pagination == 'infinitie' || $pagination == 'load_more') {
        nk_infinitie_scroll_init($yp_query);
      }
    } ?>

    <?php echo wp_kses_post($after); ?>

    <?php
    wp_reset_postdata();

    $result = ob_get_contents();
    ob_end_clean();
    return $result;
}
endif;



/* Add VC Shortcode */
add_action( "init", "vc_yp_recent_posts" );
if ( ! function_exists( 'vc_yp_recent_posts' ) ) :
function vc_yp_recent_posts() {
    if(function_exists("vc_map")) {

        // post types list
        $post_types = get_post_types( array() );
        $post_types_list = array();
        if ( is_array( $post_types ) && ! empty( $post_types ) ) {
          foreach ( $post_types as $post_type ) {
            if ( $post_type !== "revision" && $post_type !== "nav_menu_item"/* && $post_type !== "attachment"*/ ) {
              $label = ucfirst( $post_type );
              $post_types_list[] = array( $post_type, $label );
            }
          }
        }
        $post_types_list[] = array( "custom_query", esc_html__( "Custom Query", 'youplay-core' ) );
        $post_types_list[] = array( "ids", esc_html__( "List of IDs", 'youplay-core' ) );


        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name" => esc_html__("nK Recent Posts", 'youplay-core'),
           "base" => "yp_recent_posts",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-recent-posts",
           "params" => array_merge( array(
               /**
                * General
                */
               array(
                   "type"       => "dropdown",
                   "heading"    => esc_html__("Style", 'youplay-core'),
                   "param_name" => "style",
                   "value"      => array(
                       esc_html__("Style 1", 'youplay-core') => 1,
                       esc_html__("Style 2", 'youplay-core') => 2,
                       esc_html__("Style 3", 'youplay-core') => 3
                   ),
                   "description" => ""
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Recent Posts Count", 'youplay-core'),
                   "param_name"  => "count",
                   "value"       => 5,
                   "description" => "",
               ),
               array(
                   "type"       => "dropdown",
                   "heading"    => esc_html__("Pagination", 'youplay-core'),
                   "param_name" => "pagination",
                   "value"      => array(
                       esc_html__("No Pagination", 'youplay-core') => "",
                       esc_html__("Simple Pagination", 'youplay-core') => true,
                       esc_html__("Load More button", 'youplay-core') => 'load_more',
                       esc_html__("Infinitie Scroll", 'youplay-core') => 'infinitie'
                   ),
                   "description" => ""
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Boxed", 'youplay-core'),
                   "param_name"  => "boxed",
                   "value"       => array( "" => true ),
                   "description" => esc_html__("Use it when your page content boxed disabled", 'youplay-core'),
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Custom Classes", 'youplay-core'),
                   "param_name"  => "class",
                   "value"       => "",
                   "description" => "",
               ),


               /**
                * Query
                */
               array(
                   "type"        => "dropdown",
                   "heading"     => esc_html__( "Data source", 'youplay-core' ),
                   "group"       => esc_html__("Query", 'youplay-core'),
                   "param_name"  => "post_type",
                   "value"       => $post_types_list,
                   "std"         => "post",
                   "description" => esc_html__( "Select content type", 'youplay-core' )
               ),
               array(
                   'type' => 'autocomplete',
                   'heading' => esc_html__( 'Narrow data source', 'youplay-core' ),
                   "group"       => esc_html__("Query", 'youplay-core'),
                   'param_name' => 'taxonomies',
                   'settings' => array(
                       'multiple' => true,
                       'min_length' => 1,
                       'groups' => true,
                       // In UI show results grouped by groups, default false
                       'unique_values' => true,
                       // In UI show results except selected. NB! You should manually check values in backend, default false
                       'display_inline' => true,
                       // In UI show results inline view, default false (each value in own line)
                       'delay' => 100,
                       // delay for search. default 500
                       'auto_focus' => true,
                       // auto focus input, default true
                       'values' => yp_get_terms()
                   ),
                   'description' => esc_html__( 'Enter categories, tags or custom taxonomies.', 'youplay-core' ),
                   'dependency' => array(
                       'element' => 'post_type',
                       'value_not_equal_to' => array(
                           'ids',
                           'custom_query',
                       ),
                   ),
               ),
               array(
                   "type" => "dropdown",
                   "heading" => esc_html__("Data source relation", 'youplay-core'),
                   "group" => esc_html__("Query", 'youplay-core'),
                   "param_name" => "taxonomies_relation",
                   "value" => array(
                       "OR", "AND"
                   ),
                   "std" => "OR",
                   'dependency' => array(
                       'element' => 'post_type',
                       'value_not_equal_to' => array(
                           'ids',
                           'custom_query',
                       ),
                   ),
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__( "IDs", 'youplay-core' ),
                   "group"       => esc_html__("Query", 'youplay-core'),
                   "param_name"  => "ids",
                   "description" => esc_html__( "Type here the posts, pages, etc. IDs you want to use separated by coma. ex: 23,24,25", 'youplay-core' ),
                   "dependency"  => array(
                       "element"   => "post_type",
                       "value"     => array( "ids" ),
                   ),
               ),
               array(
                   "type"        => "textarea_safe",
                   "heading"     => esc_html__( "Custom Query", 'youplay-core' ),
                   "group"       => esc_html__("Query", 'youplay-core'),
                   "param_name"  => "custom_query",
                   "description" => sprintf(esc_html__( "Build custom query according to %s.", 'youplay-core' ), "<a href='http://codex.wordpress.org/Function_Reference/query_posts'>WordPress Codex</a>"),
                   "dependency"  => array(
                       "element"   => "post_type",
                       "value"     => array( "custom_query" ),
                   ),
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__( "Exclude IDs", 'youplay-core' ),
                   "group"       => esc_html__("Query", 'youplay-core'),
                   "param_name"  => "exclude_ids",
                   "description" => esc_html__( "Type here the posts, pages, etc. IDs you want to use separated by coma. ex: 23,24,25", 'youplay-core' ),
               ),
               array(
                   "type" => "dropdown",
                   "heading" => esc_html__("Order By", 'youplay-core'),
                   "group" => esc_html__("Query", 'youplay-core'),
                   "param_name" => "orderby",
                   "value" => array(
                       esc_html__("Date", 'youplay-core') => 'post_date',
                       esc_html__("Title", 'youplay-core') => 'title',
                       esc_html__("ID", 'youplay-core') => 'id',
                       esc_html__("Post In", 'youplay-core') => 'post__in',
                   ),
                   "std" => "post_date",
               ),
               array(
                   "type" => "dropdown",
                   "heading" => esc_html__("Order", 'youplay-core'),
                   "group" => esc_html__("Query", 'youplay-core'),
                   "param_name" => "order",
                   "value" => array(
                       "DESC", "ASC"
                   ),
                   "std" => "DESC",
               ),
           ), yp_get_css_tab() )
        ) );
    }
}
endif;
