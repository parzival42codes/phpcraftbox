// _globalFunctions['getIndexCardDialogTemplate'] = function (cardClick, modify = null) {
//     return jQuery('#IndexCardDialog_item_container').clone().children();
// };

_globalFunctions['ContainerExtensionTemplateParseCreateDialog_page_container_center'] = function (cardClick, modify = null) {
    let container = $('#ContainerExtensionTemplateParseCreateDialog_page_container_center');

    let lengthContainer = container.children('.ContainerExtensionTemplateParseCreateDialog_container_item_view').length;

    if (lengthContainer > 0) {
        container.css('display', 'block');
    } else {
        container.css('display', 'none');
    }

};

_globalFunctions['ContainerExtensionTemplateParseCreateDialog_view'] = function (dialogID) {
    let dialogBox = $('#ContainerExtensionTemplateParseCreateDialog_container_item_' + dialogID);
    dialogBox.addClass('ContainerExtensionTemplateParseCreateDialog_container_item_view');
    _globalFunctions['ContainerExtensionTemplateParseCreateDialog_page_container_center'].call(this);
};

_globalFunctions['ContainerExtensionTemplateParseCreateDialog_view_close'] = function (dialogItem) {
    dialogItem.closest('.ContainerExtensionTemplateParseCreateDialog_container_item').removeClass('ContainerExtensionTemplateParseCreateDialog_container_item_view');
    _globalFunctions['ContainerExtensionTemplateParseCreateDialog_page_container_center'].call(this);
};

$(document).ready(function () {

    $('.ContainerExtensionTemplateParseCreateDialog_container_item').find('.dialog_close').on("click", function () {
        _globalFunctions['ContainerExtensionTemplateParseCreateDialog_view_close'].call(this, $(this));
    });

    $('.ContainerExtensionTemplateParseCreateDialog_button').on("click", function () {
        let dialogID = $(this).data('dialog');
        _globalFunctions['ContainerExtensionTemplateParseCreateDialog_view'].call(this, dialogID);
    });

});
