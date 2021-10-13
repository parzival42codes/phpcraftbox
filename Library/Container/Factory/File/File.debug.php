<?php

class ContainerFactoryFile_debug extends CoreDebug_abstract
{


    protected function prepare(): void
    {

    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getHtml(): string
    {//        d($this->data);

        $tableTcs = [];
        foreach ($this->data as $elem) {

            switch ($elem['data']['action']) {
                case 'load':
                    $direction = '=&gt';
                    break;
                case 'save':
                    $direction = '&lt=';
                    break;
                default:
                    $direction = '?';
            }

            $dialog = uniqid();
            /** @var ContainerExtensionTemplateParseHelperDialog $templateDialog */
            $templateDialog = Container::get('ContainerExtensionTemplateParseHelperDialog',
                                             $dialog);
            $templateDialog->setHeader('Backtrace');
            $templateDialog->setBody(ContainerHelperView::convertBacktraceView($elem['backtrace']));
            $templateDialog->setFooter();

            $tableTcs[] = [
                'direction'          => $direction,
                'filenameTarget'     => ContainerFactoryFile::getFilenameWrap($elem['data']['filename']),
                'debugBacktraceFile' => ContainerFactoryFile::getFilenameWrap($elem['file']),
                'debugBacktraceLine' => $elem['line'],
                'backtrace'          => $templateDialog->create('Backtrace')
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
        $template->assign('DebugTableFile_DebugTableFile',
                          $tableTcs);

        $template->parseQuote();
        $template->parse();
        $template->catchDataClear();
        return $template->get();

    }

    public function getTitle(): string
    {
        $contentDataListLoad = 0;
        $contentDataListSave = 0;

        foreach ($this->data as $elem) {

            if ($elem['data']['action'] === 'load') {
                ++$contentDataListLoad;
            }
            elseif ($elem['data']['action'] === 'save') {
                ++$contentDataListSave;
            }
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set(ContainerFactoryLanguage::get('/ContainerFactoryFile/debug/header'));
        $template->assignArray([
                                   'load' => $contentDataListLoad,
                                   'save' => $contentDataListSave,
                               ]);
        $template->parse();
        return $template->get();
    }

}
