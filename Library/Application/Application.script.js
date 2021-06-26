/*<meta> {
    "prio" : 5
} </meta>*/
;
(function ($, window, document, undefined) {

    "use strict";

    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially dwhen both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = "CMSApplication",
        defaults = {};

    var pluginApplicationAjax = $.CMSAjax({
        'loadingElement': $('#CMSMainContent').children(),
        'loadingElementTitle': $('#Application-ajaxLoadMessageTitle').html(),
        'loadingElementPre': $('#Application-ajaxLoadMessagePre').html(),
        'loadingElementAfter': $('#Application-ajaxLoadMessageAfter').html(),
        'path': "Application/App",
        'dataType': 'json',
        'onSuccess': function (elementSettings, resultData) {
            $('#CMSMainContent').children().html(resultData.content.content);

            var debugData = $(resultData.content.debugbar);

            var debugDataHtml = debugData.html();

            if (typeof debugDataHtml !== 'undefined' && debugDataHtml.length > 0) {
                $('#debugBarBlock').children().children('#tabDebugBar').replaceWith(debugData.children().children('#tabDebugBar'));
                _globalFunctions['tabCollectedJs'].call(this);

            }

            var leftContent = $(resultData.content.leftContent);
            var leftContentHtml = leftContent.html();


            if (typeof leftContentHtml !== 'undefined' && leftContentHtml.length > 0) {

                var appmenu = $('#Application_app_menu');
                appmenu.html(leftContent.children());

                _globalFunctions['menuOpenCloseVertical'].call(this, appmenu.find('.ContainerFactoryMenuMenu .subdir'));

                // _globalFunctions['menuSelectItem'].call(this, appmenu.find('.menu-sub'));
                _globalFunctions['applicationHrefAjax'].call(this, appmenu);
                _globalFunctions['applicationHrefAjax'].call(this, $('#CMSMainContent'));
                jQuery('#pageCanonical').attr('href', resultData.trigger.POST.href);
                //    _globalFunctions['menuSelectSearch'].call(this, jQuery('#Application_app_menu'), 'a[href="' + jQuery('#pageCanonical').attr('href') + '"]');

                _globalFunctions['ContainerFactoryRulesCheck'].call(this, jQuery('#CMSMainContent').find('.Formular'));

            }
        }
    });

    var pluginMethods = {
        'trigger': function (options, args) {
            console.log(options, args);
            this.pluginApplicationAjax.trigger(options)
        }
    };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.pluginApplicationAjax = pluginApplicationAjax;
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._methods = pluginMethods;
        this._defaults = defaults;
        this._name = 'CMSApplication';
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        init: function () {
            var $this = this;

        },
        trigger: function (options) {
            var args = arguments;
            this._methods['trigger'].call(this, options, args);
        }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.CMSApplication = function (options) {
        var args = arguments;
        return $.data(this, 'plugin_CMSApplication', new Plugin(this, options));
    };


})(jQuery, window, document);

_globalFunctions['applicationHrefAjax'] = function (element) {
    var appAjax = jQuery.CMSApplication();

    element.find('a').bind("click", function (event) {
        event.preventDefault();
        var href = jQuery(this).attr('href');
        if (href !== '#') {
            appAjax.trigger({
                'href': jQuery(this).attr('href'),
                "_elementSettings": {
                    'loadingElementTitle': jQuery(this).parent().attr('data-ajaxload')
                }
            });
        }
    });

    // _globalFunctions['menuSelectSearch'].call(this, jQuery('#Application_app_menu'), 'a[href="' + jQuery('#pageCanonical').attr('href') + '"]');

};

