<?php

class ContainerExtensionTemplateParseHelperTooltip_event extends Base
{
    public static function insertTemplateDialog(): void
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'page,page.item');

        $toolTipContent = '';
        $tooltips       = ContainerExtensionTemplateParseHelperTooltip::getTooltipContent();

//        d($tooltips);
//        eol(true);

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['page']);

        $template->assign('container',
                          implode('',
                                  $tooltips));

        $template->parse();

        ContainerExtensionTemplateParseInsertPositions::insert('/ContainerIndexPage/Template/Positions/Footer/Include',
                                                               $template->get());

    }
}
