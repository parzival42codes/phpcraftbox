/*<meta> {
    "prio" : 2
} </meta>*/

_globalFunctions['menuOpenCloseVertical'] = function (itemobject) {

    itemobject.click(function () {
        let me = jQuery(this).parent();

        let subdirFolderIcon = me.children('.subdir--folder-icon-text').children('.subdir--folder-icon');

        if (me.hasClass('subdir--close') === true) {
            subdirFolderIcon.children('.subdir--folder-icon-close').hide();
            subdirFolderIcon.children('.subdir--folder-icon-open').show();

            me.addClass('subdir--open');
            me.removeClass('subdir--close');

            me.children('ul').show();

        } else if (me.hasClass('subdir--open') === true) {
            subdirFolderIcon.children('.subdir--folder-icon-close').show();
            subdirFolderIcon.children('.subdir--folder-icon-open').hide();

            me.addClass('subdir--close');
            me.removeClass('subdir--open');

            me.children('ul').hide();
        }
    });
};

$(function () {
    _globalFunctions['menuOpenCloseVertical'].call(this, jQuery('.ContainerFactoryMenu.menu-vertical .subdir--folder-icon-text'));
});

_globalFunctions['menuOpenCloseHorizontal'] = function (itemobject) {

    itemobject.click(function () {
        let me = jQuery(this).parent();

        let subdirFolderIcon = me.children('.subdir--folder-icon-text').children('.subdir--folder-icon');

        if (me.hasClass('subdir--close') === true) {
            subdirFolderIcon.children('.subdir--folder-icon-close').hide();
            subdirFolderIcon.children('.subdir--folder-icon-open').show();

            me.addClass('subdir--open');
            me.removeClass('subdir--close');

            me.children('ul').show();

        } else if (me.hasClass('subdir--open') === true) {
            subdirFolderIcon.children('.subdir--folder-icon-close').show();
            subdirFolderIcon.children('.subdir--folder-icon-open').hide();

            me.addClass('subdir--close');
            me.removeClass('subdir--open');

            me.children('ul').hide();
        }
    });
};

$(function () {
    _globalFunctions['menuOpenCloseHorizontal'].call(this, jQuery('.ContainerFactoryMenu.menu-horizontal .subdir--folder-icon-text'));
});
