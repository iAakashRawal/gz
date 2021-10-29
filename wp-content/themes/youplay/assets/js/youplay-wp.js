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
/******/ 	return __webpack_require__(__webpack_require__.s = 6);
/******/ })
/************************************************************************/
/******/ ({

/***/ 6:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(7);


/***/ }),

/***/ 7:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


!function ($) {
  var $window = $(window);

  // add sticky icon
  $('.youplay-news .news-one.sticky h2').each(function () {
    $(this).prepend('<span class="fas fa-thumbtack fa-xs"></span>');
  });

  // fix navbar position if admin bar showed
  $(function () {
    var $adminBar = $('#wpadminbar');
    var $navbar = $('.navbar-youplay');
    var scrollTimeout;
    if ($adminBar.length && $navbar.length) {
      $window.on('scroll resize load', function () {
        var rect = $adminBar[0].getBoundingClientRect();
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(function () {
          $navbar[0].style.setProperty('top', Math.max(0, rect.top + rect.height) + 'px', 'important');
        }, 200);
      });
    }
  });

  // fix widget area rss
  $('.widget_rss cite, .widget_rss .rss-date').addClass('date');

  // fix form selects and inputs
  // for sidebar widgets, buddypress and rtmedia
  $('.side-block, .buddypress .youplay-content, .rtmedia-container').each(function () {
    var $this = $(this);

    $this.find('select').each(function () {
      var $select = $(this);
      if (!$select.parent('.youplay-select').length) {
        $select.wrap('<div class="youplay-select">');
      }
    });
    $this.find('input:not([type=button]):not([type=checkbox]):not([type=file]):not([type=hidden]):not([type=image]):not([type=radio]):not([type=reset]):not([type=submit]):not([type=range])').each(function () {
      var $input = $(this);
      if (!$input.parent('.youplay-input').length) {
        $input.wrap('<div class="youplay-input">');
      }
    });
    $this.find('textarea:not(.wp-editor-area)').each(function () {
      var $textarea = $(this);
      if (!$textarea.parent('.youplay-textarea').length) {
        $textarea.wrap('<div class="youplay-textarea">');
      }
    });
    $this.find('input[type=button], input[type=submit]:not([name="rtmedia-upload"])').each(function () {
      var $button = $(this);
      if (!$button.parent('.btn').length) {
        $button.wrap('<span class="btn">' + $button.val() + '</span>');
      }
    });
    $this.find('button:not(.btn)').each(function () {
      $(this).addClass('btn');
    });
  });

  // update navbar cart products count and subtotal
  var $cart = $('.navbar-youplay .dropdown-cart');
  $(document.body).on('added_to_cart wc_fragments_loaded wc_fragments_refreshed', function (e, a) {
    var $count = $cart.find('.nav_products_count');
    var $subtotal = $cart.find('.nav_products_subtotal');

    var count = $cart.find('[data-cart-count]').attr('data-cart-count') || '';
    var subtotal = $cart.find('[data-cart-subtotal]').attr('data-cart-subtotal') || '';

    $count[count ? 'show' : 'hide']();
    $count.html(count);

    $subtotal[subtotal ? 'show' : 'hide']();
    $subtotal.html(subtotal);
  });

  /* fix BuddyPress alerts */
  $('.buddypress .bp-template-notice, .buddypress #message').each(function () {
    var $alert = $(this);
    $alert.addClass('alert');
    $alert.attr('id', '');

    if ($alert.hasClass('updated')) {
      $alert.addClass('alert-success');
      $alert.removeClass('updated');
    } else if ($alert.hasClass('warning')) {
      $alert.addClass('alert-warning');
      $alert.removeClass('warning');
    } else if ($alert.hasClass('error')) {
      $alert.addClass('alert-danger');
      $alert.removeClass('error');
    } else if ($alert.hasClass('info')) {
      $alert.addClass('alert-info');
      $alert.removeClass('info');
    }

    $alert.children('p').addClass('m-0');
  });

  /* fix BuddyPress notification settings */
  $('.buddypress table.notification-settings, .buddypress table.messages-notices').addClass('table table-hover');

  /* WooCommerce prevent review without rating */
  $('body').on('click', '#respond #submit', function () {
    if (typeof wc_single_product_params === 'undefined') {
      return;
    }

    var $rating = $(this).closest('#respond').find('.youplay-rating');
    var $form = $(this).closest('form');
    var formData = $form.serializeArray();
    var rating = false;

    for (var k = 0; k < formData.length; k++) {
      if (formData[k].name === 'rating') {
        rating = formData[k].value;
        break;
      }
    }

    if ($rating.length > 0 && !rating && wc_single_product_params.review_rating_required === 'yes') {
      window.alert(wc_single_product_params.i18n_required_rating_text);

      return false;
    }
  });

  /* WooCommerce change shop_attributes table class */
  $('.shop_attributes').removeClass('shop_attributes').addClass('wp_shop_attributes table table-bordered table-hover');
}(jQuery);

/***/ })

/******/ });