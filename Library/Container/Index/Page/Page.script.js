$(function () {

    _globalFunctions['menuOpenCloseVertical'] = $.CMSAjax({
        'path': "/ajax/page/cookie/banner",
        'dataType': 'json',
        'onSuccess': function (elementSettings, resultData) {
            $('#cookieBanner').remove();
        }
    });

    $('.cookieBannerButton').on("click", function () {
        _globalFunctions['menuOpenCloseVertical'].trigger();
    });
});
