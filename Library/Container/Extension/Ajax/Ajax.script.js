/*<meta> {
    "prio" : 2
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
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = "CMSAjax",
        defaults = {
            'path': '',
            'data': '',
            'always': null,
            'onSend': null,
            'onSuccess': null,
            'onError': null,
            'loading': null,
            'loadingElement': null,
            'loadingElementTitle': null,
            'loadingElementPre': null,
            'loadingElementAfter': null,
        };

    var pluginMethods = {
        'trigger': function () {
            var elementSettings = this.settings;
            var args = arguments;

            var dataTrigger = [];
            var dataFormular = [];
            if (args[0] !== undefined) {
                dataTrigger = args[0];
            }

            if (dataTrigger._elementSettings !== undefined) {
                elementSettings = $.extend({}, elementSettings, dataTrigger._elementSettings);
            }

            if (elementSettings.loadingElement !== null) {

                elementSettings.loadingElement.addClass('ContainerExtensionAjax-container-modify-element');
                var ajaxTemplateCloned = $('#ContainerExtensionAjax-template').clone();

                ajaxTemplateCloned.addClass('ContainerExtensionAjax-container-element-self').show();

                if (elementSettings.loadingElementTitle !== null && elementSettings.loadingElementTitle.length > 0) {
                    ajaxTemplateCloned.children('.ContainerExtensionAjax-container').children('.ContainerExtensionAjax-container-title').html(elementSettings.loadingElementTitle);
                } else {
                    ajaxTemplateCloned.children('.ContainerExtensionAjax-container').children('.ContainerExtensionAjax-container').children('.ContainerExtensionAjax-container-title').remove();
                }

                if (elementSettings.loadingElementPre !== null && elementSettings.loadingElementPre.length > 0) {
                    ajaxTemplateCloned.children('.ContainerExtensionAjax-container').children('.ContainerExtensionAjax-container-content-pre').html(elementSettings.loadingElementPre);
                } else {
                    ajaxTemplateCloned.children('.ContainerExtensionAjax-container').children('.ContainerExtensionAjax-container-content-pre').remove();
                }

                if (elementSettings.loadingElementAfter !== null && elementSettings.loadingElementAfter.length > 0) {
                    ajaxTemplateCloned.children('.ContainerExtensionAjax-container').children('.ContainerExtensionAjax-container-content-after').html(elementSettings.loadingElementAfter);
                } else {
                    ajaxTemplateCloned.children('.ContainerExtensionAjax-container').children('.ContainerExtensionAjax-container-content-after').remove();
                }

                elementSettings.loadingElement.before(ajaxTemplateCloned);

            }


            if (elementSettings.onSend != null) {
                elementSettings.onSend.apply(elementSettings);
            }

            var jqxhr = jQuery.ajax({
                'url': _CMSUrl + elementSettings.path,
                'method': "POST",
                'cache': false,
                'data': dataTrigger,
                'dataType': 'json',
                xhr: function () {
                    var xhr = $.ajaxSettings.xhr();

                    xhr.addEventListener("progress", function (evt) {
                        if (elementSettings.loadingElement !== null) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                ajaxTemplateCloned.children('.ContainerExtensionAjax-container-content-progressbar').children('.ContainerExtensionAjax-container-content-progressbar-item').children('progress').attr('value', percentComplete);
                            }
                        }
                    }, false);

                    return xhr;
                },
            })
                .done(function (data, textStatus, jqXHR) {
                    if (elementSettings.loadingElement !== null) {
                        elementSettings.loadingElement.removeClass('ContainerExtensionAjax-container-modify-element');
                        ajaxTemplateCloned.remove();
                    }

                    if (elementSettings.onSuccess != null) {
                        return elementSettings.onSuccess.call(this, elementSettings, data, textStatus, jqXHR);

                    }
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    var templateAjaxErrorModify = [];

                    if (elementSettings.onError != null) {
                        return elementSettings.onError.call(this, elementSettings, jqXHR, textStatus, errorThrown);
                    }

                })
                .always(function (data, textStatus, jqXHR) {
                    if (elementSettings.loadingElement !== null) {
                        elementSettings.loadingElement.removeClass('ContainerExtensionAjax-container-modify-element');
                        ajaxTemplateCloned.remove();
                    }
                    if (elementSettings.onComplete != null) {
                        return elementSettings.onComplete.call(this, elementSettings, data, textStatus, jqXHR);
                    }
                });
        }
    };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._mainElement = $('#ContainerExtensionAjax-container');
        this._methods = pluginMethods;
        this._defaults = defaults;
        this._name = 'CMSAjax';
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        init: function () {
            var elementTab = this.element;
            var elementSettings = this.settings;
            var $this = this;
        },
        trigger: function (options) {
            var args = arguments;
            this._mainElement.show();
            this._methods['trigger'].call(this, options);
        }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.CMSAjax = function (options) {
        var args = arguments;
        return $.data(this, 'plugin_CMSAjax', new Plugin(this, options));
    };


})(jQuery, window, document);

