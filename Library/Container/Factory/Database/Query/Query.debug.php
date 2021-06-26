<?php

class ContainerFactoryDatabaseQuery_debug extends CoreDebug_abstract
{

    protected array $counter
        = [
            'countRead'    => 0,
            'countWrite'   => 0,
            'countOther'   => 0,
            'countUnknown' => 0,
        ];

    protected function prepare(): void
    {
//        d($this->data);
//        eol();

        foreach ($this->data as $elem) {
            if (isset($elem['data']['query'])) {

                if (
                    strpos($elem['data']['query'],
                           'SELECT') === 0
                ) {
                    ++$this->counter['countRead'];
                }
                elseif (
                    strpos($elem['data']['query'],
                           'REPLACE INTO') === 0
                ) {
                    ++$this->counter['countWrite'];
                }
                elseif (
                    strpos($elem['data']['query'],
                           'INSERT INTO') === 0
                ) {
                    ++$this->counter['countWrite'];
                }
                else {
                    ++$this->counter['countOther'];
                }
            }
            else {
                ++$this->counter['countUnknown'];
            }
        }
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getHtml(): string
    {//        eol();
        $sqlWrite = [
            'INSERT',
            'REPLACE',
            'UPDATE',
        ];

//        d($this->data);
//        eol();

        $tableTcs = [];

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'debug');
        $templateCacheContent = $templateCache->getCacheContent();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['debug']);
        $template->assign('DebugTableQuery_DebugTableQuery',
                          $tableTcs);

        foreach ($this->data as $elem) {

            if (isset($elem['data']['query'])) {

                $queryFirst = strtoupper(substr($elem['data']['query'],
                                                0,
                                                strpos($elem['data']['query'],
                                                       ' ')));
            }
            else {
                $queryFirst = '';
            }


            $dialog = uniqid();
            /** @var ContainerExtensionTemplateParseHelperDialog $templateDialog */
            $templateDialog = Container::get('ContainerExtensionTemplateParseHelperDialog',
                                             $dialog);
            $templateDialog->setHeader('Backtrace');
            $templateDialog->setBody(ContainerHelperView::convertBacktraceView($elem['backtrace']));
            $templateDialog->setFooter();

            /** @var ContainerExtensionTemplateParseHelperDialog $templateDialogData */
            $templateDialogData = Container::get('ContainerExtensionTemplateParseHelperDialog');
            $templateDialogData->setHeader('Parameter');
            $parameter = \ContainerHelperCode::viewArrayAsTable($elem['data']['parameter'] ?? []);
            $templateDialogData->setBody('<div style="white-space: pre; overflow: auto; height: 25em;">' . strtr($parameter,
                                                                                                                 [
                                                                                                                     '{' => '&#123;',
                                                                                                                     '}' => '&#125;',
                                                                                                                 ]) . '</div>');
            $templateDialogData->setFooter();

            /** @var ContainerExtensionTemplateParseHelperDialog $templateExplainData */
            $templateExplainData = Container::get('ContainerExtensionTemplateParseHelperDialog');
            $templateExplainData->setHeader('Explain data');
            $templateExplainData->setBody('<div style="white-space: pre; overflow: auto; height: 25em;">' . strtr(htmlentities(var_export(($elem['data']['selectExplainData'] ?? null),
                                              true)),
                                                                                                                  [
                                                                                                                      '{' => '&#123;',
                                                                                                                      '}' => '&#125;',
                                                                                                                  ]) . '</div>');
            $templateExplainData->setFooter();

            $tableTcs[] = [
                'direction'          => (in_array($queryFirst,
                                                  $sqlWrite) ? '=&gt;' : '&lt;='),
                'query'              => ($elem['data']['query'] ?? ' ?'),
                'table'              => ($elem['data']['table'] ?? '?'),
                'databaseConnection' => ($elem['data']['databaseConnection'] ?? '?'),
                'rowCount'           => ($elem['data']['rowCount'] ?? '?'),
                //                'data'                => Container::get('ContainerIndexCard')
                //                                                   ->reset()
                //                                                   ->setLink('Data')
                //                                                   ->setContent($this->parameterAnalytics(($elem['parameter'] ?? [])))
                //                                                   ->parse()
                //                                                   ->get()

                'data'               => $templateDialogData->create('Parameter'),
                'selectExplainData'  => ((!empty($elem['data']['selectExplainData']) ? $templateExplainData->create('explain') : '')),
                'microtimeDiff'      => ContainerHelperCalculate::calculateMicroTimeDisplay($elem['microtimeDiff']),
                'memoryDiff'         => ContainerHelperCalculate::calculateMemoryBytes($elem['memoryDiff']),
                'debugBacktraceFile' => ContainerFactoryFile::getReducedFilename($elem['file']),
                'debugBacktraceLine' => $elem['line'],
                'backtrace'          => $templateDialog->create('Backtrace'),
                //                'backtrace'           => ((\Container::check('CoreClassesHelperView', false) === true) ? $js->get('/dialog/zoom', ['content' => (\Container::callStatic('HelperView', 'convertBacktraceView', $elem['backtrace'], false))]) : '')
            ];
        }

        $template->assign('DebugTableQuery_DebugTableQuery',
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

        $template->set(ContainerFactoryLanguage::get('/ContainerFactoryDatabaseQuery/debug/header'));
        $template->assignArray([
                                   'countRead'    => $this->counter['countRead'],
                                   'countWrite'   => $this->counter['countWrite'],
                                   'countOther'   => $this->counter['countOther'],
                                   'countUnknown' => $this->counter['countUnknown'],
                               ]);
        $template->parse();
        return $template->get();
    }

}
