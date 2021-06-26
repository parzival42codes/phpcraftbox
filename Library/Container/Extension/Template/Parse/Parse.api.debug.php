<?php

class ContainerExtensionTemplateParse_api_debug extends ContainerExtensionApiDebug_abstract
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
                'parseCLass'                   => $elem['parseClass'],
                'parseString'                  => $elem['parseString'],
                'debugBacktraceFile'           => $elem['_']['backtraceFile'],
                'debugBacktraceLine'           => $elem['_']['backtraceLine'],
                'debugMicrotimeDelta'          => $elem['_']['microtimeDelta'],
                'debugMicrotimeDeltaIndicator' => $elem['_']['microtimeDelta'],
                'debugMemoryDelta'             => $elem['_']['memoryDelta'],
            ];

        }
//
//        /** @var ContainerIndexTable $table */
//        $table = Container::get('ContainerIndexTable',
//                                 $tableTcs,
//                                 $attribute = []);
//        $table->addStandard(true);
//        $table->setUniqid('ContainerFactoryTemplateParseDebugTable');
//        $table->setConfig('parseCLass',
//                          [
//                              'header' => $this->language['/template/parseCLass'],
//                          ]);
//        $table->setConfig('parseString',
//                          [
//                              'header'       => $this->language['/template/parseString'],
//                              'modification' => [
//                                  'charwrap' => [
//                                      'char' => '\s',
//                                  ],
//                              ],
//                          ]);
//        $table->setConfig('debugMicrotimeDelta',
//                          [
//                              'header'       => $this->language['/template/debug/milliseconds'],
//                              'modification' => [
//                                  'calculateMicrotime' => null,
//                              ],
//                          ]);
//        $table->setConfig('debugMicrotimeDeltaIndicator',
//                          [
//                              'header'       => $this->language['/template/debug/milliseconds'],
//                              'modification' => [
//                                  'Indicator' => [
//                                      'breakpoints' => [
//                                          '0' => 'green',
//                                          '0.5' => 'blue',
//                                          '1'   => 'yellow',
//                                          '2'   => 'red',
//                                      ],
//                                  ],
//
//                              ],
//                          ]);
//        $table->setConfig('debugMemoryDelta',
//                          [
//                              'header'       => $this->language['/template/debug/memory'],
//                              'modification' => null,
//                          ]);
//        $table->setConfig('debugBacktraceFile',
//                          [
//                              'header'       => $this->language['/template/debug/filename'],
//                              'modification' => [
//                                  'charwrap' => [
//                                      'char' => '\/\\\.\#',
//                                  ],
//                              ],
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

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set(ContainerFactoryLanguage::get('/ContainerExtensionTemplateParse/template/header'));
        $template->assignArray([
                                   'count' => count($this->data),
                               ]);
        $template->parse();
        return $template->get();
    }

}
