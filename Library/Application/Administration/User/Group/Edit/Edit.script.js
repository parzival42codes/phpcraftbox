/*<meta> {
    "prio" : 3
} </meta>*/

$(function () {
    $(".ApplicationAdministrationUserGroupEditAskDeleteDialog").on("click", function (event) {
        if ($(this).prop('checked') === true) {

            event.preventDefault();

            let dialogID = $(this).data('dialogid');
            let dialogBox = $('#ContainerExtensionTemplateParseCreateDialog_container_item_' + dialogID);
            dialogBox.find('.dialogDeleteBtn').attr('data-dialogsource', $(this).attr('id'));

            _globalFunctions['ContainerExtensionTemplateParseCreateDialog_view'].call(this, dialogID);
        }
    });

    $(".dialogDeleteBtn").on("click", function (event) {
        let inputItem = $('#' + $(this).attr('data-dialogsource'));
        _globalFunctions['ContainerExtensionTemplateParseCreateDialog_view_close'].call(this, $(this));
        inputItem.prop('checked', true);
    });

});
