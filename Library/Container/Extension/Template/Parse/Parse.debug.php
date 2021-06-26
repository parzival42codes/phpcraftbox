<?php

class ContainerExtensionTemplateParse_debug extends CoreDebug_abstract
{

    public function getHtml():string
    {        $tableTcs = [];

        foreach ($this->data as $elem) {

            $tableTcs[] = [
                'parseClass'          => $elem['data']['parseClass'],
                'parseString'         => htmlspecialchars($elem['data']['parseString']),
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
        $template->assign('DebugTableTemplateParse_DebugTableTemplateParse',
                          $tableTcs);

        $template->parseQuote();
        $template->parse();
        $template->catchDataClear();
        return $template->get();
    }

    public function getTitle(): string
    {

        return Container::get('ContainerExtensionTemplate')
                        ->set(ContainerFactoryLanguage::get('/ContainerExtensionTemplateParse/debug/header'))
                        ->assign('count',
                                 count($this->getData()))
                        ->parseString()
                        ->get();

    }

}
