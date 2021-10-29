'use strict';

/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ({

/***/ 10:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(11);


/***/ }),

/***/ 11:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


(function ($) {
    var options = {
        dark: {
            main_color: '#D92B4C',
            back_color: '#160962',
            back_grey_color: '#30303D',
            text_color: '#fff',
            primary_color: '#2B6AD9',
            success_color: '#2BD964',
            info_color: '#2BD7D9',
            warning_color: '#EB8324',
            danger_color: '#D92B4C',

            skew_size: 7,
            navbar_height: 80,
            navbar_small_height: 50,
            banners_opacity: 50,
            images_opacity: 50,
            images_hover_opacity: 60
        },
        shooter: {
            main_color: '#C9572A',
            back_color: '#2b2b2b',
            back_grey_color: '#30303D',
            text_color: '#fff',
            primary_color: '#2B6AD9',
            success_color: '#2BD964',
            info_color: '#2BD7D9',
            warning_color: '#EB8324',
            danger_color: '#D92B4C',

            skew_size: 0,
            navbar_height: 80,
            navbar_small_height: 50,
            banners_opacity: 70,
            images_opacity: 100,
            images_hover_opacity: 70
        },
        anime: {
            main_color: '#b63a6b',
            back_color: '#490e48',
            back_grey_color: '#30303D',
            text_color: '#fff',
            primary_color: '#2B6AD9',
            success_color: '#2BD964',
            info_color: '#2BD7D9',
            warning_color: '#EB8324',
            danger_color: '#D92B4C',

            skew_size: 4,
            navbar_height: 80,
            navbar_small_height: 50,
            banners_opacity: 80,
            images_opacity: 80,
            images_hover_opacity: 50
        },
        light: {
            main_color: '#FF774F',
            back_color: '#C6C3D8',
            back_grey_color: '#D3D3D3',
            text_color: '#3C0732',
            primary_color: '#2B6AD9',
            success_color: '#2BD964',
            info_color: '#2BD7D9',
            warning_color: '#EB8324',
            danger_color: '#D92B4C',

            skew_size: 8,
            navbar_height: 80,
            navbar_small_height: 50,
            banners_opacity: 50,
            images_opacity: 50,
            images_hover_opacity: 60
        }
    };

    function changeSlider(selector, val) {
        $(selector).val(val);
        $(selector).siblings('.ot-numeric-slider-helper-input').val(val);
        $(selector).siblings('.ot-numeric-slider').slider('value', val);
    }
    function changeScheme(scheme) {
        if (options[scheme]) {
            var opt = options[scheme];

            $('input#theme_main_color').val(opt.main_color).trigger('change');
            $('input#theme_back_color').val(opt.back_color).trigger('change');
            $('input#theme_back_grey_color').val(opt.back_grey_color).trigger('change');
            $('input#theme_text_color').val(opt.text_color).trigger('change');
            $('input#theme_primary_color').val(opt.primary_color).trigger('change');
            $('input#theme_success_color').val(opt.success_color).trigger('change');
            $('input#theme_info_color').val(opt.info_color).trigger('change');
            $('input#theme_warning_color').val(opt.warning_color).trigger('change');
            $('input#theme_danger_color').val(opt.danger_color).trigger('change');

            changeSlider('input#theme_skew_size', opt.skew_size);
            changeSlider('input#theme_navbar_height', opt.navbar_height);
            changeSlider('input#theme_navbar_small_height', opt.navbar_small_height);
            changeSlider('input#theme_banners_opacity', opt.banners_opacity);
            changeSlider('input#theme_images_opacity', opt.images_opacity);
            changeSlider('input#theme_images_hover_opacity', opt.images_hover_opacity);
        }
    }

    $(document).on('change', 'select#theme_colors_from', function () {
        changeScheme(this.value);
    });
})(jQuery);

/***/ })

/******/ });