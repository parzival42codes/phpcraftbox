/*<meta> {
    "prio" : 5
} </meta>*/

$(document).ready(function () {

    jQuery('#debugBarButtonMin').click(function () {
        jQuery('#tabDebugBar').CMSTab('closeContent');
    });

    jQuery('#debugBarButtonMax').click(function () {
        jQuery('#debugBarBlock').css("max-height", jQuery(jQuery(window).height()));
        jQuery('#tabDebugBar').CMSTab('contentHeight', 'window');
    });

    jQuery('#debugBarButtonClose').click(function () {
        jQuery('#debugBarBlock').remove();
    });

});
