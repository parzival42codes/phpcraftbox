$(function () {

    let localStorageCookieBannerName = 'cookieBanner';
    $('#cookieBannerButton').on("click", function () {
        $('#cookieBanner').remove();
        localStorage.setItem(localStorageCookieBannerName, '1');
    });

});
