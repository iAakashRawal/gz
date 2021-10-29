<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Youplay
 */

if ( ! function_exists( 'yp_sanitize_class' ) ) :
function yp_sanitize_class($classes) {
    if (!is_array($classes)) {
        $classes = explode(' ', $classes);
    }

    foreach($classes as $k => $v){
        $classes[$k] = sanitize_html_class($v);
    }

    return join(' ', $classes);

    return $classes;
}
endif;


/**
 * Get content and sidebar sizes, sidebar name
 */
if ( ! function_exists( 'yp_get_layout_data' ) ) :
    function yp_get_layout_data() {
        $post_type = get_post_type();
        if(!$post_type) {
            $post_type = 'page';
        }

        // get layout name
        $layout = 'single_page_layout';
        if ($post_type === 'match') {
            $layout = 'single_match_layout';
        } else if (function_exists('is_woocommerce') && is_woocommerce()) {
            $layout = 'single_product_layout';
        } else if (function_exists('is_buddypress') && is_buddypress()) {
            $layout = 'buddypress_layout';
        } else if (function_exists('is_bbpress') && is_bbpress()) {
            $layout = 'press_layout';
        } else if (is_single()) {
            $layout = 'single_post_layout';
        } else if (is_search()) {
            $layout = 'search_page_layout';
        } else if (is_archive() || is_home()) {
            $layout = 'archive_layout';
        }

        // get side of sidebar
        $side = strpos(yp_opts($layout, true), 'side-cont') !== false
            ? 'left'
            : (strpos(yp_opts($layout, true), 'cont-side') !== false
                ? 'right'
                : false);

        // get custom sidebar
        $sidebar_name = yp_opts('single_' . $post_type . '_sidebar', true);
        if ( ! $sidebar_name ) {
            if ( $layout === 'single_match_layout' ) {
                $sidebar_name = 'matches_sidebar';
            } else if ( $layout === 'single_product_layout' ) {
                $sidebar_name = 'woocommerce_sidebar';
            } else if ( $layout === 'buddypress_layout' ) {
                $sidebar_name = 'buddypress_sidebar';
            } else if ( $layout === 'press_layout' ) {
                $sidebar_name = 'bbpress_sidebar';
            } else {
                $sidebar_name = 'sidebar-1';
            }
        }

        $is_active_sidebar = $side && is_active_sidebar( $sidebar_name );

        return array(
            'is_active_sidebar' => $is_active_sidebar,
            'sidebar_name' => $sidebar_name,
            'sidebar_class' => $is_active_sidebar ? ('col-md-3' . ($side === 'left' ? ' col-md-pull-9' : '')) : '',
            'content_class' =>  $is_active_sidebar ? ('col-md-9' . ($side === 'left' ? ' col-md-push-3' : '')) : ('col-xs-12' . ($post_type === 'page' ? ' p-0' : ''))
        );
    }
endif;


/**
 * Fix Content for Shortcodes
 */
if ( ! function_exists( 'yp_fix_content' ) ) :
function yp_fix_content($content) {
    // fix for stupid </p> tag in start of string
    if (substr($content, 0, strlen("</p>")) == "</p>") {
        $content = substr($content, strlen("</p>"));
    }

    // fix for stupid <p> tag in end of string
    $content = preg_replace('/<p>$/', '', $content);

    // remove some <br> tags near shortcodes
    $content = str_replace( "]<br />","]", ( substr( $content, 0 , 6 ) == "<br />" ? substr( $content, 6 ): $content ) );

    // remove some <p> tags near shortcodes
    $content = str_replace( "]</p>","]", $content );
    $content = str_replace( "<p>[","[", $content );

    return $content;
}
endif;

/**
 * Get Rating HTML
 */
if ( ! function_exists( 'yp_get_rating' ) ) :
function yp_get_rating($rating, $class = '') {
    if(( yp_check($rating) || $rating == 0) && is_numeric($rating)) {
        return '<div class="rating ' . esc_attr($class) . '" data-rating="' . esc_attr( $rating ) . '" title="' . sprintf( esc_attr__( 'Rated %s out of 5', 'youplay' ), $rating ) . '">
                    <div class="rating-front" style="width: ' . ( max(0, min( 100, 100 * $rating / 5 ) ) ) . '%;">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                    <div class="rating-back">
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                        <i class="far fa-star"></i>
                    </div>
                </div>';
    } else {
        return "";
    }
}
endif;

/**
 * Get avatar URL
 * http://wordpress.stackexchange.com/questions/59442/how-do-i-get-the-avatar-url-instead-of-an-html-img-tag-when-using-get-avatar
 */
if ( ! function_exists( 'yp_get_avatar_url' ) ) :
function yp_get_avatar_url($get_avatar){
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return esc_url($matches[1]);
}
endif;

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
if ( ! function_exists( 'youplay_body_classes' ) ) :
function youplay_body_classes( $classes ) {
  // Adds a class of group-blog to blogs with more than 1 published author.
  if ( is_multi_author() ) {
    $classes[] = 'group-blog';
  }

  foreach($classes as $key => $class) {
      if($class == 'date') {
          $classes[$key] = 'archive-date';
      }
  }

  return $classes;
}
endif;
add_filter( 'body_class', 'youplay_body_classes' );


/**
 * Title Tag filter for 404
 */
add_filter( 'wp_title', 'yp_title', 10, 2 );
if ( ! function_exists( 'yp_title' ) ) :
function yp_title( $title ) {
    if( is_404() ) {
        $title = yp_opts('404_title');
    }
    return $title;
}
endif;


/**
 * Get image sizes with 'full'
 */
if ( ! function_exists( 'yp_get_intermediate_image_sizes' ) ) :
function yp_get_intermediate_image_sizes( $for_options = false ) {
    $sizes = get_intermediate_image_sizes();
    $sizes[] = 'full';

    if ( $for_options ) {
        $old_sizes = $sizes;
        $sizes = array();

        foreach($old_sizes as $size) {
            $sizes[] = array(
                'value' => $size,
                'label' => $size,
                'src'   => ''
            );
        }
    }

    return $sizes;
}
endif;


/**
 * Get an HTML img element representing an image path or id
 */
if ( ! function_exists( 'youplay_get_image' ) ) :
    function youplay_get_image( $image_id_or_path = null, $size = 'thumbnail', $icon = false, $attr = '' ) {
        $image_id = $image_id_or_path;

        if ( filter_var(filter_var($image_id_or_path, FILTER_SANITIZE_URL), FILTER_VALIDATE_URL) ) {
            $image_id = attachment_url_to_postid( $image_id_or_path );

            if (is_numeric($image_id) && $image_id == 0) {
                $return = '<img src="' . esc_url( $image_id_or_path ) . '"';
                if ( isset( $attr ) && ! empty( $attr ) && is_array( $attr ) ) {
                    foreach ( $attr as $key => $attribute ) {
                        if ( 'class' === $key ) {
                            $return .= ' class="' . esc_attr( $attribute ) . '"';
                        }
                    }
                }
                $alt = '';
                $return .= ' alt="' . esc_attr( $alt ) . '" />';
                return $return;
            }
        }
        if (is_numeric($image_id) && $image_id !== 0) {
            return wp_get_attachment_image( $image_id, $size, $icon, $attr );
        }
        return null;
    }
endif;


/**
 * Remove admin bar top margin
 */
add_action('get_header', 'remove_admin_login_header');
if ( ! function_exists( 'remove_admin_login_header' ) ) :
function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
endif;


/**
 * Add active classname for menu item
 */
add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);
if ( ! function_exists( 'special_nav_class' ) ) :
function special_nav_class($classes, $item){
     if( in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes) ){
          $classes[] = 'active ';
     }
     return $classes;
}
endif;


/**
 * Responsive video embed
 */
add_filter( 'embed_oembed_html', 'yp_oembed_filter', 10, 4 );
if ( ! function_exists( 'yp_oembed_filter' ) ) :
function yp_oembed_filter($html, $url, $attr, $post_ID) {
    $classes = '';
    if (strpos($url, 'youtube') > 0 || strpos($url, 'youtu.be') > 0) {
        $classes .= ' responsive-embed responsive-embed-16x9 embed-youtube';
    } else if (strpos($url, 'vimeo') > 0) {
        $classes .= ' responsive-embed responsive-embed-16x9 embed-vimeo';
    } else if (strpos($url, 'twitter') > 0) {
        $classes .= ' embed-twitter';
    }

    $return = '<div class="' . esc_attr($classes) . '">' . $html . '</div>';
    return $return;
}
endif;

/**
 * Get all terms for autocomplete visual composer selects and for post shortcodes
 */
if ( ! function_exists( 'yp_get_terms' ) ) :
function yp_get_terms() {
  $terms_list_vc = array();
  $terms_list = get_terms( get_object_taxonomies( get_post_types( array(
    'public' => false,
    'name' => 'attachment',
  ), 'names', 'NOT' ) ) );
  foreach($terms_list as $term) {
    $terms_list_vc[] = array(
      "value" => $term->term_id,
      "label" => $term->name,
      "group" => $term->taxonomy
    );
  }

  return $terms_list_vc;
}
endif;

/**
 * Removes WordPress SEO Title Filter On BuddyPress Pages
 */
if ( ! function_exists( 'youplay_remove_bp_wpseo_title' ) && function_exists( 'wpseo_auto_load' ) ) {
    function youplay_remove_bp_wpseo_title() {
        // NOTE: prevent theme check errors.
//        if ( function_exists( 'bp_is_blog_page' ) && !bp_is_blog_page() ) {
//            $youplay_front_end = WPSEO_Frontend::get_instance();
//            remove_filter( 'pre_get_document_title', array( $youplay_front_end, 'title' ), 15 );
//        }
    }
    add_action( 'init', 'youplay_remove_bp_wpseo_title' );
}

/**
 * Get timezones list
 * from: http://hilios.github.io/jQuery.countdown/examples/timezone-aware.html
 */
if ( ! function_exists( 'youplay_get_tz_list' ) ) {
    function youplay_get_tz_list($opts_format = false) {
        $tz = array(
            'auto', 'Africa/Abidjan', 'Africa/Accra', 'Africa/Addis_Ababa', 'Africa/Algiers', 'Africa/Asmara', 'Africa/Asmera', 'Africa/Bamako', 'Africa/Bangui', 'Africa/Banjul', 'Africa/Bissau', 'Africa/Blantyre', 'Africa/Brazzaville', 'Africa/Bujumbura', 'Africa/Cairo', 'Africa/Casablanca', 'Africa/Ceuta', 'Africa/Conakry', 'Africa/Dakar', 'Africa/Dar_es_Salaam', 'Africa/Djibouti', 'Africa/Douala', 'Africa/El_Aaiun', 'Africa/Freetown', 'Africa/Gaborone', 'Africa/Harare', 'Africa/Johannesburg', 'Africa/Juba', 'Africa/Kampala', 'Africa/Khartoum', 'Africa/Kigali', 'Africa/Kinshasa', 'Africa/Lagos', 'Africa/Libreville', 'Africa/Lome', 'Africa/Luanda', 'Africa/Lubumbashi', 'Africa/Lusaka', 'Africa/Malabo', 'Africa/Maputo', 'Africa/Maseru', 'Africa/Mbabane', 'Africa/Mogadishu', 'Africa/Monrovia', 'Africa/Nairobi', 'Africa/Ndjamena', 'Africa/Niamey', 'Africa/Nouakchott', 'Africa/Ouagadougou', 'Africa/Porto-Novo', 'Africa/Sao_Tome', 'Africa/Timbuktu', 'Africa/Tripoli', 'Africa/Tunis', 'Africa/Windhoek', 'America/Adak', 'America/Anchorage', 'America/Anguilla', 'America/Antigua', 'America/Araguaina', 'America/Argentina/Buenos_Aires', 'America/Argentina/Catamarca', 'America/Argentina/ComodRivadavia', 'America/Argentina/Cordoba', 'America/Argentina/Jujuy', 'America/Argentina/La_Rioja', 'America/Argentina/Mendoza', 'America/Argentina/Rio_Gallegos', 'America/Argentina/Salta', 'America/Argentina/San_Juan', 'America/Argentina/San_Luis', 'America/Argentina/Tucuman', 'America/Argentina/Ushuaia', 'America/Aruba', 'America/Asuncion', 'America/Atikokan', 'America/Atka', 'America/Bahia', 'America/Bahia_Banderas', 'America/Barbados', 'America/Belem', 'America/Belize', 'America/Blanc-Sablon', 'America/Boa_Vista', 'America/Bogota', 'America/Boise', 'America/Buenos_Aires', 'America/Cambridge_Bay', 'America/Campo_Grande', 'America/Cancun', 'America/Caracas', 'America/Catamarca', 'America/Cayenne', 'America/Cayman', 'America/Chicago', 'America/Chihuahua', 'America/Coral_Harbour', 'America/Cordoba', 'America/Costa_Rica', 'America/Creston', 'America/Cuiaba', 'America/Curacao', 'America/Danmarkshavn', 'America/Dawson', 'America/Dawson_Creek', 'America/Denver', 'America/Detroit', 'America/Dominica', 'America/Edmonton', 'America/Eirunepe', 'America/El_Salvador', 'America/Ensenada', 'America/Fort_Wayne', 'America/Fortaleza', 'America/Glace_Bay', 'America/Godthab', 'America/Goose_Bay', 'America/Grand_Turk', 'America/Grenada', 'America/Guadeloupe', 'America/Guatemala', 'America/Guayaquil', 'America/Guyana', 'America/Halifax', 'America/Havana', 'America/Hermosillo', 'America/Indiana/Indianapolis', 'America/Indiana/Knox', 'America/Indiana/Marengo', 'America/Indiana/Petersburg', 'America/Indiana/Tell_City', 'America/Indiana/Vevay', 'America/Indiana/Vincennes', 'America/Indiana/Winamac', 'America/Indianapolis', 'America/Inuvik', 'America/Iqaluit', 'America/Jamaica', 'America/Jujuy', 'America/Juneau', 'America/Kentucky/Louisville', 'America/Kentucky/Monticello', 'America/Knox_IN', 'America/Kralendijk', 'America/La_Paz', 'America/Lima', 'America/Los_Angeles', 'America/Louisville', 'America/Lower_Princes', 'America/Maceio', 'America/Managua', 'America/Manaus', 'America/Marigot', 'America/Martinique', 'America/Matamoros', 'America/Mazatlan', 'America/Mendoza', 'America/Menominee', 'America/Merida', 'America/Metlakatla', 'America/Mexico_City', 'America/Miquelon', 'America/Moncton', 'America/Monterrey', 'America/Montevideo', 'America/Montreal', 'America/Montserrat', 'America/Nassau', 'America/New_York', 'America/Nipigon', 'America/Nome', 'America/Noronha', 'America/North_Dakota/Beulah', 'America/North_Dakota/Center', 'America/North_Dakota/New_Salem', 'America/Ojinaga', 'America/Panama', 'America/Pangnirtung', 'America/Paramaribo', 'America/Phoenix', 'America/Port-au-Prince', 'America/Port_of_Spain', 'America/Porto_Acre', 'America/Porto_Velho', 'America/Puerto_Rico', 'America/Rainy_River', 'America/Rankin_Inlet', 'America/Recife', 'America/Regina', 'America/Resolute', 'America/Rio_Branco', 'America/Rosario', 'America/Santa_Isabel', 'America/Santarem', 'America/Santiago', 'America/Santo_Domingo', 'America/Sao_Paulo', 'America/Scoresbysund', 'America/Shiprock', 'America/Sitka', 'America/St_Barthelemy', 'America/St_Johns', 'America/St_Kitts', 'America/St_Lucia', 'America/St_Thomas', 'America/St_Vincent', 'America/Swift_Current', 'America/Tegucigalpa', 'America/Thule', 'America/Thunder_Bay', 'America/Tijuana', 'America/Toronto', 'America/Tortola', 'America/Vancouver', 'America/Virgin', 'America/Whitehorse', 'America/Winnipeg', 'America/Yakutat', 'America/Yellowknife', 'Antarctica/Casey', 'Antarctica/Davis', 'Antarctica/DumontDUrville', 'Antarctica/Macquarie', 'Antarctica/Mawson', 'Antarctica/McMurdo', 'Antarctica/Palmer', 'Antarctica/Rothera', 'Antarctica/South_Pole', 'Antarctica/Syowa', 'Antarctica/Troll', 'Antarctica/Vostok', 'Arctic/Longyearbyen', 'Asia/Aden', 'Asia/Almaty', 'Asia/Amman', 'Asia/Anadyr', 'Asia/Aqtau', 'Asia/Aqtobe', 'Asia/Ashgabat', 'Asia/Ashkhabad', 'Asia/Baghdad', 'Asia/Bahrain', 'Asia/Baku', 'Asia/Bangkok', 'Asia/Beirut', 'Asia/Bishkek', 'Asia/Brunei', 'Asia/Calcutta', 'Asia/Chita', 'Asia/Choibalsan', 'Asia/Chongqing', 'Asia/Chungking', 'Asia/Colombo', 'Asia/Dacca', 'Asia/Damascus', 'Asia/Dhaka', 'Asia/Dili', 'Asia/Dubai', 'Asia/Dushanbe', 'Asia/Gaza', 'Asia/Harbin', 'Asia/Hebron', 'Asia/Ho_Chi_Minh', 'Asia/Hong_Kong', 'Asia/Hovd', 'Asia/Irkutsk', 'Asia/Istanbul', 'Asia/Jakarta', 'Asia/Jayapura', 'Asia/Jerusalem', 'Asia/Kabul', 'Asia/Kamchatka', 'Asia/Karachi', 'Asia/Kashgar', 'Asia/Kathmandu', 'Asia/Katmandu', 'Asia/Khandyga', 'Asia/Kolkata', 'Asia/Krasnoyarsk', 'Asia/Kuala_Lumpur', 'Asia/Kuching', 'Asia/Kuwait', 'Asia/Macao', 'Asia/Macau', 'Asia/Magadan', 'Asia/Makassar', 'Asia/Manila', 'Asia/Muscat', 'Asia/Nicosia', 'Asia/Novokuznetsk', 'Asia/Novosibirsk', 'Asia/Omsk', 'Asia/Oral', 'Asia/Phnom_Penh', 'Asia/Pontianak', 'Asia/Pyongyang', 'Asia/Qatar', 'Asia/Qyzylorda', 'Asia/Rangoon', 'Asia/Riyadh', 'Asia/Saigon', 'Asia/Sakhalin', 'Asia/Samarkand', 'Asia/Seoul', 'Asia/Shanghai', 'Asia/Singapore', 'Asia/Srednekolymsk', 'Asia/Taipei', 'Asia/Tashkent', 'Asia/Tbilisi', 'Asia/Tehran', 'Asia/Tel_Aviv', 'Asia/Thimbu', 'Asia/Thimphu', 'Asia/Tokyo', 'Asia/Ujung_Pandang', 'Asia/Ulaanbaatar', 'Asia/Ulan_Bator', 'Asia/Urumqi', 'Asia/Ust-Nera', 'Asia/Vientiane', 'Asia/Vladivostok', 'Asia/Yakutsk', 'Asia/Yekaterinburg', 'Asia/Yerevan', 'Atlantic/Azores', 'Atlantic/Bermuda', 'Atlantic/Canary', 'Atlantic/Cape_Verde', 'Atlantic/Faeroe', 'Atlantic/Faroe', 'Atlantic/Jan_Mayen', 'Atlantic/Madeira', 'Atlantic/Reykjavik', 'Atlantic/South_Georgia', 'Atlantic/St_Helena', 'Atlantic/Stanley', 'Australia/ACT', 'Australia/Adelaide', 'Australia/Brisbane', 'Australia/Broken_Hill', 'Australia/Canberra', 'Australia/Currie', 'Australia/Darwin', 'Australia/Eucla', 'Australia/Hobart', 'Australia/LHI', 'Australia/Lindeman', 'Australia/Lord_Howe', 'Australia/Melbourne', 'Australia/NSW', 'Australia/North', 'Australia/Perth', 'Australia/Queensland', 'Australia/South', 'Australia/Sydney', 'Australia/Tasmania', 'Australia/Victoria', 'Australia/West', 'Australia/Yancowinna', 'Brazil/Acre', 'Brazil/DeNoronha', 'Brazil/East', 'Brazil/West', 'CET', 'CST6CDT', 'Canada/Atlantic', 'Canada/Central', 'Canada/East-Saskatchewan', 'Canada/Eastern', 'Canada/Mountain', 'Canada/Newfoundland', 'Canada/Pacific', 'Canada/Saskatchewan', 'Canada/Yukon', 'Chile/Continental', 'Chile/EasterIsland', 'Cuba', 'EET', 'EST', 'EST5EDT', 'Egypt', 'Eire', 'Etc/GMT', 'Etc/GMT+0', 'Etc/GMT+1', 'Etc/GMT+10', 'Etc/GMT+11', 'Etc/GMT+12', 'Etc/GMT+2', 'Etc/GMT+3', 'Etc/GMT+4', 'Etc/GMT+5', 'Etc/GMT+6', 'Etc/GMT+7', 'Etc/GMT+8', 'Etc/GMT+9', 'Etc/GMT-0', 'Etc/GMT-1', 'Etc/GMT-10', 'Etc/GMT-11', 'Etc/GMT-12', 'Etc/GMT-13', 'Etc/GMT-14', 'Etc/GMT-2', 'Etc/GMT-3', 'Etc/GMT-4', 'Etc/GMT-5', 'Etc/GMT-6', 'Etc/GMT-7', 'Etc/GMT-8', 'Etc/GMT-9', 'Etc/GMT0', 'Etc/Greenwich', 'Etc/UCT', 'Etc/UTC', 'Etc/Universal', 'Etc/Zulu', 'Europe/Amsterdam', 'Europe/Andorra', 'Europe/Athens', 'Europe/Belfast', 'Europe/Belgrade', 'Europe/Berlin', 'Europe/Bratislava', 'Europe/Brussels', 'Europe/Bucharest', 'Europe/Budapest', 'Europe/Busingen', 'Europe/Chisinau', 'Europe/Copenhagen', 'Europe/Dublin', 'Europe/Gibraltar', 'Europe/Guernsey', 'Europe/Helsinki', 'Europe/Isle_of_Man', 'Europe/Istanbul', 'Europe/Jersey', 'Europe/Kaliningrad', 'Europe/Kiev', 'Europe/Lisbon', 'Europe/Ljubljana', 'Europe/London', 'Europe/Luxembourg', 'Europe/Madrid', 'Europe/Malta', 'Europe/Mariehamn', 'Europe/Minsk', 'Europe/Monaco', 'Europe/Moscow', 'Europe/Nicosia', 'Europe/Oslo', 'Europe/Paris', 'Europe/Podgorica', 'Europe/Prague', 'Europe/Riga', 'Europe/Rome', 'Europe/Samara', 'Europe/San_Marino', 'Europe/Sarajevo', 'Europe/Simferopol', 'Europe/Skopje', 'Europe/Sofia', 'Europe/Stockholm', 'Europe/Tallinn', 'Europe/Tirane', 'Europe/Tiraspol', 'Europe/Uzhgorod', 'Europe/Vaduz', 'Europe/Vatican', 'Europe/Vienna', 'Europe/Vilnius', 'Europe/Volgograd', 'Europe/Warsaw', 'Europe/Zagreb', 'Europe/Zaporozhye', 'Europe/Zurich', 'GB', 'GB-Eire', 'GMT', 'GMT+0', 'GMT-0', 'GMT0', 'Greenwich', 'HST', 'Hongkong', 'Iceland', 'Indian/Antananarivo', 'Indian/Chagos', 'Indian/Christmas', 'Indian/Cocos', 'Indian/Comoro', 'Indian/Kerguelen', 'Indian/Mahe', 'Indian/Maldives', 'Indian/Mauritius', 'Indian/Mayotte', 'Indian/Reunion', 'Iran', 'Israel', 'Jamaica', 'Japan', 'Kwajalein', 'Libya', 'MET', 'MST', 'MST7MDT', 'Mexico/BajaNorte', 'Mexico/BajaSur', 'Mexico/General', 'NZ', 'NZ-CHAT', 'Navajo', 'PRC', 'PST8PDT', 'Pacific/Apia', 'Pacific/Auckland', 'Pacific/Bougainville', 'Pacific/Chatham', 'Pacific/Chuuk', 'Pacific/Easter', 'Pacific/Efate', 'Pacific/Enderbury', 'Pacific/Fakaofo', 'Pacific/Fiji', 'Pacific/Funafuti', 'Pacific/Galapagos', 'Pacific/Gambier', 'Pacific/Guadalcanal', 'Pacific/Guam', 'Pacific/Honolulu', 'Pacific/Johnston', 'Pacific/Kiritimati', 'Pacific/Kosrae', 'Pacific/Kwajalein', 'Pacific/Majuro', 'Pacific/Marquesas', 'Pacific/Midway', 'Pacific/Nauru', 'Pacific/Niue', 'Pacific/Norfolk', 'Pacific/Noumea', 'Pacific/Pago_Pago', 'Pacific/Palau', 'Pacific/Pitcairn', 'Pacific/Pohnpei', 'Pacific/Ponape', 'Pacific/Port_Moresby', 'Pacific/Rarotonga', 'Pacific/Saipan', 'Pacific/Samoa', 'Pacific/Tahiti', 'Pacific/Tarawa', 'Pacific/Tongatapu', 'Pacific/Truk', 'Pacific/Wake', 'Pacific/Wallis', 'Pacific/Yap', 'Poland', 'Portugal', 'ROC', 'ROK', 'Singapore', 'Turkey', 'UCT', 'US/Alaska', 'US/Aleutian', 'US/Arizona', 'US/Central', 'US/East-Indiana', 'US/Eastern', 'US/Hawaii', 'US/Indiana-Starke', 'US/Michigan', 'US/Mountain', 'US/Pacific', 'US/Pacific-New', 'US/Samoa', 'UTC', 'Universal', 'W-SU', 'WET', 'Zulu'
        );
        $tz2 = array();
        foreach($tz as $z) {
            $tz2[] = array(
                'value' => $z,
                'label' => $z
            );
        }
        return $opts_format ? $tz2 : $tz;
    }
}
