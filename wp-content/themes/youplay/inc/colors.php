<?php
/**
 * RGB-to-HSL and HSL-to-RGB Converter
 * Check http://www.michaelburri.ch/generate-different-shades-of-a-color/ for explanation
 * @author     Michael Burri, https://github.com/mpbzh
 * @license    GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// Validates hex color code and returns proper value
// Input: String - Format #ffffff, #fff, ffffff or fff
// Output: hex value - 3 byte (000000 if input is invalid)
if ( ! function_exists( 'validate_hex' ) ) :
function validate_hex($hex) {
    // Complete patterns like #ffffff or #fff
    if(preg_match("/^#([0-9a-fA-F]{6})$/", $hex) || preg_match("/^#([0-9a-fA-F]{3})$/", $hex)) {
        // Remove #
        $hex = substr($hex, 1);
    }

    // Complete patterns without # like ffffff or 000000
    if(preg_match("/^([0-9a-fA-F]{6})$/", $hex)) {
        return $hex;
    }

    // Short patterns without # like fff or 000
    if(preg_match("/^([0-9a-f]{3})$/", $hex)) {
        // Spread to 6 digits
        return substr($hex, 0, 1) . substr($hex, 0, 1) . substr($hex, 1, 1) . substr($hex, 1, 1) . substr($hex, 2, 1) . substr($hex, 2, 1);
    }

    // If input value is invalid return black
    return "000000";
}
endif;
// Converts hex color code to RGB color
// Input: String - Format #ffffff, #fff, ffffff or fff
// Output: Array(Hue, Saturation, Lightness) - Values from 0 to 1
if ( ! function_exists( 'hex2hsl' ) ) :
function hex2hsl($hex) {
    //Validate Hex Input
    $hex = validate_hex($hex);

    // Split input by color
    $hex = str_split($hex, 2);
    // Convert color values to value between 0 and 1
    $r = (hexdec($hex[0])) / 255;
    $g = (hexdec($hex[1])) / 255;
    $b = (hexdec($hex[2])) / 255;

    return rgb2hsl(array($r,$g,$b));
}
endif;
// Converts RGB color to HSL color
// Check http://en.wikipedia.org/wiki/HSL_and_HSV#Hue_and_chroma for
// details
// Input: Array(Red, Green, Blue) - Values from 0 to 1
// Output: Array(Hue, Saturation, Lightness) - Values from 0 to 1
if ( ! function_exists( 'rgb2hsl' ) ) :
function rgb2hsl($rgb) {
    // Fill variables $r, $g, $b by array given.
    list($r, $g, $b) = $rgb;

    // Determine lowest & highest value and chroma
    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $chroma = $max - $min;

    // Calculate Luminosity
    $l = ($max + $min) / 2;

    // If chroma is 0, the given color is grey
    // therefore hue and saturation are set to 0
    if ($chroma == 0)
    {
        $h = 0;
        $s = 0;
    }

    // Else calculate hue and saturation.
    // Check http://en.wikipedia.org/wiki/HSL_and_HSV for details
    else
    {
        switch($max) {
            case $r:
                $h_ = fmod((($g - $b) / $chroma), 6);
                if($h_ < 0) $h_ = (6 - fmod(abs($h_), 6)); // Bugfix: fmod() returns wrong values for negative numbers
                break;

            case $g:
                $h_ = ($b - $r) / $chroma + 2;
                break;

            case $b:
                $h_ = ($r - $g) / $chroma + 4;
                break;
            default:
                break;
        }

        $h = $h_ / 6;
        //*********************************************************************
        // https://en.wikipedia.org/wiki/HSL_and_HSV#Converting_to_RGB ***
        // Adjustment needed to compute correct value of $s (saturation). ***
        //*********************************************************************
        if ($l < 0.5) {
            $s = ($max-$min)/($max+$min);
        } else {
            $s = ($max-$min)/(2.0-$max-$min);
        }
        // $s = 1 - abs(2 * $l - 1);
    }

    // Return HSL Color as array
    return array($h, $s, $l);
}
endif;
// Converts HSL color to RGB color
// Input: Array(Hue, Saturation, Lightness) - Values from 0 to 1
// Output: Array(Red, Green, Blue) - Values from 0 to 1
if ( ! function_exists( 'hsl2rgb' ) ) :
function hsl2rgb($hsl) {
    // Fill variables $h, $s, $l by array given.
    list($h, $s, $l) = $hsl;

    // If saturation is 0, the given color is grey and only
    // lightness is relevant.
    if ($s == 0 ) {
        $rgb = array($l, $l, $l);
    }

    // Else calculate r, g, b according to hue.
    // Check http://en.wikipedia.org/wiki/HSL_and_HSV#From_HSL for details
    else
    {
        $chroma = (1 - abs(2*$l - 1)) * $s;
        $h_     = $h * 6;
        $x         = $chroma * (1 - abs((fmod($h_,2)) - 1)); // Note: fmod because % (modulo) returns int value!!
        $m = $l - round($chroma/2, 10); // Bugfix for strange float behaviour (e.g. $l=0.17 and $s=1)

             if($h_ >= 0 && $h_ < 1) $rgb = array(($chroma + $m), ($x + $m), $m);
        else if($h_ >= 1 && $h_ < 2) $rgb = array(($x + $m), ($chroma + $m), $m);
        else if($h_ >= 2 && $h_ < 3) $rgb = array($m, ($chroma + $m), ($x + $m));
        else if($h_ >= 3 && $h_ < 4) $rgb = array($m, ($x + $m), ($chroma + $m));
        else if($h_ >= 4 && $h_ < 5) $rgb = array(($x + $m), $m, ($chroma + $m));
        else if($h_ >= 5 && $h_ < 6) $rgb = array(($chroma + $m), $m, ($x + $m));
    }

    return $rgb;
}
endif;
// Converts RGB color to hex code
// Input: Array(Red, Green, Blue)
// Output: String hex value (#000000 - #ffffff)
if ( ! function_exists( 'rgb2hex' ) ) :
function rgb2hex($rgb) {
    list($r,$g,$b) = $rgb;
    $r = round(255 * $r);
    $g = round(255 * $g);
    $b = round(255 * $b);
    return "#".sprintf("%02X",$r).sprintf("%02X",$g).sprintf("%02X",$b);
}
endif;
/* HEX to RGB colors */
/* http://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/ */
if ( ! function_exists( 'hex2rgb' ) ) :
function hex2rgb($hex) {
    $hex = validate_hex($hex);

    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));
    $rgb = array($r, $g, $b);
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}
endif;
// Converts HSL color to RGB hex code
// Input: Array(Hue, Saturation, Lightness) - Values from 0 to 1
// Output: String hex value (#000000 - #ffffff)
if ( ! function_exists( 'hsl2hex' ) ) :
function hsl2hex($hsl) {
    $rgb = hsl2rgb($hsl);
    return rgb2hex($rgb);
}
endif;
