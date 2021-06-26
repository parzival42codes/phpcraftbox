<?php declare(strict_types=1);

Class ContainerExtensionTemplateParseTemplateInclude extends ContainerExtensionTemplateParseTemplate_abstract
{
    function parse():string
    {
        return '';


//        $parameter = $this->getParameter();
//
//        /** @var ContainerExtensionTemplate $parentTemplateObject */
//        $parentTemplateObject = $this->getParentTemplateObject();
//
//        /** @var ContainerExtensionTemplateInternalAssign $templateAssign */
//        $templateAssign = $parentTemplateObject->getAssignObject();
//
//        /** @var ContainerExtensionTemplateLoad $templateCache */
//        $templateCache = Container::get('ContainerExtensionTemplateLoad',
//                                         $parameter['class'],
//                                         [
//                                             $parameter['include'],
//                                         ]);
//
//        $templates = $templateCache->get();
//
//        /** @var ContainerExtensionTemplate $template */
//        $template = Container::get('ContainerExtensionTemplate',
//                                    null,
//                                    null,
//                                    $templateAssign);
//
//        $template->set($templates[$parameter['include']]);
//        $template->parse();
//        return $template->get();
    }
}
