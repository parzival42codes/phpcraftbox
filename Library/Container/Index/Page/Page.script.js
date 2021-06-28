$(function () {

    let localStorageCookieBannerName = 'cookieBanner';

    let localStorageCookieBanner = localStorage.getItem(localStorageCookieBannerName);

    if (localStorageCookieBanner === '1') {
        $('#cookieBanner').remove();
    }

    $('#cookieBannerButton').on("click", function () {
        $('#cookieBanner').remove();
        localStorage.setItem(localStorageCookieBannerName, '1');
    });

});
