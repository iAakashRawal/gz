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
/******/ 	return __webpack_require__(__webpack_require__.s = 28);
/******/ })
/************************************************************************/
/******/ ({

/***/ 28:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(29);


/***/ }),

/***/ 29:
/***/ (function(module, exports) {

/* Custom View for YP Tabs */
!function ($) {
  if (typeof window.vc === 'undefined') {
    return;
  }

  window.YPTabsView = vc.shortcode_view.extend({
    new_tab_adding: false,
    events: {
      'click .add_tab': 'addTab',
      'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
      'click > .vc_controls .vc_control-btn-edit': 'editElement',
      'click > .vc_controls .vc_control-btn-clone': 'clone'
    },
    initialize: function initialize(params) {
      window.YPTabsView.__super__.initialize.call(this, params);

      _.bindAll(this, 'stopSorting');
    },
    render: function render() {
      window.YPTabsView.__super__.render.call(this);

      this.$tabs = this.$el.find('.wpb_tabs_holder'); // remove default unused tabs

      this.$tabs.find('.tab-pane').remove();
      this.createAddTabButton();
      return this;
    },
    ready: function ready(e) {
      window.YPTabsView.__super__.ready.call(this, e);
    },
    createAddTabButton: function createAddTabButton() {
      var new_tab_button_id = +new Date() + '-' + Math.floor(Math.random() * 11);
      this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
      this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.i18nLocale.add_tab + '">+</a></li>').appendTo(this.$tabs.find(".tabs_controls"));
    },
    addTab: function addTab(e) {
      e.preventDefault(); // check user role to add controls

      if (!this.hasUserAccess()) {
        return false;
      }

      this.new_tab_adding = true;
      var tab_title = window.i18nLocale.tab,
          tabs_count = this.$tabs.find('[data-element_type=yp_tab]').length,
          tab_id = +new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11);
      vc.shortcodes.create({
        shortcode: 'yp_tab',
        params: {
          title: tab_title,
          tab_id: tab_id
        },
        parent_id: this.model.id
      });
      return false;
    },
    stopSorting: function stopSorting(event, ui) {
      var shortcode;
      this.$tabs.find('ul.tabs_controls li:not(.add_tab_block)').each(function (index) {
        var href = $(this).find('a').attr('href').replace("#", "");
        shortcode = vc.shortcodes.get($('[id=' + $(this).attr('aria-controls') + ']').data('model-id'));
        vc.storage.lock();
        shortcode.save({
          'order': $(this).index()
        }); // Optimize
      });
      shortcode && shortcode.save();
    },
    changedContent: function changedContent(view) {
      var params = view.model.get('params');

      if (!this.$tabs.hasClass('ui-tabs')) {
        this.$tabs.tabs({
          select: function select(event, ui) {
            return !$(ui.tab).hasClass('add_tab');
          }
        });
        this.$tabs.find(".ui-tabs-nav").prependTo(this.$tabs); // check user role to add controls

        if (this.hasUserAccess()) {
          this.$tabs.find(".ui-tabs-nav").sortable({
            axis: this.$tabs.closest('[data-element_type]').data('element_type') == 'vc_tour' ? 'y' : 'x',
            update: this.stopSorting,
            items: "> li:not(.add_tab_block)"
          });
        }
      }

      if (view.model.get('cloned') === true) {
        var cloned_from = view.model.get('cloned_from'),
            $tab_controls = $('.tabs_controls > .add_tab_block', this.$content),
            $new_tab = $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore($tab_controls);
        this.$tabs.tabs('refresh');
        this.$tabs.tabs("option", 'active', $new_tab.index());
      } else {
        $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore(this.$add_button);
        this.$tabs.tabs('refresh');
        this.$tabs.tabs("option", "active", this.new_tab_adding ? $('.ui-tabs-nav li', this.$content).length - 2 : 0);
      }

      this.new_tab_adding = false;
    },
    cloneModel: function cloneModel(model, parent_id, save_order) {
      var new_order, model_clone, params, tag;
      new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index;
      params = _.extend({}, model.get('params'));
      tag = model.get('shortcode');

      if (tag === 'yp_tab') {
        _.extend(params, {
          tab_id: +new Date() + '-' + this.$tabs.find('[data-element-type=yp_tab]').length + '-' + Math.floor(Math.random() * 11)
        });
      }

      model_clone = vc.shortcodes.create({
        shortcode: tag,
        id: vc_guid(),
        parent_id: parent_id,
        order: new_order,
        cloned: tag !== 'yp_tab',
        // todo review this by @say2me
        cloned_from: model.toJSON(),
        params: params
      });

      _.each(vc.shortcodes.where({
        parent_id: model.id
      }), function (shortcode) {
        this.cloneModel(shortcode, model_clone.get('id'), true);
      }, this);

      return model_clone;
    }
  });
  window.YPTabView = window.VcColumnView.extend({
    events: {
      'click > .vc_controls .vc_control-btn-delete': 'deleteShortcode',
      'click > .vc_controls .vc_control-btn-prepend': 'addElement',
      'click > .vc_controls .vc_control-btn-edit': 'editElement',
      'click > .vc_controls .vc_control-btn-clone': 'clone',
      'click > .wpb_element_wrapper > .vc_empty-container': 'addToEmpty'
    },
    render: function render() {
      var params = this.model.get('params');

      window.YPTabView.__super__.render.call(this);
      /**
       * @deprecated 4.4.3
       * @see composer-atts.js vc.atts.tab_id.addShortcode
       */


      if (!params.tab_id) {
        params.tab_id = +new Date() + '-' + Math.floor(Math.random() * 11);
        this.model.save('params', params);
      }

      this.id = 'tab-' + params.tab_id;
      this.$el.attr('id', this.id);
      return this;
    },
    ready: function ready(e) {
      window.YPTabView.__super__.ready.call(this, e);

      this.$tabs = this.$el.closest('.wpb_tabs_holder');
      var params = this.model.get('params');
      return this;
    },
    changeShortcodeParams: function changeShortcodeParams(model) {
      var params;

      window.YPTabView.__super__.changeShortcodeParams.call(this, model);

      params = model.get('params');

      if (_.isObject(params) && _.isString(params.title) && _.isString(params.tab_id)) {
        $('.ui-tabs-nav [href="#tab-' + params.tab_id + '"]').text(params.title);
      }
    },
    deleteShortcode: function deleteShortcode(e) {
      _.isObject(e) && e.preventDefault();
      var answer = confirm(window.i18nLocale.press_ok_to_delete_section),
          parent_id = this.model.get('parent_id');

      if (answer !== true) {
        return false;
      }

      this.model.destroy();

      if (!vc.shortcodes.where({
        parent_id: parent_id
      }).length) {
        var parent = vc.shortcodes.get(parent_id);
        parent.destroy();
        return false;
      }

      var params = this.model.get('params'),
          current_tab_index = $('[href="#tab-' + params.tab_id + '"]', this.$tabs).parent().index();
      $('[href="#tab-' + params.tab_id + '"]').parent().remove();
      var tab_length = this.$tabs.find('.ui-tabs-nav li:not(.add_tab_block)').length;

      if (tab_length > 0) {
        this.$tabs.tabs('refresh');
      }

      if (current_tab_index < tab_length) {
        this.$tabs.tabs("option", "active", current_tab_index);
      } else if (tab_length > 0) {
        this.$tabs.tabs("option", "active", tab_length - 1);
      }
    },
    cloneModel: function cloneModel(model, parent_id, save_order) {
      var new_order, model_clone, params, tag;
      new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index;
      params = _.extend({}, model.get('params'));
      tag = model.get('shortcode');

      if (tag === 'yp_tab') {
        _.extend(params, {
          tab_id: +new Date() + '-' + this.$tabs.find('[data-element_type=yp_tab]').length + '-' + Math.floor(Math.random() * 11)
        });
      }

      model_clone = vc.shortcodes.create({
        shortcode: tag,
        parent_id: parent_id,
        order: new_order,
        cloned: true,
        cloned_from: model.toJSON(),
        params: params
      });

      _.each(vc.shortcodes.where({
        parent_id: model.id
      }), function (shortcode) {
        this.cloneModel(shortcode, model_clone.get('id'), true);
      }, this);

      return model_clone;
    }
  });
}(jQuery);

/***/ })

/******/ });