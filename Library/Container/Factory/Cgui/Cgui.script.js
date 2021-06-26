_globalFunctions['ContainerFactoryCguiExecute'] = function (consoleID, secure, cguiBox, collectCguiText) {
    setTimeout(function () {
        $.ajax({
            method: "POST",
            url: _CMSUrl + "/cgui.php?execute=" + consoleID,
            dataType: "json",
            data: {
                "secure": secure
            }
        })
            .done(function (data) {
                    if (data.status === 'error') {
                        $(cguiBox).text(data.message);
                    } else {
                        collectCguiText = data.messages + collectCguiText;

                        $(cguiBox).html('<div>' + data.cguiCount + ' / ' + data.cguiMax + '</div>' +
                            '<progress style="display: inline-block;width: 100%;" max="' + data.cguiMax + '" value="' + data.cguiCount + '"></progress>' +
                            '<div>' + data.partCount + ' / ' + data.partMax + '</div>' +
                            '<progress style="display: inline-block;width: 100%;" max="' + data.partMax + '" value="' + data.partCount + '"></progress>' +
                            '<div>' + collectCguiText + '</div>');

                        if (data.status === 'step') {
                            _globalFunctions['ContainerFactoryCguiExecute'].call(this, consoleID, secure, cguiBox, collectCguiText);
                        } else {

                        }
                    }
                }
            ).fail(function (jqXHR, textStatus, errorThrown) {
            let responseText = jqXHR.responseText;
            $(cguiBox).prepend($(responseText).html());
        });
    }, 250);

};

_globalFunctions['ContainerFactoryCgui'] = function (modul, command, secure, parameter, cguiBox) {
    $.ajax({
        method: "POST",
        url: _CMSUrl + "/cgui.php?prepare=1",
        dataType: "json",
        data: {
            "modul": modul,
            "command": command,
            "secure": secure,
            "parameter": parameter

        }
    })
        .done(function (data) {
            console.log(data);

            $(cguiBox).html('' +
                '<div>0 / ' + data.cguiMax + '</div>' +
                '<progress style="display: inline-block;width: 100%;" max="' + data.cguiMax + '"></progress>');

            _globalFunctions['ContainerFactoryCguiExecute'].call(this, data.consoleID, secure, cguiBox, '');
        }).fail(function (jqXHR, textStatus, errorThrown) {
        let responseText = jqXHR.responseText;
        $(cguiBox).prepend($(responseText).html());
    });

}

