$(document).ready(function () {
    $(".ApplicationAdministrationModul_button_un_install").on("change", function () {
        let $this = $(this);

        console.log('ApplicationAdministrationModul_button_un_install');

        let isChecked = $this.children('label').children('input').is(':checked');
        console.log(isChecked);

        if (isChecked === true) {
            $this.attr('data-action', 'install');
        } else if (isChecked === false) {
            $this.attr('data-action', 'uninstall');
        }

        let $messageTarget = '#cGuiDialog_cguiMessages';
        _globalFunctions['ContainerExtensionTemplateParseCreateDialog_view'].call(this, 'ApplicationAdministrationModul_dialog_cgui_messages');
        _globalFunctions["ContainerFactoryCgui"].call(this, 'Custom', $this.attr('data-action'), $('#ApplicationAdministrationLContent').data('securekey'), $this.data('modul'), $messageTarget);

    });

    $(".ApplicationAdministrationModul_button_in_active").on("change", function () {
        let $this = $(this);

        console.log('ApplicationAdministrationModul_button_in_active');

        let isChecked = $this.children('label').children('input').is(':checked');
        if (isChecked === true) {
            $this.attr('data-action', 'activate');
        } else if (isChecked === false) {
            $this.attr('data-action', 'deactivate');
        }

        let $messageTarget = '#cGuiDialog_cguiMessages';
        _globalFunctions['ContainerExtensionTemplateParseCreateDialog_view'].call(this, 'ApplicationAdministrationModul_dialog_cgui_messages');
        _globalFunctions["ContainerFactoryCgui"].call(this, 'Custom', $this.attr('data-action'), $('#ApplicationAdministrationLContent').data('securekey'), $this.data('modul'), $messageTarget);

    });
});
