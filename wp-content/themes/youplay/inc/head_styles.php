<?php
if(!function_exists('yp_print_typography')) {
    function yp_print_typography($arr) {
        if(is_array($arr)) {
            foreach($arr as $k => $item) {
                if($k === "font-family") {
                    $fonts = get_theme_mod('ot_google_fonts', array());
                    if(isset($fonts[$item]['family'])) {
                        echo esc_html( $k ) . ": " . esc_html( $fonts[$item]['family'] ) . ", sans-serif;\n";
                    }
                } else {
                    echo esc_html( $k ) . ": " . esc_html( $item ) . ";\n";
                }
            }
        }
    }
}
?>

/* Custom Typography */
body {
    <?php yp_print_typography(yp_opts('fonts_typography_body')); ?>
}
h1, .h1 {
    <?php yp_print_typography(yp_opts('fonts_typography_heading1')); ?>
}
h2, .h2 {
    <?php yp_print_typography(yp_opts('fonts_typography_heading2')); ?>
}
h3, .h3 {
    <?php yp_print_typography(yp_opts('fonts_typography_heading3')); ?>
}
h4, .h4 {
    <?php yp_print_typography(yp_opts('fonts_typography_heading4')); ?>
}
h5, .h5 {
    <?php yp_print_typography(yp_opts('fonts_typography_heading5')); ?>
}
h6, .h6 {
    <?php yp_print_typography(yp_opts('fonts_typography_heading6')); ?>
}
.youplay-banner .info h1,
.youplay-banner .info h2,
.youplay-banner .info .h1,
.youplay-banner .info .h2 {
    <?php yp_print_typography(yp_opts('fonts_typography_banner_heading')); ?>
}


/* Navigation Logo Width */
<?php if (yp_opts('navigation_logo_width')) : ?>
.navbar-youplay .navbar-brand {
    width: <?php echo yp_opts('navigation_logo_width'); ?>px;
}
<?php endif; ?>
<?php if (yp_opts('navigation_logo_small_width')) : ?>
.navbar-youplay.navbar-small .navbar-brand {
    width: <?php echo yp_opts('navigation_logo_small_width'); ?>px;
}
<?php endif; ?>
.navbar-youplay .navbar-brand img {
    width: 100%;
}


/* Boxed Content */
.content-wrap {
    <?php if (yp_opts('general_boxed_content')) : ?>
        max-width: <?php echo yp_opts('general_boxed_content_size', true); ?>px;
    <?php else : ?>
        max-width: none;
    <?php endif; ?>
}


/* Custom Background */
body {
    <?php
    $background = yp_opts('general_background', true);
    if($background) {
        $url = yp_opts('general_background_image', true);
        $cover = yp_opts('general_background_cover', true);
        $fixed = yp_opts('general_background_fixed', true);

        if($url) {
            echo 'background-image: url("' . $url . '");';
        }
        if($cover) {
            echo 'background-size: cover;';
        }
        if($fixed) {
            echo 'background-attachment: fixed;';
        }
    }
    ?>
}
/* Content background image with opacity */
.content-wrap {
    <?php
    if (!function_exists('youplay_sass_darken')) :
    function youplay_sass_darken($hex, $amount = 0) {
        $hsl = hex2hsl($hex);
        if($amount) {
            $hsl[2] = $hsl[2] - $amount / 100;
            $hsl[2] = $hsl[2] < 0 ? 0 : $hsl[2];
        }
        return hsl2hex($hsl);
    }
    endif;
    if($background) {
        // content background color
        $theme_style = yp_opts('theme_style');
        $cont_color = youplay_sass_darken('#160962', 13);
        $opacity = yp_opts('general_content_bg_opacity', yp_opts('general_content_bg_opacity_metabox', true)) / 100;

        if($theme_style == 'light') {
            $cont_color = '#ffffff';
        } else if($theme_style == 'shooter') {
            $cont_color = youplay_sass_darken('#2b2b2b', 13);
        } else if($theme_style == 'anime') {
            $cont_color = youplay_sass_darken('#490e48', 13);
        } else if($theme_style == 'custom') {
            $cont_color = youplay_sass_darken(yp_opts('theme_back_color'), 13);
        }

        $rgb = hex2rgb($cont_color);

        echo 'background: rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',' . $opacity . ');';
    }
    ?>
}

/* User CSS */
<?php
echo yp_opts('general_custom_css');
