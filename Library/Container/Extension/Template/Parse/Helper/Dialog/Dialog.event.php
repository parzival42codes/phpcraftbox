<?php

class ContainerExtensionTemplateParseHelperDialog_event extends Base
{
    public static function insertTemplateDialog(): void
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'page,page.item');


        $dialogContent = [
            ContainerExtensionTemplateParseHelperDialog::BOX_POSITION_HEADER => '',
            ContainerExtensionTemplateParseHelperDialog::BOX_POSITION_CENTER => '',
            ContainerExtensionTemplateParseHelperDialog::BOX_POSITION_FOOTER => '',
        ];

        $dialogs = ContainerExtensionTemplateParseHelperDialog::getDialogContent();

        foreach ($dialogContent as $dialogContentKey => $dialogContentValue) {

            foreach ($dialogs[$dialogContentKey] as $dialogId => $dialog) {
                /** @var ContainerExtensionTemplate $template */
                $template = Container::get('ContainerExtensionTemplate');
                $template->set($templateCache->get()['page.item']);

                $template->assign('content',
                                  $dialog);
                $template->assign('id',
                                  $dialogId);

                $template->parseString();

                $dialogContent[$dialogContentKey] .= $template->get();
            }

        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['page']);

        $template->assign('header',
                          $dialogContent[ContainerExtensionTemplateParseHelperDialog::BOX_POSITION_HEADER]);
        $template->assign('center',
                          $dialogContent[ContainerExtensionTemplateParseHelperDialog::BOX_POSITION_CENTER]);
        $template->assign('footer',
                          $dialogContent[ContainerExtensionTemplateParseHelperDialog::BOX_POSITION_FOOTER]);

        $template->parse();

//        d($template);
//        eol(true);

        ContainerExtensionTemplateParseInsertPositions::insert('/ContainerIndexPage/Template/Positions/Footer/Include',
                                                               $template->get());

    }
}
