$(function () {

    _globalFunctions['ajaxPageCookieBanner'] = $.CMSAjax({
        'path': "/ajax/page/cookie/banner",
        'dataType': 'json',
        'onSuccess': function (elementSettings, resultData) {
            $('#cookieBanner').remove();
        }
    });

    $('.cookieBannerButton').on("click", function () {
        let $this = $(this);
        _globalFunctions['ajaxPageCookieBanner'].trigger(
            {
                "value": $this.data('value')
            }
        );
    });
});
