<?php

class ContainerExtensionCache_debug extends CoreDebug_abstract
{
    public function getHtml(): string
    {
        $tableTcs = [];
        foreach ($this->data as $elem) {

            $tableTcs[] = [
                'isCreated'          => ($elem['data']['isCreated'] ?? '?'),
                'cacheClassName'     => ($elem['data']['cacheClassName'] ?? '?'),
                'cacheName'          => ($elem['data']['cacheName'] ?? '?'),
                'microtimeDiff'      => ContainerHelperCalculate::calculateMicroTimeDisplay($elem['microtimeDiff']),
                'memoryDiff'         => ContainerHelperCalculate::calculateMemoryBytes($elem['memoryDiff']),
                'debugBacktraceFile' => ContainerFactoryFile::getReducedFilename($elem['file']),
                'debugBacktraceLine' => $elem['line'],
            ];
        }

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'debug');
        $templateCacheContent = $templateCache->get();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['debug']);
        $template->assign('DebugTableCache_DebugTableCache',
                          $tableTcs);

        $template->parseQuote();
        $template->parse();
        $template->catchDataClear();
        return $template->get();

    }

    public function getTitle(): string
    {
        $countRead  = 0;
        $countWrite = 0;

        foreach ($this->data as $elem) {
            if ($elem['data']['isCreated'] === false) {
                $countRead++;
            }
            else {
                $countWrite++;
            }
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');

        $template->set(ContainerFactoryLanguage::get('/ContainerExtensionCache/debug/header'));
        $template->assignArray([
                                   'countRead'  => $countRead,
                                   'countWrite' => $countWrite,
                               ]);
        $template->parse();
        return $template->get();

    }


}
