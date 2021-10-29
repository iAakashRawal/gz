<?php
/**
 * YP Tabs
 *
 * Example:
 * [yp_tabs boxed="false"]
 *    [yp_tab title="Home" active="true"]...[/yp_tab]
 *    [yp_tab title="Other"]...[/yp_tab]
 * [/yp_tabs]
 */

add_shortcode( 'yp_tabs', 'yp_tabs' );

if ( ! function_exists( 'yp_tabs' ) ) :
function yp_tabs($atts, $content = null) {
    global $youplay_tabs_id;

    if(!$youplay_tabs_id) {
      $youplay_tabs_id = 0;
    }
    $youplay_tabs_id++;

    extract(shortcode_atts(array(
        "boxed" => false,
        "class"  => ""
    ), $atts));

    if(yp_check($boxed)) {
        $class .= " container";
    }

    // Extract tab titles
    preg_match_all( '/yp_tab([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
    $tab_titles = array();
    if ( isset( $matches[1] ) ) {
        $tab_titles = $matches[1];
    }

    // prepare tabs nav
    $tabs_nav = '';
    $tabs_nav .= '<ul class="nav nav-tabs" role="tablist">';
    $activateTab = 0;
    foreach ( $tab_titles as $tab ) {
        $tab_atts = shortcode_parse_atts($tab[0]);
        if(!isset($tab_atts['active'])) {
          $tab_atts['active'] = false;
        }

        if(isset($tab_atts['title'])) {
            $tabID = esc_attr('tab' . '-' . $youplay_tabs_id . '-' . ( isset( $tab_atts['tab_id'] ) ? $tab_atts['tab_id'] : sanitize_title( $tab_atts['title'] ) ));
            $tabs_nav .=
                 '<li role="presentation" ' . (yp_check($tab_atts['active']) ? 'class="active"' : '') . '>
                    <a href="' . esc_url('#' .  $tabID) . '" aria-controls="' .  esc_attr($tabID) . '" role="tab" data-toggle="tab" aria-expanded="true">' . esc_html($tab_atts['title']) . '</a>
                  </li>';
        }
    }
    $tabs_nav .= '</ul>';

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    return '<div role="tabpanel" class="' . esc_attr($class) . '">'
                 . $tabs_nav .
                '<div class="tab-content">
                    ' . do_shortcode(yp_fix_content($content)) . '
                </div>
            </div>';
}
endif;

// each tab shortcode
add_shortcode( 'yp_tab', 'yp_tab' );

if ( ! function_exists( 'yp_tab' ) ) :
function yp_tab($atts, $content = null) {
    global $youplay_tabs_id;

    extract(shortcode_atts(array(
        "title"  => "",
        "tab_id" => null,
        "active" => "",
        "class"  => ""
    ), $atts));

    $tab_id = 'tab' . '-' . $youplay_tabs_id . '-' . ( isset( $tab_id ) ? $tab_id : sanitize_title( $title ) );

    $result = '';

    // additional classname for custom styles VC
    $class .= yp_get_css_tab_class($atts);

    if(isset($title)) {
        $result .= '<div role="tabpanel" class="tab-pane ' . (yp_check($active) ? 'active' : '') . ' ' . esc_attr($class) . '" id="' . esc_attr($tab_id) . '">
                        ' . do_shortcode($content) . '
                    </div>';
    }

    return $result;
}
endif;



/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_youplay_tabs" );
if ( ! function_exists( 'vc_youplay_tabs' ) ) :
function vc_youplay_tabs() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"                    => esc_html__("nK Tabs", 'youplay-core'),
           "base"                    => "yp_tabs",
           "category"                => "nK",
           "icon"                    => "icon-nk icon-nk-tabs",
           "show_settings_on_create" => false,
           "is_container"            => true,
           "admin_enqueue_js"        => youplay_core()->plugin_url . "shortcodes/js/yp-tabs-vc-view.js",
           "admin_enqueue_css"       => youplay_core()->plugin_url . "shortcodes/css/yp-tabs-vc-view.css",
           "params"                  => array_merge( array(
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
           ), yp_get_css_tab() ),
            "custom_markup" => "
            <div class='wpb_tabs_holder wpb_holder vc_container_for_children'>
            <ul class='tabs_controls'>
            </ul>
            %content%
            </div>",
           "default_content" => "
                [yp_tab title='" . esc_html__( 'Tab 1', 'youplay-core' ) . "' active='true'][/yp_tab]
                [yp_tab title='" . esc_html__( 'Tab 2', 'youplay-core' ) . "'][/yp_tab]",
           "js_view" => "YPTabsView"
        ) );
    }
}
endif;

add_action( "after_setup_theme", "vc_youplay_single_tab" );
if ( ! function_exists( 'vc_youplay_single_tab' ) ) :
function vc_youplay_single_tab() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"             => esc_html__("nK Single Tab", 'youplay-core'),
           "base"             => "yp_tab",
           "category"         => "nK",
           "icon"             => "icon-nk icon-nk-tabs",
           "allowed_container_element" => "vc_row",
           "content_element"  => false,
           "is_container"     => true,
           "params"           => array_merge( array(
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Title", 'youplay-core'),
                 "param_name"  => "title",
                 "value"       => esc_html__("Youplay", 'youplay-core'),
                 "description" => "",
              ),
              array(
                  "type"       => "checkbox",
                  "heading"    => esc_html__( "Active", 'youplay-core' ),
                  "param_name" => "active",
                  "value"      => array( "" => true )
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Custom Classes", 'youplay-core'),
                 "param_name"  => "class",
                 "value"       => "",
                 "description" => "",
              ),
           ), yp_get_css_tab() ),
           "js_view" => "YPTabView"
        ) );
    }
}
endif;



if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_yp_tabs extends WPBakeryShortCodesContainer {
        static $filter_added = false;
        protected $controls_css_settings = 'out-tc vc_controls-content-widget';
        protected $controls_list = array( 'edit', 'clone', 'delete' );

        public function __construct( $settings ) {
            parent::__construct( $settings );
            // WPBakeryVisualComposer::getInstance()->addShortCode( array( 'base' => 'vc_tab' ) );
            if ( ! self::$filter_added ) {
                $this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
                self::$filter_added = true;
            }
        }

        public function contentAdmin( $atts, $content = null ) {
            $width = $custom_markup = '';
            $shortcode_attributes = array( 'width' => '1/1' );
            foreach ( $this->settings['params'] as $param ) {
                if ( $param['param_name'] != 'content' ) {
                    if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
                        $shortcode_attributes[ $param['param_name'] ] = $param['value'];
                    } elseif ( isset( $param['value'] ) ) {
                        $shortcode_attributes[ $param['param_name'] ] = $param['value'];
                    }
                } else if ( $param['param_name'] == 'content' && $content == null ) {
                    $content = $param['value'];
                }
            }
            extract( shortcode_atts(
                $shortcode_attributes
                , $atts ) );

            // Extract tab titles

            preg_match_all( '/yp_tab title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );

            $output = '';
            $tab_titles = array();

            if ( isset( $matches[0] ) ) {
                $tab_titles = $matches[0];
            }
            $tmp = '';
            if ( count( $tab_titles ) ) {
                $tmp .= '<ul class="clearfix tabs_controls">';
                foreach ( $tab_titles as $tab ) {
                    preg_match( '/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
                    if ( isset( $tab_matches[1][0] ) ) {
                        $tmp .= '<li><a href="#tab-' . ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) . '">' . $tab_matches[1][0] . '</a></li>';

                    }
                }
                $tmp .= '</ul>' . "\n";
            } else {
                $output .= do_shortcode( $content );
            }

            $elem = $this->getElementHolder( $width );

            $iner = '';
            foreach ( $this->settings['params'] as $param ) {
                $custom_markup = '';
                $param_value = ! empty( ${$param['param_name']} ) ? ${$param['param_name']} : '';
                if ( is_array( $param_value ) ) {
                    // Get first element from the array
                    reset( $param_value );
                    $first_key = key( $param_value );
                    $param_value = $param_value[ $first_key ];
                }
                $iner .= $this->singleParamHtmlHolder( $param, $param_value );
            }

            if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
                if ( $content != '' ) {
                    $custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
                } else if ( $content == '' && isset( $this->settings["default_content"] ) && $this->settings["default_content"] != '' ) {
                    $custom_markup = str_ireplace( "%content%", $this->settings["default_content"], $this->settings["custom_markup"] );
                } else {
                    $custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
                }
                $iner .= do_shortcode( $custom_markup );
            }
            $elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
            $output = $elem;

            return $output;
        }

        public function getTabTemplate() {
            return '<div class="wpb_template">' . do_shortcode( '[yp_tab title="Tab" tab_id=""][/yp_tab]' ) . '</div>';
        }

        public function setCustomTabId( $content ) {
            return preg_replace( '/tab\_id\=\"([^\"]+)\"/', 'tab_id="$1-' . time() . '"', $content );
        }
    }
}
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {

    class WPBakeryShortCode_yp_tab extends WPBakeryShortCodesContainer {
        protected $controls_css_settings = 'tc vc_control-container';
        protected $controls_list = array( 'add', 'edit', 'clone', 'delete' );
        protected $predefined_atts = array(
            'tab_id' => TAB_TITLE,
            'title' => ''
        );
        protected $controls_template_file = 'editors/partials/backend_controls_tab.tpl.php';

        public function __construct( $settings ) {
            parent::__construct( $settings );
        }

        public function customAdminBlockParams() {
            return ' id="tab-' . $this->atts['tab_id'] . '"';
        }

        public function mainHtmlBlockParams( $width, $i ) {
            return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
        }

        public function containerHtmlBlockParams( $width, $i ) {
            return 'class="wpb_column_container vc_container_for_children"';
        }

        public function getColumnControls($controls = full, $extended_css = '') {
            return $this->getColumnControlsModular( $extended_css );
        }
    }
}
