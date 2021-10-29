<?php
/**
 * YP Carousel
 *
 * Example:
 * [yp_posts_carousel style="1" post_type="ids" posts="1,2,3" exclude_ids="" custom_query="" taxonomies="" autoplay="" stage_padding="70" item_padding="0" show_price="true" show_rating="true" show_discount_badges="true" badges_always_show="false" boxed="false"]
 */

add_shortcode( 'yp_posts_carousel', 'yp_posts_carousel' );

if ( ! function_exists( 'yp_posts_carousel' ) ) :
function yp_posts_carousel($atts, $content = null) {
    extract(shortcode_atts(array(
        "style"               => 1,
        "size"                => 4,
        "post_type"           => "ids",
        "count"               => 5,
        "taxonomies"          => "",
        "taxonomies_relation" => "OR",
        "posts"               => "",
        "custom_query"        => "",
        "exclude_ids"         => "",
        "orderby"             => "post_date",
        "order"               => "DESC",
        "autoplay"            => "",
        "loop"                => true,
        "stage_padding"       => 70,
        "item_padding"        => 0,
        "show_arrows"         => true,
        "show_dots"           => false,
        "show_price"          => true,
        "show_rating"         => true,
        "show_discount_badges"=> true,
        "badges_always_show"  => false,
        "img_size"            => "500x375_crop",
        "boxed"               => false,
        "class"               => ""
    ), $atts));



    /**
     * Set Up Query
     */
    $query_opts = array(
        'showposts' => intval($count),
        'posts_per_page' => intval($count),
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
        $posts = explode(",", $posts);
        $query_opts['post_type'] = 'any';
        $query_opts['post__in'] = $posts;
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
    $before = '';
    $after = '';
    if(yp_check($boxed)) {
      $before = "<div class='container'>";
      $after = "</div>";
    }

    // size
    if ($size) {
        $class .= ' youplay-carousel-size-' . $size;
    }

    // autoplay
    $autoplay = intval($autoplay);
    if($autoplay) {
      $autoplay = 'data-autoplay="' . $autoplay . '"';
    } else {
      $autoplay = '';
    }

    $result_items = '';
    $yp_query = new WP_Query($query_opts);

    while ($yp_query->have_posts()) : $yp_query->the_post();
      global $product;

      $title = "<h4>" . get_the_title() . "</h4>";
      $rating = '';
      $price = '';
      $badge = '';

      $img_src = get_post_thumbnail_id( get_the_ID() );
      $img = youplay_get_image( $img_src, $img_size );

      // use no-image
      if ( ! $img) {
          $img = '<img src="' . esc_url( yp_opts('single_post_noimage') ) . '" alt="" />';
      }

      if ($product) {
        if(yp_check($show_rating)) {
          $rating = yp_get_rating( $product->get_average_rating() );
        }

        if(yp_check($show_discount_badges) && function_exists('yp_woo_discount_badge')) {
          $badge = yp_woo_discount_badge($product, yp_check($badges_always_show));
        }

        if(yp_check($show_price) && $price = $product->get_price_html()) {
          $price = '<div class="price">' . $price . '</div>';
        }
      }

      $item_content = '';
      if($style == 1) {
        $item_content =
          '<a class="angled-img" href="' . esc_url(get_permalink()) . '">
            <div class="img">
              ' . $img . '
              ' . $badge . '
            </div>
            <div class="over-info">
              <div>
                <div>
                  ' . $title . '
                  ' . $rating . '
                  ' . $price . '
                </div>
              </div>
            </div>
          </a>';
      } else {
        $description = '';
        if( yp_check($price) && yp_check($rating) ) {
          $description =
            '<div class="row">
              <div class="col-xs-6">
                ' . $rating . '
              </div>
              <div class="col-xs-6">
                ' . $price . '
              </div>
            </div>';
        } else if( yp_check($price) ) {
          $description = $price;
        } else if( yp_check($rating) ) {
          $description = $rating;
        }

        $item_content =
          '<a class="angled-img" href="' . esc_url(get_permalink()) . '">
            <div class="img img-offset">
              ' . $img . '
              ' . $badge . '
            </div>
            <div class="bottom-info">
              ' . $title . '
              ' . $description . '
            </div>
          </a>';
      }

      $result_items .= $item_content;

    endwhile;

    wp_reset_postdata();

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return $before . '<div class="youplay-carousel ' . esc_attr($class) . '" ' . $autoplay . ' data-stage-padding="' . esc_attr($stage_padding) . '" data-item-padding="' . esc_attr($item_padding) . '" data-loop="' . esc_attr(yp_check($loop) ? 'true' : 'false') . '" data-dots="' . esc_attr(yp_check($show_dots) ? 'true' : 'false') . '" data-arrows="' . esc_attr(yp_check($show_arrows) ? 'true' : 'false') . '">' . $result_items . '</div>' . $after;
}
endif;


/* Add VC Shortcode */
add_action( "init", "vc_youplay_posts_carousel" );
if ( ! function_exists( 'vc_youplay_posts_carousel' ) ) :
function vc_youplay_posts_carousel() {
    if(function_exists("vc_map")) {

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
           "name"     => esc_html__("nK Posts Carousel", 'youplay-core'),
           "base"     => "yp_posts_carousel",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk icon-nk-posts-carousel",
           "params"   => array_merge( array(
               /**
                * General
                */
               array(
                   "type"       => "dropdown",
                   "heading"    => esc_html__("Style", 'youplay-core'),
                   "param_name" => "style",
                   "value"      => array(
                       esc_html__("Style 1", 'youplay-core') => 1,
                       esc_html__("Style 2", 'youplay-core') => 2
                   ),
                   "admin_label" => true,
                   "description" => ""
               ),
               array(
                   "type"       => "dropdown",
                   "heading"    => esc_html__("Size", 'youplay-core'),
                   "param_name" => "size",
                   "std"        => 4,
                   "value"      => array(
                       esc_html__("1", 'youplay-core') => 1,
                       esc_html__("2", 'youplay-core') => 2,
                       esc_html__("3", 'youplay-core') => 3,
                       esc_html__("4", 'youplay-core') => 4,
                       esc_html__("5", 'youplay-core') => 5,
                       esc_html__("6", 'youplay-core') => 6
                   ),
                   "admin_label" => true,
                   "description" => ""
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Posts Count", 'youplay-core'),
                   "param_name"  => "count",
                   "value"       => 5,
                   "description" => "",
                   "admin_label" => true,
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Autoplay", 'youplay-core'),
                   "param_name"  => "autoplay",
                   "value"       => "",
                   "description" => esc_html__("Type integer value in ms", 'youplay-core')
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Loop", 'youplay-core'),
                   "param_name"  => "loop",
                   "std"         => true,
                   "value"       => array( "" => true ),
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Stage Padding", 'youplay-core'),
                   "param_name"  => "stage_padding",
                   "value"       => 70
               ),
               array(
                   "type"        => "textfield",
                   "heading"     => esc_html__("Item Padding", 'youplay-core'),
                   "param_name"  => "item_padding",
                   "value"       => 0
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Show Arrows", 'youplay-core'),
                   "param_name"  => "show_arrows",
                   "std"         => true,
                   "value"       => array( "" => true ),
                   "description" => "",
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Show Dots", 'youplay-core'),
                   "param_name"  => "show_dots",
                   "value"       => array( "" => true ),
                   "description" => "",
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Show Price", 'youplay-core'),
                   "param_name"  => "show_price",
                   "value"       => array( "" => true ),
                   "description" => "",
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Show Rating", 'youplay-core'),
                   "param_name"  => "show_rating",
                   "value"       => array( "" => true ),
                   "description" => "",
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Show Discount Badges", 'youplay-core'),
                   "param_name"  => "show_discount_badges",
                   "value"       => array( "" => true ),
                   "description" => "",
               ),
               array(
                   "type"        => "checkbox",
                   "heading"     => esc_html__("Badges Always Show", 'youplay-core'),
                   "param_name"  => "badges_always_show",
                   "value"       => array( "" => true ),
                   "description" => esc_html__("When unchecked - show only on mouse over", 'youplay-core'),
               ),
               array(
                   "type" => "dropdown",
                   "heading" => esc_html__("Images Size", 'youplay-core'),
                   "param_name" => "img_size",
                   "value" => yp_get_intermediate_image_sizes(),
                   "std" => "500x375_crop",
                   "description" => "",
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
                   "std"         => "ids",
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
                   "heading"     => esc_html__("IDs", 'youplay-core'),
                   "group"       => esc_html__("Query", 'youplay-core'),
                   "param_name"  => "posts",
                   "value"       => "",
                   "description" => esc_html__("Type here the posts, pages, etc. IDs you want to use separated by coma. ex: 23,24,25", 'youplay-core'),
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
                   "description" => sprintf(
                       esc_html__( "Build custom query according to %s.", 'youplay-core' ),
                       "<a href='http://codex.wordpress.org/Function_Reference/query_posts'>WordPress Codex</a>"
                   ),
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
