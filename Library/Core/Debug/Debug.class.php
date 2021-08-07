<?php

final class  CoreDebug
{

    protected static array $rawData
        = [
            'CoreDebugProfiler' => [],
            'CoreDebugDump'     => [],
            'CoreErrorhandler'  => [],
        ];

    protected static array $debugCache = [];

    protected string $keySys     = '_SystemInformation';
    protected        $keySysItem = null;

    public static function getSourceCodeInFile(string $file, int $line, int $rows = 50): string
    {
        /** @var ContainerFactoryFile $fileContent */
        $fileContent = Container::get('ContainerFactoryFile',
                                      $file,
                                      true);
        $fileContent->load();
        $fileViewContent = $fileContent->get();

//        d($fileContent);
//        d($fileViewContent);
//        d($file);
//        eol();

        $outputCollect = [];

        if ($fileViewContent !== false) {
            $fileViewContent = explode("\n",
                                       $fileViewContent);
            array_unshift($fileViewContent,
                          '');

            $fileViewContentLine = 0;

            if ($rows > 0) {

                $fileViewContentLine  = $line;
                $fileViewContentCount = count($fileViewContent) - 1;
                $fileViewContentStart = ($fileViewContentLine - $rows);
                if ($fileViewContentStart < 0) {
                    $fileViewContentStart = 0;
                }
                $fileViewContentEnd = ($fileViewContentLine + $rows);
                if ($fileViewContentEnd > $fileViewContentCount) {
                    $fileViewContentEnd = $fileViewContentCount;
                }
            }
            else {
                $fileViewContentStart = $line;
                $fileViewContentEnd   = $line;
            }

            for ($i = $fileViewContentStart; $i <= $fileViewContentEnd; $i++) {
                $outputCollect[] = $fileViewContent[$i];
//                if ($i !== $fileViewContentLine) {
//                    $Output .= '<div class="errorFileViewContentRow" style="overflow: hidden;"><span class="errorFileViewContentLine">' . $i . '</span><span class="errorFileViewContent">' . htmlentities($fileViewContent[$i]) . '</span></div>';
//
//                }
//                else {
//                    $Output .= '<div class="errorFileViewContentRow" style="overflow: hidden;"><span class="errorFileViewContentLine">' . $i . '</span><span class="errorFileViewContentMarked">' . htmlentities($fileViewContent[$i]) . '</span></div>';
//                }
            }

            unset($fileViewContent, $fileViewContentCount, $fileViewContentStart, $fileViewContentEnd, $fileViewContentLine);
        }
//        $Output .= '</div>';
        return implode(PHP_EOL,
                       $outputCollect);
    }

    public static function getRawDebugData(?string $class = null)
    {
        return ($class === null ? self::$rawData : self::$rawData[$class]);
    }

    public static function getJavascriptSource(): string
    {
        return '
             $( document ).ready(function() {

                jQuery("#CoreClassesIndexPage-container-footer").css("padding-bottom","100px");
                jQuery("#debugBarBlock").show();

                var debugHeight = parseInt(jQuery(window).height() / 2);


             });
          ';
    }

    public function createDebugbarFromRawDebugData(): string
    {
        $scope = [];

        \Event::trigger('/core/debug/createDebugbarFromRawDebugData/init',
                        $this,
                        $scope);

        define('CMS_SET_RAW_DEBUG_DATA_END',
               microtime(true));
        define('CMS_SET_RAW_DEBUG_DATA_END_MEMORY',
               memory_get_usage());

        $scope['rawDataKeys'] = array_keys(self::$rawData);

        $mapping = [
            'ContainerExtensionApiDebug_abstract',
        ];

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'debugbar');
        $templates     = $templateCache->getCacheContent();

        /** @var ContainerExtensionTemplate $templateDebug */
        $templateDebug = Container::get('ContainerExtensionTemplate');
        $templateDebug->set($templates['debugbar']);

        $eventTabItem = [];

        /** @var ContainerIndexTab $eventTab */
        $eventTab = Container::get('ContainerIndexTab',
                                   'tabDebugBar');
        $eventTab->setConfig('collect',
                             true);

        $this->tabSystemInfoInit($eventTab);

        \Event::trigger('/core/debug/createDebugbarFromRawDebugData/beforeRawDataKeys',
                        $this,
                        $scope);

        $errorHandlerSearch = array_search('CoreErrorhandler',
                                           $scope['rawDataKeys']);
        if (is_numeric($errorHandlerSearch)) {
            unset($scope['rawDataKeys'][$errorHandlerSearch]);
            $scope['rawDataKeys'][] = 'CoreErrorhandler';
        }

        $exclude = explode(',',
                           Config::get('/environment/debug/debugbar/remove'));

//        d($exclude);
//        d($scope['rawDataKeys']);
//        eol(true);

        foreach ($scope['rawDataKeys'] as $key) {

            if (
            in_array($key,
                     $exclude)
            ) {
                continue;
            }

            $eventTabItem[$key] = $eventTab->createTab($key);

            $exceptioncatch = Container::get(/**
             * @param ContainerIndexTabItem $eventTabItem
             * @param string                $key
             *
             * @throws DetailedException
             */ 'ContainerFactoryExceptioncatch',
                function (array $eventTabItem, string $key) {

                    if (class_exists($key . '_debug')) {

                        /** @var ContainerExtensionApi $api */
                        $debug = Container::get($key . '_debug',
                                                self::$rawData[$key]);

                        $eventTabItem[$key]->setTitle($debug->getTitle() . ' | ' . $debug->getTime());
                        $eventTabItem[$key]->setContent($debug->getHtml() ?? '');

                    }

                },
                $eventTabItem,
                $key);

            if ($exceptioncatch->hasException()) {
                $exception = $exceptioncatch->getException();

                $exceptionMessage = \CoreErrorhandler::doExceptionView($exception);
                $eventTabItem[$key]->setTitle($key)
                                   ->setContent($exceptionMessage);
            }

        }

        $this->tabSystemInfo();

        $eventTab->setConfig('triggerFirst',
                             false);
        $eventTab->setConfig('titleWithtMax',
                             false);
        $eventTab->setConfig('tabHeightMax',
                             'debugHeight');

        define('CMS_SYSTEM_DEBUG_MICROTIME_END',
               microtime(true));
        define('CMS_SYSTEM_DEBUG_MEMORY_END',
               memory_get_usage());

        $templateDebug->assign('content',
                               $eventTab->get());
        $templateDebug->parse();
        return $templateDebug->get();
    }

    protected function tabSystemInfoInit(ContainerIndexTab $eventTab): void
    {
        $this->keySysItem = $eventTab->createTab($this->keySys);
    }

    protected function tabSystemInfo(): void
    {
        /** @var ContainerIndexTab $keySysTab */
        $keySysTab = Container::get('ContainerIndexTab',
                                    $this->keySys);
        $keySysTab->setConfig('collect',
                              true);

        $this->tabSystemInfoInfo($keySysTab,
                                 $this->keySys . 'Info');

        $this->tabSystemInfoServer($keySysTab,
                                   $this->keySys . 'Server');

        $this->tabSystemInfoExtensions($keySysTab,
                                       $this->keySys . 'Extensions');

        $this->tabSystemInfoEnv($keySysTab,
                                $this->keySys . 'Env');

        $this->tabSystemInfoGet($keySysTab,
                                $this->keySys . 'Get');

        $this->tabSystemInfoPost($keySysTab,
                                 $this->keySys . 'Post');

        $this->tabSystemInfoFiles($keySysTab,
                                  $this->keySys . 'Files');

        $this->tabSystemInfoCookie($keySysTab,
                                   $this->keySys . 'Cookie');

        $this->tabSystemInfoSession($keySysTab,
                                    $this->keySys . 'Session');

        $this->tabSystemInfoConfig($keySysTab,
                                   $this->keySys . 'Config');


        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set(ContainerFactoryLanguage::get('/CoreDebug/tab/title'));
        $template->assign('datetime',
                          date(\Config::get('/cms/date')));

        $microTime = \ContainerHelperCalculate::calculateMicroTimeDisplay(CMS_SET_RAW_DEBUG_DATA_END - CMS_SYSTEM_START_TIME);

        $template->assign('microtime',
                          $microTime);
        $template->assign('peakMemory',
                          ContainerHelperCalculate::calculateMemoryBytes(memory_get_peak_usage()));
        $template->assign('endMemory',
                          ContainerHelperCalculate::calculateMemoryBytes(CMS_SET_RAW_DEBUG_DATA_END_MEMORY));
        $template->parse();

        $this->keySysItem->setTitle($template->get());
        $this->keySysItem->setContent($keySysTab->get());

        /* Sysinfo - End*/
    }

    protected function tabSystemInfoInfo(ContainerIndexTab $tab, string $key): void
    {
        /** @var ContainerFactoryRouter $router */
        $router = Container::getInstance('ContainerFactoryRouter');

        $infoTable = [
            ContainerFactoryLanguage::get('/CoreDebug/tab/info/phpversion') => phpversion(),
            'Route/'                                                        => [
                ContainerFactoryLanguage::get('/CoreDebug/tab/info/route/application') => $router->getApplication(),
                ContainerFactoryLanguage::get('/CoreDebug/tab/info/route/route')       => $router->getRoute(),
                ContainerFactoryLanguage::get('/CoreDebug/tab/info/route/parameter')   => var_export($router->getParameter(),
                                                                                                     true),
                ContainerFactoryLanguage::get('/CoreDebug/tab/info/route/target')      => $router->getTarget(),
            ]
        ];

        /** @var ContainerIndexTabItem $keySysDebugTabItem */
        $keySysDebugTabItem = $tab->createTab($key);
        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/info'));
        $keySysDebugTabItem->setContent(\ContainerHelperCode::viewArrayAsTable($infoTable));
    }

    protected function tabSystemInfoServer(ContainerIndexTab $tab, string $key): void
    {
        /** @var ContainerIndexTabItem $keySysDebugTabItem */
        $keySysDebugTabItem = $tab->createTab($key);
        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/server'));

        $tableContent = \ContainerHelperCode::viewArrayAsTable($_SERVER);
        if (
            strpos($tableContent,
                   'data-empty="1"') === false
        ) {
            $keySysDebugTabItem->setContent($tableContent);
        }
        else {
            $keySysDebugTabItem->setContent('');
        }
    }

    protected function tabSystemInfoExtensions(ContainerIndexTab $tab, string $key): void
    {
        /** @var ContainerIndexTab $tab */
        /** @var ContainerIndexTabItem $keySysDebugTabItem */
        $keySysDebugTabItem = $tab->createTab($key);
        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/extensions'));

        $loadedExtension = get_loaded_extensions();
        sort($loadedExtension);
        $tableContent = \ContainerHelperCode::viewArrayAsTable($loadedExtension);
        $keySysDebugTabItem->setContent($tableContent);
    }

    protected function tabSystemInfoEnv(ContainerIndexTab $tab, string $key): void
    {
        /** @var ContainerIndexTabItem $keySysDebugTabItem */
        $keySysDebugTabItem = $tab->createTab($key);
        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/env'));

        $tableContent = \ContainerHelperCode::viewArrayAsTable($_ENV);
        if (
            strpos($tableContent,
                   'data-empty="1"') === false
        ) {
            $keySysDebugTabItem->setContent($tableContent);
        }
        else {
            $keySysDebugTabItem->setContent('');
        }

    }

    protected function tabSystemInfoGet(ContainerIndexTab $tab, string $key): void
    {
        /** @var ContainerIndexTabItem $keySysDebugTabItem */
        $keySysDebugTabItem = $tab->createTab($key);
        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/get'));

        $tableContent = \ContainerHelperCode::viewArrayAsTable($_GET);
        if (
            strpos($tableContent,
                   'data-empty="1"') === false
        ) {
            $keySysDebugTabItem->setContent($tableContent);
        }
        else {
            $keySysDebugTabItem->setContent('');
        }
    }

    protected function tabSystemInfoPost(ContainerIndexTab $tab, string $key): void
    {
        /** @var ContainerIndexTabItem $keySysDebugTabItem */
        $keySysDebugTabItem = $tab->createTab($key);
        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/post'));

        $tableContent = \ContainerHelperCode::viewArrayAsTable($_POST);
        if (
            strpos($tableContent,
                   'data-empty="1"') === false
        ) {
            $keySysDebugTabItem->setContent($tableContent);
        }
        else {
            $keySysDebugTabItem->setContent('');
        }
    }

    protected function tabSystemInfoFiles(ContainerIndexTab $tab, string $key): void
    {
        /** @var ContainerIndexTabItem $keySysDebugTabItem */
        $keySysDebugTabItem = $tab->createTab($key);
        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/files'));

        $tableContent = \ContainerHelperCode::viewArrayAsTable($_FILES);
        if (
            strpos($tableContent,
                   'data-empty="1"') === false
        ) {
            $keySysDebugTabItem->setContent($tableContent);
        }
        else {
            $keySysDebugTabItem->setContent('');
        }
    }

    protected function tabSystemInfoCookie(ContainerIndexTab $tab, string $key): void
    {
        /** @var ContainerIndexTabItem $keySysDebugTabItem */
        $keySysDebugTabItem = $tab->createTab($key);
        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/cookie'));

        $tableContent = \ContainerHelperCode::viewArrayAsTable($_COOKIE);
        if (
            strpos($tableContent,
                   'data-empty="1"') === false
        ) {
            $keySysDebugTabItem->setContent($tableContent);
        }
        else {
            $keySysDebugTabItem->setContent('');
        }
    }

    protected function tabSystemInfoSession(ContainerIndexTab $tab, string $key): void
    {
        /** @var ContainerIndexTabItem $keySysDebugTabItem */
        $keySysDebugTabItem = $tab->createTab($key);
        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/session'));

        $tableContent = \ContainerHelperCode::viewArrayAsTable(($_SESSION ?? []));
        if (
            strpos($tableContent,
                   'data-empty="1"') === false
        ) {
            $keySysDebugTabItem->setContent($tableContent);
        }
        else {
            $keySysDebugTabItem->setContent('');
        }
    }

    protected function tabSystemInfoConfig(ContainerIndexTab $tab, string $key): void
    {
        /** @var ContainerIndexTabItem $keySysDebugTabItem */
        $keySysDebugTabItem = $tab->createTab($key);
        $keySysDebugTabItem->setTitle(ContainerFactoryLanguage::get('/CoreDebug/tab/config'));

        $tableContent = \ContainerHelperCode::viewArrayAsTable(\Config::getAll());
        if (
            strpos($tableContent,
                   'data-empty="1"') === false
        ) {
            $keySysDebugTabItem->setContent($tableContent);
        }
        else {
            $keySysDebugTabItem->setContent('');
        }
    }

    public static function setRawDebugData(string $class, array $data = [], string $key = null, bool $keyArray = false, string $cacheKey = null, string $cache = null): void
    {

        if ($key === null) {
            self::$rawData[$class][] = $data;
        }
        else {
            if ($keyArray === false) {
                self::$rawData[$class][$key] = $data;
            }
            else {
                self::$rawData[$class][$key][] = $data;
            }
        }
        if ($cacheKey !== null) {
            self::$debugCache[$class][$cacheKey] = $cache;
        }

    }

}
