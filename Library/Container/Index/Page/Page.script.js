$(function () {

    _globalFunctions['menuOpenCloseVertical'] = $.CMSAjax({
        'path': "/ajax/page/cookie/banner",
        'dataType': 'json',
        'onSuccess': function (elementSettings, resultData) {
            console.log(elementSettings);
            console.log(resultData);
        }
    });

    $('.cookieBannerButton').on("click", function () {
        // $('#cookieBanner').remove();
        _globalFunctions['menuOpenCloseVertical'].trigger({
            "foo": "bar"
        });
    });
});
