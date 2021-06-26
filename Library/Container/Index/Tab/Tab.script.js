/*<meta> {
    "prio" : 2
} </meta>*/

// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;

(function ($, window, document, undefined) {

    "use strict";

    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = "CMSTab",
        defaults = {
            tabHeightMax: 0,
            titleWithtMax: true,
            triggerFirst: true,
            classActive: 'tabActive',
            classInactive: 'tabInactive'
        };

    var pluginMethods = {
        'closeContent': function () {
            CloseContent(this.element, this.settings);
        },
        'contentHeight': function (elementHeight) {
            CalculateContentHeight(this.element, this.settings, elementHeight);
        }
    };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this._methods = pluginMethods;
        this.init();
    }

    function CloseContent(element, elementSettings) {
        $(element).children('div').hide();
        $(element).children('ul').children('.tabActive').removeClass('tabActive').addClass('tabInactive');
    }

    function CalculateContentHeight(element, elementSettings, setContentHeight) {

        var elementTab = $(element);
        var tabStatus = elementTab.children('ul').first().css('display');
        elementTab.children('ul').first().show();

        var tabBarHeight = (elementTab.children('ul').height() * 2);
        elementTab.children('ul').first().css('display', tabStatus);

        var windowHeight = $(window).height();

//            var windowHeight = jQuery(window).height();
//            var contentheight = windowHeight - tabBarHeight - 100 - elementTab.position().top;

        if (setContentHeight === false) {

        } else {
            if (setContentHeight === 'window') {
                var contentheight = windowHeight - tabBarHeight;
            } else if (setContentHeight === 0) {
                var contentheight = jQuery(elementTab).parent().height() - tabBarHeight;
            } else if (setContentHeight !== 0) {
                var contentheight = parseInt(setContentHeight) - tabBarHeight;
            }

            $(element).children('div').children('.tabContent').height(contentheight);
        }

//        console.log(setContentHeight, windowHeight, tabBarHeight, contentheight);

    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        init: function () {
            // Place initialization logic here
            // You already have access to the DOM element and
            // the options via the instance, e.g. this.element
            // and this.settings
            // you can add more functions like the one below and
            // call them like so: this.yourOtherFunction(this.element, this.settings).

            var elementTab = this.element;
            var elementSettings = this.settings;
            var $this = this;
            this.Tab(this.element, this.settings);

//            jQuery(elementTab).children().each(function (index) {
//
//                var data_mode = jQuery(this).attr('data-mode');
//                data_mode = 'tab';
//                if (typeof data_mode === 'undefined') {
//                    data_mode = 'tab';
//                } else {
//                    if (data_mode === 'tab') {
//
//                    } else if (data_mode === 'linebreak') {
//                        jQuery(this).before('<br />');
//                        jQuery(this).remove();
//                    } else if (data_mode === 'line') {
//                        jQuery(this).before('<hr />');
//                        jQuery(this).remove();
//                    } else {
//
//                    }
//                }
//
//            });


//
//            jQuery(window).resize(function () {
//                jQuery(elementTab).children().children('.tabTitle').css('width', '');
//                $this.Tab(elementTab, elementSettings);
//                jQuery(elementTab).children().children('.' + elementSettings.classActive).trigger('click');
//            });

        },
        Tab: function () {
//            jQuery('.tabContent').css({
//                'display': 'none',
//                'position': 'absolute'
//            });
            var elementTab = jQuery(this.element);
            var elementSettings = this.settings;

            var elementListing = '';

            var setTitleHeight = 0;
            var setTitleWidth = 0;

            elementTab.children('ul').remove();

            elementTab.children('div.tabItem').each(function (index) {

                if (jQuery(this).children('.tabContent').text() !== '') {
                    elementListing = elementListing + '<li class="tabButton" id="tabButton' + jQuery(this).attr('id') + '" data-id="' + jQuery(this).attr('id') + '"><span>' + jQuery(this).children('.tabTitle').html() + '</span></li>';
                } else {
                    elementListing = elementListing + '<li><span class="tabButtonNone" id="tabButton' + jQuery(this).attr('id') + '">' + jQuery(this).children('.tabTitle').html() + '</span></li>';
                }

            });

//            console.log(elementTab,elementListing,elementSettings);

            elementTab.prepend('<ul>' + elementListing + '</ul>');

            jQuery('.tab > ul > li', this.element).css({
                'display': 'inline-block'
            });

            var elementTabLi = elementTab.children('ul').children('li');

            var clickFunction = function () {
                CalculateContentHeight(elementTab, elementSettings, elementSettings.tabHeightMax);
                elementTabLi.removeClass(elementSettings.classActive).addClass(elementSettings.classInactive);
                var thisId = jQuery(this).data('id');
                elementTab.children('div').hide();
                jQuery(this).removeClass(elementSettings.classInactive).addClass(elementSettings.classActive);
                elementTab.children('#' + thisId).show();
                elementTab.children('#' + thisId).children('.tabContent').show();
            };

            elementTabLi.each(function (index) {

                var element = jQuery(this);
                var elementwidth = element.width();

//                console.log('elementwidth', elementwidth, element, elementSettings);

                if (elementwidth > setTitleWidth) {
                    setTitleWidth = elementwidth;
                }
                if (element.height() > setTitleHeight) {
                    setTitleHeight = element.height();
                }

                if (element.hasClass('tabButton')) {
                    element.bind("click", clickFunction);
                } else if (element.hasClass('tabButtonNone')) {
                    element.unbind("click", clickFunction);
                }
            });

            if (this.settings.titleWithtMax === true) {
                //    elementTabLi.width(setTitleWidth);
            }

            if (setTitleHeight > 0) {
                elementTabLi.height(setTitleHeight);
            }

            jQuery(this.element).find('.tabTitle').css({
                'display': 'none'
            });
            // jQuery(this.element).find('.tabContent').css({
            //     'display': 'none'
            // });

            if (this.settings.triggerFirst === true) {
                elementTabLi.first().trigger('click');
            } else {
                jQuery(elementTab).children('div').children('.tabContent').hide();
                elementTabLi.each(function (index) {
                    if (jQuery(this).hasClass('tabButton')) {
                        jQuery(this).addClass(elementSettings.classInactive);
                    }
                });
            }

        }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        var args = arguments;

        if (options === undefined || typeof options === 'object') {
            return this.each(function () {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName, new Plugin(this, options));
                }
            });

        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {

            this.each(function () {

                var instance = $.data(this, 'plugin_' + pluginName);

                if (instance instanceof Plugin && typeof instance._methods[options] === 'function') {
                    return instance._methods[options].apply(instance, Array.prototype.slice.call(args, 1));
                }

                if (options === 'destroy') {
                    $.data(this, 'plugin_' + pluginName, null);
                } else if (options === 'refresh') {
                    instance.Tab(this.element, this.settings);
                }
            });

        }
    };

})(jQuery, window, document);

//Real Width of Element even if element/parents are hidden
$.fn.elementRealWidth = function () {
    $clone = this.clone()
        .css("visibility", "hidden")
        .appendTo($('body'));
    var $width = $clone.outerWidth();
    $clone.remove();
    return $width;
};