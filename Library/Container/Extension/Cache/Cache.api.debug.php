<?php

class ContainerExtensionCache_api_debug extends ContainerExtensionApiDebug_abstract
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
                'cacheClass'                   => $elem['cacheClass'],
                'cacheFile'                    => $elem['cacheFile'],
                'parameter'                    => $elem['parameter'],
                'debugBacktraceFile'           => $elem['_']['backtraceFile'],
                'debugBacktraceLine'           => $elem['_']['backtraceLine'],
                'debugMicrotimeDelta'          => $elem['_']['microtimeDelta'],
                'debugMicrotimeDeltaIndicator' => $elem['_']['microtimeDelta'],
                'debugMemoryDelta'             => $elem['_']['memoryDelta'],
                'debugMemoryDeltaIndicator'    => $elem['_']['memoryDeltaRaw'],
            ];

        }

//        /** @var ContainerIndexTable $table */
//        $table = Container::get('ContainerIndexTable',
//                                $tableTcs,
//                                $attribute = []);
//        $table->addStandard(true);
//        $table->setUniqid('ContainerFactoryTemplateParseDebugTable');
//        $table->setConfig('cacheClass',
//                          [
//                              'header' => $this->language['/template/debug/class'],
//                          ]);
//        $table->setConfig('cacheFile',
//                          [
//                              'header'       => $this->language['/template/debug/filename'],
//                              'modification' => [],
//                          ]);
//        $table->setConfig('parameter',
//                          [
//                              'header'       => $this->language['/template/parameter'],
//                              'modification' => [
//                                  'charwrap'  => [
//                                      'char' => ',',
//                                  ],
//                                  'attribute' => [
//                                      'style' => [
//                                          'overflow: auto;'
//                                      ],
//                                  ],
//                              ],
//                          ]);
//        $table->setConfig('debugMicrotimeDelta',
//                          [
//                              'header'       => $this->language['/template/debug/milliseconds'],
//                              'modification' => [
//                                  'attribute' => [
//                                      'style' => [
//                                          'width: 5em;'
//                                      ],
//                                  ],
//                              ],
//                          ]);
//        $table->setConfig('debugMemoryDelta',
//                          [
//                              'header'       => $this->language['/template/debug/memory'],
//                              'modification' => null,
//                          ]);
//
//        $table->setConfig('debugBacktraceFile',
//                          [
//                              'header'       => $this->language['/template/debug/filename'],
//                              'modification' => [],
//                          ]);
//        $table->setConfig('debugBacktraceLine',
//                          [
//                              'header' => $this->language['/template/debug/line'],
//                          ]);
//
//        return $table->get();
        return '';
    }

    public function getTitle(): string
    {
//        /** @var ContainerExtensionTemplate $template */
//        $template = Container::get('ContainerExtensionTemplate');
//        $template->set($this->language['/template/header']);
//        $template->assignArray([
//                                   'count' => count($this->data)
//                               ]);
//        $template->parse();
//        return $template->get();
        return '';
    }

}
