<?php

class ContainerFactoryExceptioncatch_api_debug extends ContainerExtensionApiDebug_abstract
{

    public function getHtml(): string
    {
        $tableTcs = [];
        foreach ($this->data as $elem) {

            $tableTcs[] = [
                'class'     => $elem['class'],
                'message'   => $elem['message'],
                'parameter' => var_export($elem['parameter'],
                                          true),
                'file'      => \ContainerFactoryFile::getReducedFilename($elem['file']),
                'line'      => $elem['line'],
                'backtrace' => \ContainerHelperView::convertBacktraceView($elem['backtrace']),
            ];

        }

//        /** @var ContainerIndexTable $table */
//        $table = Container::get('ContainerIndexTable',
//                                 $tableTcs,
//                                 $attribute = []);
//        $table->addStandard(true);
//        $table->setUniqid('ContainerFactoryExceptionsDebugTable');
//        $table->setConfig('class',
//                          [
//                              'header'       => $this->language['/template/class'],
//                              'modification' => null,
//                          ]);
//        $table->setConfig('message',
//                          [
//                              'header'       => $this->language['/template/message'],
//                              'modification' => null,
//                          ]);
//        $table->setConfig('parameter',
//                          [
//                              'header'       => $this->language['/template/parameter'],
//                              'modification' => null,
//                          ]);
//        $table->setConfig('file',
//                          [
//                              'header'       => $this->language['/template/debug/filename'],
//                              'modification' => [
//                                  'charwrap' => [
//                                      'char' => '\/\\\.\#',
//                                  ]
//                              ]
//                          ]);
//        $table->setConfig('line',
//                          [
//                              'header'       => $this->language['/template/debug/line'],
//                              'modification' => null,
//
//                          ]);
//        $table->setConfig('backtrace',
//                          [
//                              'header'       => $this->language['/template/debug/backtrace'],
//                              'modification' => null,
//
//                          ]);
//
//
//        //        $table->setConfigView('javascript', ' $(document).ready(function () {
//        //        jQuery( "#tabButtontabDebugBar' . $rootClass . '").click(function() {
//        //            jQuery("#' . $table->getUniqid() . '").trigger("resize");
//        //        });
//        //    });');
//
//        return $table->get();

        return '';
    }

    public function getTitle(): string
    {
        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');

        $template->set(ContainerFactoryLanguage::get('/ContainerFactoryDatabaseQuery/template/header'));
        $template->assignArray([
                          'count' => count($this->getData()),
                      ]);
        $template->parse();
        return $template->get();
    }

    public function getData(): array
    {
        return $this->data;
    }

}
