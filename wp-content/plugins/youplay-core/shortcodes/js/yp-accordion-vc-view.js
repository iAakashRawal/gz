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
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
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
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(1);


/***/ }),
/* 1 */
/***/ (function(module, exports) {

/* Custom View for YP Accordion */
!function ($) {
  if (typeof window.vc === 'undefined') {
    return;
  }

  window.YPAccordionView = vc.shortcode_view.extend({
    adding_new_tab: false,
    events: {
      'click .add_tab': 'addTab',
      'click > .vc_controls .column_delete, > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
      'click > .vc_controls .column_edit, > .vc_controls .vc_control-btn-edit': 'editElement',
      'click > .vc_controls .column_clone,> .vc_controls .vc_control-btn-clone': 'clone'
    },
    render: function render() {
      window.YPAccordionView.__super__.render.call(this); // check user role to add controls


      if (!this.hasUserAccess()) {
        return this;
      }

      this.$content.sortable({
        axis: "y",
        handle: "h3",
        stop: function stop(event, ui) {
          // IE doesn't register the blur when sorting
          // so trigger focusout handlers to remove .ui-state-focus
          ui.item.prev().triggerHandler("focusout");
          $(this).find('> .wpb_sortable').each(function () {
            var shortcode = $(this).data('model');
            shortcode.save({
              'order': $(this).index()
            }); // Optimize
          });
        }
      });
      return this;
    },
    changeShortcodeParams: function changeShortcodeParams(model) {
      var params, collapsible;

      window.YPAccordionView.__super__.changeShortcodeParams.call(this, model);

      params = model.get('params');
      collapsible = _.isString(params.collapsible) && params.collapsible === 'yes' ? true : false;

      if (this.$content.hasClass('ui-accordion')) {
        this.$content.accordion("option", "collapsible", collapsible);
      }
    },
    changedContent: function changedContent(view) {
      if (this.$content.hasClass('ui-accordion')) {
        this.$content.accordion('destroy');
      }

      var collapsible = _.isString(this.model.get('params').collapsible) && this.model.get('params').collapsible === 'yes' ? true : false;
      this.$content.accordion({
        header: "h3",
        navigation: false,
        autoHeight: true,
        heightStyle: "content",
        collapsible: collapsible,
        active: this.adding_new_tab === false && view.model.get('cloned') !== true ? 0 : view.$el.index()
      });
      this.adding_new_tab = false;
    },
    addTab: function addTab(e) {
      e.preventDefault(); // check user role to add controls

      if (!this.hasUserAccess()) {
        return false;
      }

      this.adding_new_tab = true;
      vc.shortcodes.create({
        shortcode: 'yp_accordion_tab',
        params: {
          title: window.i18nLocale.section
        },
        parent_id: this.model.id
      });
    },
    _loadDefaults: function _loadDefaults() {
      window.YPAccordionView.__super__._loadDefaults.call(this);
    }
  });
  window.YPAccordionTabView = window.VcColumnView.extend({
    events: {
      'click > [data-element_type] > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
      'click > [data-element_type] > .vc_controls .vc_control-btn-prepend': 'addElement',
      'click > [data-element_type] > .vc_controls .vc_control-btn-edit': 'editElement',
      'click > [data-element_type] > .vc_controls .vc_control-btn-clone': 'clone',
      'click > [data-element_type] > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
    },
    setContent: function setContent() {
      this.$content = this.$el.find('> [data-element_type] > .wpb_element_wrapper > .vc_container_for_children');
    },
    changeShortcodeParams: function changeShortcodeParams(model) {
      var params;

      window.YPAccordionTabView.__super__.changeShortcodeParams.call(this, model);

      params = model.get('params');

      if (_.isObject(params) && _.isString(params.title)) {
        this.$el.find('> h3 .tab-label').text(params.title);
      }
    },
    setEmpty: function setEmpty() {
      $('> [data-element_type]', this.$el).addClass('vc_empty-column');
      this.$content.addClass('vc_empty-container');
    },
    unsetEmpty: function unsetEmpty() {
      $('> [data-element_type]', this.$el).removeClass('vc_empty-column');
      this.$content.removeClass('vc_empty-container');
    }
  });
}(jQuery);

/***/ })
/******/ ]);