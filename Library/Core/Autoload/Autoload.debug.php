<?php

class CoreAutoload_debug extends CoreDebug_abstract
{
    protected array $collectClass = [];

    protected function prepare(): void
    {

        foreach ($this->data as $elem) {
            $this->collectClass[$elem['class']] = $elem;
        }

        foreach ($this->collectClass as $collectClassKey => $collectClassItem) {
            $this->collectClass[$collectClassKey]['microtime']        = ContainerHelperCalculate::calculateMicroTimeDisplay($collectClassItem['microtime']);
            $this->collectClass[$collectClassKey]['classDefinedFile'] = ContainerFactoryFile::getFilenameWrap(($collectClassItem['classDefinedFile'] ?? ''));
            $this->collectClass[$collectClassKey]['file']             = ContainerFactoryFile::getFilenameWrap(($collectClassItem['trace'][2]['file'] ?? ''));
            $this->collectClass[$collectClassKey]['line']             = ($collectClassItem['trace'][2]['line'] ?? '');
            $this->collectClass[$collectClassKey]['function']         = strtr($collectClassItem['trace'][2]['function'] ?? '',
                                                                              [
                                                                                  '{' => '&#123;',
                                                                                  '}' => '&#125;',
                                                                              ]);
            unset($this->collectClass[$collectClassKey]['trace']);
        }

    }

    public function getHtml(): string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'debug');
        $templateCacheContent = $templateCache->getCacheContent();


        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['debug']);
        $template->assign('CoreAutoloadDebugTable_CoreAutoloadDebugTable',
                          $this->collectClass);


        $template->parseQuote();
        $template->parse();

        $template->parse();
        $template->catchDataClear();

        return $template->get();
    }

    public function getTitle(): string
    {
        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set(ContainerFactoryLanguage::get('/CoreAutoload/debug/header'));
        $template->assignArray([
                                   'count' => count($this->collectClass),
                               ]);
        $template->parse();
        return $template->get();
    }


}
