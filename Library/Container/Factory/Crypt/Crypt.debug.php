<?php

class ContainerFactoryCrypt_debug extends ContainerExtensionApiDebug_abstract
{
    public function getData(): array
    {
        return $this->data;
    }

    public function getHtml(): string
    {
        $tableTcs = [];
        foreach ($this->data as $elem) {

            $tableTcs[] = [
                'action'             => ($elem['data']['action'] ?? '?'),
                'length'             => ($elem['data']['length'] ?? '?'),
                'cipher'             => ($elem['data']['cipher'] ?? '?'),
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
        $template->assign('DebugTableCrypt_DebugTableCrypt',
                          $tableTcs);

        $template->parseQuote();
        $template->parse();
        $template->catchDataClear();
        return $template->get();
    }

    public function getTitle(): string
    {
        $actionCount = [
            'encrypt' => 0,
            'decrypt' => 0,
        ];
        foreach ($this->data as $elem) {
            $actionCount[$elem['data']['action']]++;
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set(ContainerFactoryLanguage::get('/ContainerFactoryCrypt/debug/title'));
        $template->assignArray([
                                   'encrypt' => $actionCount['encrypt'],
                                   'decrypt' => $actionCount['decrypt'],
                               ]);
        $template->parse();
        return $template->get();
    }

}
