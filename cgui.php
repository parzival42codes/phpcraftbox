<?php

ini_set('memory_limit',
        '512M');
ini_set("max_execution_time",
        720);;

try {

    require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Library/config.inc.php');

    if (empty($_POST)) {

        $javascript = file_get_contents(CMS_ROOT . '/Cgui/Jquery.3.5.1.min.js');
        $javascript .= '
    _CMSUrl = "' . \Config::get('/server/http/base/url') . '";
    _globalFunctions = [];
     ';

        $javascript .= ContainerFactoryCgui::getJavaScript();
        $javascript .= '
    $(function () {
        $("#cguiButtonsGo").on("click", function () {
            let $this = $(this);
            _globalFunctions["ContainerFactoryCgui"].call(this,$("#modul").val(),$("#command").val(),$("#securekey").val(),$("#parameter").val(),"#cguiMessages");
         });
    });
    ';

        $body = file_get_contents(CMS_ROOT . '/Cgui/Body.tpl');
        $bodyCss = file_get_contents(CMS_ROOT . '/Cgui/Style.css');

        /** @var ContainerExtensionTemplate $bodyTemplate */
        $bodyTemplate = Container::get('ContainerExtensionTemplate');
        $bodyTemplate->set($body);
        $bodyTemplate->assign('javascriptHeader',
                              $javascript);
        $bodyTemplate->assign('headerCss',
                              $bodyCss);

        $bodyTemplate->parse();
        echo $bodyTemplate->get();

    }
    else {
        ContainerFactoryCgui::cgui();
    }

} catch (Throwable $exception) {
    echo '<pre>'.var_export($exception, true).'</pre>';
    die();
}
