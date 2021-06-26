<?php

class ContainerExtensionAjax_event extends Event_abstract
{
    public static function insertTemplateDialog(string $class, string $method, object $object): void
    {

//        $templateFile = 'preparecontent';
//
//        /** @var ContainerExtensionTemplate $template */
//        $template = Container::get('ContainerExtensionTemplate');
//        $output   = $template->loadTemplate(Core::getRootClass(__CLASS__),
//                                            $templateFile)
//                             ->setMetaClass(Core::getRootClass(__CLASS__))
//                             ->assignArray([
//                                               'ajaxLoadMessageTitle' => \Language::get('/Application/ajaxLoadMessageTitle'),
//                                               'ajaxLoadMessagePre'   => \Language::get('/Application/ajaxLoadMessagePre'),
//                                               'ajaxLoadMessageAfter' => \Language::get('/Application/ajaxLoadMessageAfter'),
//                                           ])
//                             ->parse($templateFile)
//                             ->get($templateFile);
//
//        ContainerExtensionTemplate::registerInjection(,
//                               'ContainerIndexPage',
//                               'endOfBody',
//                               Core::getRootClass(__CLASS__),
//                               $output);
    }
}
