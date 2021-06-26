<?php

class  CoreDebugDump_debug extends ContainerExtensionApiDebug_abstract
{

    public function getData(): array
    {
        return $this->data;
    }

    public function getHtml(): string
    {
        /** @var ContainerIndexTab $eventTab */
        $eventTab = Container::get('ContainerIndexTab',
                                   'tabDebugBarDebugDump');
        $eventTab->setConfig('collect',
                             true);
        $eventTabItem = [];

        foreach ($this->data as $key => $elem) {

            $eventTabItem[$key] = $eventTab->createTab($key);

            $file = $elem['backtrace'][2]['file'];
            $line = $elem['backtrace'][2]['line'];

            $elem['filename'] = $file;

            $elem['title'] = '?';
            $code          = CoreDebug::getSourceCodeInFile($file,
                                                            $line,
                                                            1);

            preg_match('!(.*)debugDump\((.*?)\);!si',
                       $code,
                       $Temp);
            if (isset($Temp[1]) === true) {
                $elem['title'] = $Temp[2];
            }

            $type = gettype($elem['dump']);

            $dumpClass = Core::getRootClass(__CLASS__) . ucfirst(strtolower($type));
            /** @var  CoreDebugDump_abstract_api $debugDump */
            if (class_exists($dumpClass) === true) {
                $debugDump = Container::get($dumpClass,
                                            $elem['dump'],
                                            $elem['title']);
            }
            else {
                $debugDump = Container::get(Core::getRootClass(__CLASS__) . 'Default',
                                            $elem['dump'],
                                            $elem['title']);
            }

            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            Core::getRootClass(__CLASS__),
                                            'debug_row');
            $templates     = $templateCache->getCacheContent();

            /** @var ContainerExtensionTemplate $templateRow */
            $templateRow = Container::get('ContainerExtensionTemplate');
            $templateRow->set($templates['debug_row']);
            $templateRow->assignArray([
                                          'key'                => $key,
                                          'title'              => (($elem['info'] !== '') ? $elem['info'] . ' | ' : '') . $elem['title'] . ' | ' . $type . ' - ' . basename($file) . ' @ ' . $line,
                                          'dump'               => $debugDump->getContent(),
                                          'type'               => $type,
                                          'additional'         => $debugDump->getAdditional(),
                                          'temp'               => $code,
                                          'code'               => '',
                                          'memory'             => 0,
                                          'backtrace'          => \ContainerHelperView::convertBacktraceView($elem['backtrace'],
                                                                                                             false),
                                          'debugBacktraceFile' => ContainerFactoryFile::getReducedFilename($file),
                                          'debugBacktraceLine' => $line,
                                      ]);

            $templateRow->parse();

            $eventTabItem[$key]->setTitle($debugDump->getTitle());
            $eventTabItem[$key]->setContent($templateRow->get());

        }

        $eventTab->setConfig('triggerFirst',
                             true);
        $eventTab->setConfig('titleWithtMax',
                             false);

        return $eventTab->get();
    }

    public function getTitle(): string
    {
//        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
//        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
//                                         Core::getRootClass(__CLASS__),
//                                         [
//                                             'debug_row'
//                                         ]);
//        $templates     = $templateCache->getCacheContent();
//
//        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/cachereset'));

//        d(Container::get('ContainerExtensionTemplate')
//                    ->set(ContainerFactoryLanguage::get('/CoreDebug/template/header'))
//                    ->assignArray([
//                                      'count' => count($this->data),
//                                  ])
//                    ->parse());
//        eol();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set(ContainerFactoryLanguage::get('/CoreDebugDump/template/header'));
        $template->assignArray([
                                   'count' => count($this->data),
                               ]);
        $template->parse();
        return $template->get();
    }

}
