$(document).ready(function () {
    $(".ApplicationAdministrationModul_button_un_install").on("change", function () {
        let $this = $(this);

        console.log('ApplicationAdministrationModul_button_un_install');

        let $inActive = $this.closest('.table-row-type-body').find('.ApplicationAdministrationModul_button_in_active');

        let isChecked = $this.children('label').children('input').is(':checked');

        if (isChecked === true) {
            isChecked.find('input').attr('disabled', 'disabled');
            isChecked.find('span').addClass('labelClosed');
            $this.attr('data-action', 'install');
        } else if (isChecked === false) {
            isChecked.find('input').removeAttr('disabled');
            isChecked.find('span').removeClass('labelClosed');
            $this.attr('data-action', 'uninstall');
        }

        let $messageTarget = '#cGuiDialog_cguiMessages';
        _globalFunctions['ContainerExtensionTemplateParseCreateDialog_view'].call(this, 'ApplicationAdministrationModul_dialog_cgui_messages');
        _globalFunctions["ContainerFactoryCgui"].call(this, 'Custom', $this.attr('data-action'), $('#ApplicationAdministrationModulCustom').data('securekey'), $this.data('modul'), $messageTarget);

    });

    $(".ApplicationAdministrationModul_button_in_active").on("change", function () {
        let $this = $(this);

        console.log('ApplicationAdministrationModul_button_in_active');

        let $unInstall = $this.closest('.table-row-type-body').find('.ApplicationAdministrationModul_button_un_install');
        let isChecked = $this.children('label').children('input').is(':checked');

        if (isChecked === true) {
            $unInstall.find('input').attr('disabled', 'disabled');
            $unInstall.find('span').addClass('labelClosed');
            $this.attr('data-action', 'activate');
        } else if (isChecked === false) {
            $unInstall.find('input').removeAttr('disabled');
            $unInstall.find('span').removeClass('labelClosed');
            $this.attr('data-action', 'deactivate');
        }

        let $messageTarget = '#cGuiDialog_cguiMessages';
        _globalFunctions['ContainerExtensionTemplateParseCreateDialog_view'].call(this, 'ApplicationAdministrationModul_dialog_cgui_messages');
        _globalFunctions["ContainerFactoryCgui"].call(this, 'Custom', $this.attr('data-action'), $('#ApplicationAdministrationModulCustom').data('securekey'), $this.data('modul'), $messageTarget);

    });
});
