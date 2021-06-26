<?php

class ContainerExtensionTemplate_debug extends CoreDebug_abstract
{

    public function getHtml(): string
    {
        $tableTcs = [];

//        d($this->data);
//        eol();

        foreach ($this->data as $elem) {

            $tableTcs[] = [
                'differenceMicrotime' => ContainerHelperCalculate::calculateMicroTimeDisplay($elem['microtimeDiff']),
                'differenceMemory'    => ContainerHelperCalculate::calculateMemoryBytes($elem['memoryDiff']),
                'file'                => ContainerFactoryFile::getFilenameWrap($elem['backtrace'][1]['file']),
                'line'                => $elem['backtrace'][1]['line'],
            ];
        }

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'debug');
        $templateCacheContent = $templateCache->getCacheContent();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['debug']);
        $template->assign('DebugTableTemplate_DebugTableTemplate',
                          $tableTcs);

        $template->parseQuote();
        $template->parse();
        $template->catchDataClear();
        return $template->get();
    }

    public function getTitle(): string
    {

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set(ContainerFactoryLanguage::get('/ContainerExtensionTemplate/debug/header'));
        $template->assign('count',
                          count($this->getData()));
        $template->parseString();
        return $template->get();

    }

}
