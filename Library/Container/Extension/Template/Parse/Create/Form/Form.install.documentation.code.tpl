<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ContainerExtensionTemplateParseCreateForm" path="/documentation/helper/descriptiion" import="documentation"
        language-de_DE="Formular Helfer Bausteine"
        language-en_US="Form Helper"}
    </div>
    <div class="card-container-content">
        <div class="btn copyToClipboard"
             data-id="{$copyToClipboard}">{insert/language class="ContainerFactoryLanguage" path="/standard/template/copyToClipboard" import="template"}</div>
        <div class="code"
             id="{$copyToClipboard}">
            <CMS function="_code">
                $formHelper->addFormElement('',
                'Text',
                [],
                [
                [
                'ContainerExtensionTemplateParseCreateFormModifyDefault',
                ''
                ],
                ]);

                $template->assign('',
                $formHelper->getElements());

                $template->assign('header',
                $formHelper->getHeader());

                $template->assign('footer',
                $formHelper->getFooter());


            </CMS>
        </div>
    </div>
</div>

