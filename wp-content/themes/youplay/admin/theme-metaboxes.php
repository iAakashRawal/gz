<?php
add_filter( 'ot_override_forced_textarea_simple', '__return_true' );

if ( ! function_exists( 'youplay_get_sidebars' ) ) :
function youplay_get_sidebars() {
  global $wp_registered_sidebars;
  $sidebars = array();

  foreach($wp_registered_sidebars as $k => $sidebar) {
    $sidebars[] = $k;
  }

  return $sidebars;
}
endif;

if ( ! function_exists( 'getYouplayLayouts' ) ) :
function getYouplayLayouts() {
  return array(
    array(
      'value'       => 'default',
      'label'       => esc_html__('Default', 'youplay'),
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/default.jpg'
    ),
    array(
      'value'       => 'cont',
      'label'       => esc_html__('Content', 'youplay'),
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/cont.jpg'
    ),
    array(
      'value'       => 'cont-side',
      'label'       => esc_html__('Content + Sidebar', 'youplay'),
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/cont-side.jpg'
    ),
    array(
      'value'       => 'side-cont',
      'label'       => esc_html__('Sidebar + Content', 'youplay'),
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/side-cont.jpg'
    ),
    array(
      'value'       => 'banner-cont',
      'label'       => esc_html__('Banner + Content', 'youplay'),
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/banner-cont.jpg'
    ),
    array(
      'value'       => 'banner-cont-side',
      'label'       => esc_html__('Banner + Content + Sidebar', 'youplay'),
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/banner-cont-side.jpg'
    ),
    array(
      'value'       => 'banner-side-cont',
      'label'       => esc_html__('Banner + Sidebar + Content', 'youplay'),
      'src'         => nk_admin()->admin_uri . '/assets/images/layouts/banner-side-cont.jpg'
    )
  );
}
endif;


/* Background Image metaboxes for all page types */
if ( ! function_exists( 'getYouplayBackgroundMetabox' ) ) :
function getYouplayBackgroundMetabox() {
  return array(
    array(
      'id'          => 'tab_backgound',
      'label'       => esc_html__('Background', 'youplay'),
      'desc'        => '',
      'std'         => '',
      'type'        => 'tab',
      'operator'    => 'and'
    ),
    array(
      'id'          => 'general_background',
      'label'       => esc_html__('Enable Background', 'youplay'),
      'desc'        => '',
      'std'         => 'default',
      'type'        => 'select',
      'choices'     => array(
        array(
          'value'       => 'default',
          'label'       => esc_html__('Default', 'youplay'),
          'src'         => ''
        ),
        array(
          'value'       => 'on',
          'label'       => esc_html__('On', 'youplay'),
          'src'         => ''
        ),
        array(
          'value'       => 'off',
          'label'       => esc_html__('Off', 'youplay'),
          'src'         => ''
        )
      )
    ),
    array(
      'id'          => 'general_background_image',
      'label'       => esc_html__('Image', 'youplay'),
      'desc'        => '',
      'type'        => 'upload',
      'condition'   => 'general_background:not(off)'
    ),
    array(
      'id'          => 'general_background_cover',
      'label'       => esc_html__('Cover', 'youplay'),
      'desc'        => esc_html__('Cover image if ON. Repeat image if OFF.', 'youplay'),
      'std'         => 'default',
      'type'        => 'select',
      'choices'     => array(
        array(
          'value'       => 'default',
          'label'       => esc_html__('Default', 'youplay'),
          'src'         => ''
        ),
        array(
          'value'       => 'on',
          'label'       => esc_html__('On', 'youplay'),
          'src'         => ''
        ),
        array(
          'value'       => 'off',
          'label'       => esc_html__('Off', 'youplay'),
          'src'         => ''
        )
      ),
      'condition'   => 'general_background:not(off)'
    ),
    array(
      'id'          => 'general_background_fixed',
      'label'       => esc_html__('Fixed', 'youplay'),
      'desc'        => esc_html__('Fixed attachment (not scroll with page)', 'youplay'),
      'std'         => 'default',
      'type'        => 'select',
      'choices'     => array(
        array(
          'value'       => 'default',
          'label'       => esc_html__('Default', 'youplay'),
          'src'         => ''
        ),
        array(
          'value'       => 'on',
          'label'       => esc_html__('On', 'youplay'),
          'src'         => ''
        ),
        array(
          'value'       => 'off',
          'label'       => esc_html__('Off', 'youplay'),
          'src'         => ''
        )
      ),
      'condition'   => 'general_background:not(off)'
    ),
    array(
      'id'          => 'general_background_parallax',
      'label'       => esc_html__('Parallax', 'youplay'),
      'desc'        => esc_html__('Change background position on scroll from top screen to bottom. Set 0 to disable. Set "default" to use value from Options panel.', 'youplay'),
      'std'         => 'default',
      'type'        => 'text',
      'condition'   => 'general_background:not(off)'
    ),
    array(
      'id'          => 'general_content_bg_opacity_metabox',
      'label'       => esc_html__('Custom Background Opacity', 'youplay'),
      'desc'        => '',
      'std'         => 'off',
      'type'        => 'on-off',
      'condition'   => 'general_background:not(off)'
    ),
    array(
      'id'          => 'general_content_bg_opacity',
      'label'       => '',
      'desc'        => '',
      'std'         => '75',
      'type'        => 'numeric-slider',
      'min_max_step'=> '0,100,1',
      'condition'   => 'general_content_bg_opacity_metabox:is(on),general_background:not(off)'
    ),
  );
}
endif;



/**
------------------
PAGE METABOXES
------------------
*/
add_action( 'admin_init', 'yp_page_meta_boxes' );

if ( ! function_exists( 'yp_page_meta_boxes' ) ) {
  function yp_page_meta_boxes() {
    //layout
    $meta_box = array(
      'id'        => 'page_custom_options',
      'title'     => esc_html__('Youplay Custom Options', 'youplay'),
      'desc'      => '',
      'pages'     => array( 'page' ),
      'context'   => 'normal',
      'priority'  => 'high',
      'fields'    => array_merge(array(
        array(
          'id'          => 'tab_page_layout',
          'label'       => esc_html__('Layout', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'tab',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_page_layout',
          'label'       => esc_html__('Layout','youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'radio-image',
          'class'       => '',
          'choices'     => getYouplayLayouts()
        ),
        array(
          'id'          => 'single_page_sidebar',
          'label'       => esc_html__('Sidebar', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'sidebar_select'
        ),
        array(
          'id'          => 'single_page_show_title',
          'label'       => esc_html__('Show Title', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_page_boxed_cont',
          'label'       => esc_html__('Boxed Content', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_page_comments',
          'label'       => esc_html__('Show Comments', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_page_nopadding',
          'label'       => esc_html__('Remove Content Padding', 'youplay'),
          'desc'        => esc_html__('Remove padding from top and bottom of content. May be useful with carousels on top and bottom.', 'youplay'),
          'std'         => 'off',
          'type'        => 'on-off',
        ),
        array(
          'id'          => 'single_page_revslider',
          'label'       => esc_html__('Use Revolution Slider', 'youplay'),
          'desc'        => esc_html__('Title will be hidden', 'youplay'),
          'std'         => 'off',
          'type'        => 'on-off',
        ),
        array(
          'id'          => 'single_page_revslider_alias',
          'label'       => esc_html__('Slider', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'revslider-select',
        ),

        array(
          'id'          => 'tab_page_banner',
          'label'       => esc_html__('Banner', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'tab',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_page_banner_image',
          'label'       => esc_html__('Image', 'youplay'),
          'desc'        => '',
          'std'         => yp_opts('single_page_banner_image'),
          'type'        => 'upload',
        ),
        array(
          'id'          => 'single_page_banner_size',
          'label'       => esc_html__('Banner Size', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'full',
              'label'       => esc_html__('Full', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'big',
              'label'       => esc_html__('Big', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'mid',
              'label'       => esc_html__('Mid', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'small',
              'label'       => esc_html__('Small', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'xsmall',
              'label'       => esc_html__('Extra Small', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_page_banner_parallax',
          'label'       => esc_html__('Banner Parallax', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
          array(
              'id'          => 'single_page_banner_parallax_speed_type',
              'label'       => esc_html__('Parallax Image Speed', 'youplay'),
              'desc'        => '',
              'std'         => 'default',
              'type'        => 'select',
              'choices'     => array(
                  array(
                      'value'       => 'default',
                      'label'       => esc_html__('Default', 'youplay'),
                      'src'         => ''
                  ),
                  array(
                      'value'       => 'custom',
                      'label'       => esc_html__('Custom', 'youplay'),
                      'src'         => ''
                  )
              ),
          ),
          array(
              'id'          => 'single_page_banner_parallax_speed',
              'label'       => '',
              'desc'        => '',
              'std'         => '0.4',
              'type'        => 'numeric-slider',
              'min_max_step'=> '-1,2,0.1',
              'condition'   => 'single_page_banner_parallax_speed_type:is(custom)',
              'operator'    => 'and',
          ),
        array(
          'id'          => 'single_page_banner_cont',
          'label'       => esc_html__('Banner Text', 'youplay'),
          'desc'        => esc_html__('Leave blank if you want to display the name of the page', 'youplay'),
          'std'         => '',
          'type'        => 'textarea',
        )
      ), getYouplayBackgroundMetabox())
    );

    if(function_exists('ot_register_meta_box')) {
      ot_register_meta_box( $meta_box );
    }
  }
}


/**
------------------
POST METABOXES
------------------
*/
add_action( 'admin_init', 'yp_post_meta_boxes' );

if ( ! function_exists( 'yp_post_meta_boxes' ) ){

  function yp_post_meta_boxes() {
    //layout
    $meta_box = array(
      'id'        => 'post_custom_options',
      'title'     => esc_html__('Youplay Custom Options', 'youplay'),
      'desc'      => '',
      'pages'     => array( 'post' ),
      'context'   => 'normal',
      'priority'  => 'high',
      'fields'    => array_merge(array(
        array(
          'id'          => 'tab_post_layout',
          'label'       => esc_html__('Layout', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'tab',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_post_layout',
          'label'       => esc_html__('Layout','youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'radio-image',
          'class'       => '',
          'choices'     => getYouplayLayouts()
        ),
        array(
          'id'          => 'single_post_sidebar',
          'label'       => esc_html__('Sidebar', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'sidebar_select'
        ),
        array(
          'id'          => 'single_post_boxed_cont',
          'label'       => esc_html__('Boxed Content', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_post_comments',
          'label'       => esc_html__('Show Comments', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_post_revslider',
          'label'       => esc_html__('Use Revolution Slider', 'youplay'),
          'desc'        => esc_html__('Title will be hidden', 'youplay'),
          'std'         => 'off',
          'type'        => 'on-off',
        ),
        array(
          'id'          => 'single_post_revslider_alias',
          'label'       => esc_html__('Slider', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'revslider-select',
        ),

        array(
          'id'          => 'tab_post_banner',
          'label'       => esc_html__('Banner', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'tab',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_post_banner_image',
          'label'       => esc_html__('Image', 'youplay'),
          'desc'        => '',
          'std'         => yp_opts('single_post_banner_image'),
          'type'        => 'upload',
        ),
        array(
          'id'          => 'single_post_banner_size',
          'label'       => esc_html__('Banner Size', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'full',
              'label'       => esc_html__('Full', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'mid',
              'label'       => esc_html__('Mid', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'small',
              'label'       => esc_html__('Small', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'xsmall',
              'label'       => esc_html__('Extra Small', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_post_banner_parallax',
          'label'       => esc_html__('Banner Parallax', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
          array(
              'id'          => 'single_post_banner_parallax_speed_type',
              'label'       => esc_html__('Parallax Image Speed', 'youplay'),
              'desc'        => '',
              'std'         => 'default',
              'type'        => 'select',
              'choices'     => array(
                  array(
                      'value'       => 'default',
                      'label'       => esc_html__('Default', 'youplay'),
                      'src'         => ''
                  ),
                  array(
                      'value'       => 'custom',
                      'label'       => esc_html__('Custom', 'youplay'),
                      'src'         => ''
                  )
              ),
          ),
          array(
              'id'          => 'single_post_banner_parallax_speed',
              'label'       => '',
              'desc'        => '',
              'std'         => '0.4',
              'type'        => 'numeric-slider',
              'min_max_step'=> '-1,2,0.1',
              'condition'   => 'single_post_banner_parallax_speed_type:is(custom)',
              'operator'    => 'and',
          ),
        array(
          'id'          => 'single_post_banner_cont',
          'label'       => esc_html__('Banner Text', 'youplay'),
          'desc'        => esc_html__('Leave blank if you want to display the name of the post', 'youplay'),
          'std'         => '',
          'type'        => 'textarea',
        ),


        array(
          'id'          => 'tab_post_review',
          'label'       => esc_html__('Review', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'tab',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_post_review',
          'label'       => esc_html__('Show Review', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_post_review_rating',
          'label'       => esc_html__('Rating', 'youplay'),
          'desc'        => '',
          'std'         => '0',
          'type'        => 'numeric-slider',
          'section'     => 'single_post',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'min_max_step'=> '0,' . intval(yp_opts('single_post_review_max_rating')) . ',0.1',
          'class'       => '',
          'condition'   => 'single_post_review:not(off)',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_post_review_first_list',
          'label'       => esc_html__('First List', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'list-item',
          'section'     => 'single_post',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'min_max_step'=> '',
          'class'       => '',
          'settings'    => array( false ),
          'condition'   => 'single_post_review:not(off)',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_post_review_second_list',
          'label'       => esc_html__('Second List', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'list-item',
          'section'     => 'single_post',
          'rows'        => '',
          'post_type'   => '',
          'taxonomy'    => '',
          'min_max_step'=> '',
          'class'       => '',
          'settings'    => array( false ),
          'condition'   => 'single_post_review:not(off)',
          'operator'    => 'and'
        ),
      ), getYouplayBackgroundMetabox())
    );

    if (function_exists('ot_register_meta_box')) {
      ot_register_meta_box( $meta_box );
    }

    /**
     * Filters the required title field's label.
     */
    function filter_list_item_title_label( $label, $id ) {
      if ($id == 'single_post_review_first_list' || $id == 'single_post_review_second_list') {
        $label = esc_html__( 'Text', 'youplay' );
      }
      return $label;
    }
    add_filter( 'ot_list_item_title_label', 'filter_list_item_title_label', 10, 2 );
  }
}


/**
------------------
MATCH METABOXES
------------------
*/
add_action( 'admin_init', 'yp_match_meta_boxes' );

if ( ! function_exists( 'yp_match_meta_boxes' ) ){

  function yp_match_meta_boxes() {
    //layout
    $meta_box = array(
      'id'        => 'match_custom_options',
      'title'     => esc_html__('Youplay Custom Options', 'youplay'),
      'desc'      => '',
      'pages'     => array( 'match' ),
      'context'   => 'normal',
      'priority'  => 'high',
      'fields'    => array_merge(array(
        array(
          'id'          => 'tab_match_layout',
          'label'       => esc_html__('Layout', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'tab',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_match_layout',
          'label'       => esc_html__('Layout','youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'radio-image',
          'class'       => '',
          'choices'     => getYouplayLayouts()
        ),
        array(
          'id'          => 'single_match_sidebar',
          'label'       => esc_html__('Sidebar', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'sidebar_select'
        ),
        array(
          'id'          => 'single_match_boxed_cont',
          'label'       => esc_html__('Boxed Content', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_match_comments',
          'label'       => esc_html__('Show Comments', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_match_revslider',
          'label'       => esc_html__('Use Revolution Slider', 'youplay'),
          'desc'        => esc_html__('Title will be hidden', 'youplay'),
          'std'         => 'off',
          'type'        => 'on-off',
        ),
        array(
          'id'          => 'single_match_revslider_alias',
          'label'       => esc_html__('Slider', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'revslider-select',
        ),

        array(
          'id'          => 'tab_match_banner',
          'label'       => esc_html__('Banner', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'tab',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_match_banner_image',
          'label'       => esc_html__('Image', 'youplay'),
          'desc'        => '',
          'std'         => yp_opts('single_match_banner_image'),
          'type'        => 'upload',
        ),
        array(
          'id'          => 'single_match_banner_size',
          'label'       => esc_html__('Banner Size', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'full',
              'label'       => esc_html__('Full', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'mid',
              'label'       => esc_html__('Mid', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'small',
              'label'       => esc_html__('Small', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'xsmall',
              'label'       => esc_html__('Extra Small', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_match_banner_parallax',
          'label'       => esc_html__('Banner Parallax', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
          array(
              'id'          => 'single_match_banner_parallax_speed_type',
              'label'       => esc_html__('Parallax Image Speed', 'youplay'),
              'desc'        => '',
              'std'         => 'default',
              'type'        => 'select',
              'choices'     => array(
                  array(
                      'value'       => 'default',
                      'label'       => esc_html__('Default', 'youplay'),
                      'src'         => ''
                  ),
                  array(
                      'value'       => 'custom',
                      'label'       => esc_html__('Custom', 'youplay'),
                      'src'         => ''
                  )
              ),
          ),
          array(
              'id'          => 'single_match_banner_parallax_speed',
              'label'       => '',
              'desc'        => '',
              'std'         => '0.4',
              'type'        => 'numeric-slider',
              'min_max_step'=> '-1,2,0.1',
              'condition'   => 'single_match_banner_parallax_speed_type:is(custom)',
              'operator'    => 'and',
          ),
      ), getYouplayBackgroundMetabox())
    );

    if (function_exists('ot_register_meta_box')) {
      ot_register_meta_box( $meta_box );
    }
  }
}


/**
------------------
PRODUCT METABOXES
------------------
*/
add_action( 'admin_init', 'yp_product_meta_boxes' );

if ( ! function_exists( 'yp_product_meta_boxes' ) ){

  function yp_product_meta_boxes() {
    //layout
    $meta_box = array(
      'id'        => 'product_custom_options',
      'title'     => esc_html__('Youplay Custom Options', 'youplay'),
      'desc'      => '',
      'pages'     => array( 'product' ),
      'context'   => 'normal',
      'priority'  => 'low',
      'fields'    => array_merge(array(
        array(
          'id'          => 'tab_product_layout',
          'label'       => esc_html__('Layout', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'tab',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_product_layout',
          'label'       => esc_html__('Layout','youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'radio-image',
          'class'       => '',
          'choices'     => getYouplayLayouts()
        ),
        array(
          'id'          => 'single_product_sidebar',
          'label'       => esc_html__('Sidebar', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'sidebar_select'
        ),
        array(
          'id'          => 'single_product_boxed_cont',
          'label'       => esc_html__('Boxed Content', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_product_revslider',
          'label'       => esc_html__('Use Revolution Slider', 'youplay'),
          'desc'        => esc_html__('Title will be hidden', 'youplay'),
          'std'         => 'off',
          'type'        => 'on-off',
        ),
        array(
          'id'          => 'single_product_revslider_alias',
          'label'       => esc_html__('Slider', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'revslider-select',
        ),

        array(
          'id'          => 'tab_product_banner',
          'label'       => esc_html__('Banner', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'tab',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_product_banner_image',
          'label'       => esc_html__('Image (by default used Featured Image)', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'upload',
        ),
        array(
          'id'          => 'single_product_banner_size',
          'label'       => esc_html__('Banner Size', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'full',
              'label'       => esc_html__('Full', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'mid',
              'label'       => esc_html__('Mid', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'small',
              'label'       => esc_html__('Small', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'xsmall',
              'label'       => esc_html__('Extra Small', 'youplay'),
              'src'         => ''
            )
          )
        ),
        array(
          'id'          => 'single_product_banner_parallax',
          'label'       => esc_html__('Banner Parallax', 'youplay'),
          'desc'        => '',
          'std'         => 'default',
          'type'        => 'select',
          'choices'     => array(
            array(
              'value'       => 'default',
              'label'       => esc_html__('Default', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'on',
              'label'       => esc_html__('On', 'youplay'),
              'src'         => ''
            ),
            array(
              'value'       => 'off',
              'label'       => esc_html__('Off', 'youplay'),
              'src'         => ''
            )
          )
        ),
          array(
              'id'          => 'single_product_banner_parallax_speed_type',
              'label'       => esc_html__('Parallax Image Speed', 'youplay'),
              'desc'        => '',
              'std'         => 'default',
              'type'        => 'select',
              'choices'     => array(
                  array(
                      'value'       => 'default',
                      'label'       => esc_html__('Default', 'youplay'),
                      'src'         => ''
                  ),
                  array(
                      'value'       => 'custom',
                      'label'       => esc_html__('Custom', 'youplay'),
                      'src'         => ''
                  )
              ),
          ),
          array(
              'id'          => 'single_product_banner_parallax_speed',
              'label'       => '',
              'desc'        => '',
              'std'         => '0.4',
              'type'        => 'numeric-slider',
              'min_max_step'=> '-1,2,0.1',
              'condition'   => 'single_product_banner_parallax_speed_type:is(custom)',
              'operator'    => 'and',
          ),

        array(
          'id'          => 'tab_product_additional_params',
          'label'       => esc_html__('Aditional Parameters', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'tab',
          'operator'    => 'and'
        ),
        array(
          'id'          => 'single_product_additional_params',
          'label'       => esc_html__('Show Additional Params', 'youplay'),
          'desc'        => '',
          'std'         => 'off',
          'type'        => 'on-off',
        ),
        array(
          'id'          => 'single_product_additional_params_title',
          'label'       => esc_html__('Title', 'youplay'),
          'desc'        => '',
          'std'         => esc_html__('System Requirements', 'youplay'),
          'type'        => 'text',
        ),
        array(
          'id'          => 'single_product_additional_params_cont',
          'label'       => esc_html__('Content Text', 'youplay'),
          'desc'        => '',
          'std'         => '',
          'type'        => 'textarea',
        )
      ), getYouplayBackgroundMetabox())
    );

    if (function_exists('ot_register_meta_box')) {
      ot_register_meta_box( $meta_box );
    }
  }
}





/**
------------------
Custom Sidebars Option Type
------------------
*/
// Add custom sidebar select option
if ( ! function_exists( 'youplay_add_sidebar_select_type' ) ) :
function youplay_add_sidebar_select_type( $array ) {
  $array['sidebar-select'] = 'Sidebar Select';
  return $array;
}
endif;
add_filter( 'ot_option_types_array', 'youplay_add_sidebar_select_type' );

// Show custom sidebar select option
if ( ! function_exists( 'ot_type_sidebar_select' ) ) :
function ot_type_sidebar_select( $args = array() ) {
  extract( $args );
  $has_desc = $field_desc ? true : false;
  echo '<div class="format-setting type-sidebar-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
  echo $has_desc ? '<div class="description">' . wp_kses_post( $field_desc ) . '</div>' : '';
    echo '<div class="format-setting-inner">';
      echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . $field_class . '">';

      /* get sidebar array */
      $sidebars = $GLOBALS['wp_registered_sidebars'];

      /* has sides */
      if ( ! empty( $sidebars ) ) {
        echo '<option value="">-- ' . esc_html__( 'Choose One', 'youplay' ) . ' --</option>';
        foreach ( $sidebars as $sidebar ) {
          echo '<option value="' . esc_attr( $sidebar['id'] ) . '"' . selected( $field_value, $sidebar['id'], false ) . '>' . esc_attr( $sidebar['name'] ) . '</option>';
        }
      } else {
        echo '<option value="">' . esc_html__( 'No Sidebars Found', 'youplay' ) . '</option>';
      }
      echo '</select>';
    echo '</div>';
  echo '</div>';
}
endif;
?>
