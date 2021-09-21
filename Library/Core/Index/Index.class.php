<?php

class CoreIndex
{

    final public static function execute(): void
    {
        Event::importEvent();
        define('EVENT_ENABLE',
               true);

        $scope = [];

        ContainerExtensionTemplateParseInsertPositions::load();

        /** @var ContainerFactoryLogStatistic $statistic */
        $statistic = Container::get('ContainerFactoryLogStatistic',
                                    'pageCall');
        $statistic->increase();

//        eol();

//        $statistic->getStatisticDay(date('Y-m-d'));
//        d($statistic);
//        eol(true);

        /** @var ContainerFactoryRouter $route */
        $route = Container::getInstance('ContainerFactoryRouter');

//      echo ($fooBar ?? null);
//      echo $fooBar;

        if (
            \Config::get('/config/CoreIndex/maintenance',
                         false) === true
        ) {
            $route->analyzeUrl('index.php?app=ApplicationIndexMaintenance');
        }

        $urlReadableRaw  = $route->getUrlReadable(true);
        $applicationName = $route->getApplication();

        if ($route->getApplication() === '') {
            /** @var ContainerFactoryModul_crud $applicationSearch */
            /** @var ContainerFactoryModul_crud $applicationSearchResultItem */
            $applicationSearch       = Container::get('ContainerFactoryModul_crud');
            $applicationSearchResult = $applicationSearch->find([
                                                                    'crudHasContent' => '1',
                                                                ]);

            $application = null;
            foreach ($applicationSearchResult as $applicationSearchResultItem) {
                $contentObj = Container::get($applicationSearchResultItem->getCrudModul() . '_content');

                try {
                    $application = $contentObj->get($urlReadableRaw);
                } catch (Throwable $e) {
                    continue;
                }

                break;

            }

            if ($application === null) {
                /** @var Application $application */
                $application = Container::get('Application',
                                              'ApplicationIndexErrorNotfound');
            }
        }
        else {
            /** @var Application $application */
            $application = Container::get('Application',
                                          $applicationName);
        }

        /** @var ContainerIndexPage $ContainerIndexPage */
        $ContainerIndexPage = Container::getInstance('ContainerIndexPage');
        $ContainerIndexPage->addPageContentLeft($application->getContentLeft());

        $output = $ContainerIndexPage->generateFinalPage($application);

        $TempOb = ob_get_contents();
        //ob_clean();

        /** @var ContainerFactoryHeader $header */
        $header = Container::getInstance('ContainerFactoryHeader');

        switch ($application->getHeader()) {
            default:
            case 200:
                $header->set('#',
                             'HTTP/1.1 200 OK');
                break;
            case 404:
                $header->set('#',
                             'HTTP/1.1 404 Not Found');
                break;
            case 500:
                $header->set('#',
                             'HTTP/1.1 500 Internal Server Error');
                break;
            case 503:
                $header->set('#',
                             'HTTP/1.1 503 Service Temporarily Unavailable');
                break;
        }

        $scope['headerCMS'] = [
            'default-src' => [],
            'connect-src' => [],
            'script-src'  => [],
            'style-src'   => [],
            'child-src'   => [],
            'font-src'    => [],
            'img-src'     => [],
        ];

        \Event::trigger('/Core/Index/Header',
                        __FUNCTION__,
                        $scope);

        $header->set('Referrer-Policy',
                     'no-referrer-when-downgrade');
        $header->set('X-Frame-Options',
                     'SAMEORIGIN');
        $header->set('X-Xss-Protection',
                     '1;mode=block');
        $header->set('Strict-Transport-Security',
                     'max-age=31536000; includeSubdomains');

        $secureHeader = [
            'default-src \'self\' ' . implode(' ',
                                              $scope['headerCMS']['default-src']) . ';',
            'connect-src ' . Config::get('/server/http/base/url') . ' ' . implode(' ',
                                                                                  $scope['headerCMS']['connect-src']) . ';',
            'script-src \'self\' \'unsafe-eval\' \'unsafe-inline\' ' . implode(' ',
                                                                               $scope['headerCMS']['script-src']) . ';',
            'style-src \'self\' \'unsafe-inline\' ' . implode(' ',
                                                              $scope['headerCMS']['style-src']) . ';',
            'child-src \'self\' ' . implode(' ',
                                            $scope['headerCMS']['child-src']) . ';',
            'font-src \'self\' ' . implode(' ',
                                           $scope['headerCMS']['font-src']) . ';',
            'img-src \'self\' data: *',
        ];

        $header->set('Content-Security-Policy',
                     implode('',
                             $secureHeader));

        $header->set('Cache-Control',
                     'no-store, no-cache, must-revalidate, max-age=0');
        $header->set('Cache-Control',
                     'Cache-Control: post-check=0, pre-check=0',
                     false);
        $header->set('Pragma',
                     'no-cache');
        $header->set('Expires',
                     'Mon, 26 Jul 1997 05:00:00 GMT');
        $header->set('Content-Type',
                     'text/html; charset=utf-8');
        $header->set('Connection',
                     'keep-alive');
        $header->remove('X-Powered-By');
        $header->remove('Server');

        ob_clean();

        if (
            Config::get('/environment/debug/statistics/javascriptIndexEnd',
                        0) == 1
        ) {
            $output .= '<script language="JavaScript">
console.log("Page Generated @ index End; ' . ContainerHelperCalculate::calculateMicroTimeDisplay(microtime(true) - CMS_SYSTEM_START_TIME) . ' sec.")
</script>
';
        }
        if (\Config::get('/CoreIndex/gzip') == 1) {

            $output = ContainerHelperData::Gzip($output,
                                                (int)Config::get('/CoreIndex/gzip/level'));

            if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
                if (
                    strpos($_SERVER['HTTP_ACCEPT_ENCODING'],
                           'x-gzip') !== false
                ) {
                    $header->set('Content-Encoding',
                                 'x-gzip');
                }
                if (
                    strpos($_SERVER['HTTP_ACCEPT_ENCODING'],
                           'gzip') !== false
                ) {
                    $header->set('Content-Encoding',
                                 'gzip');
                }
            }
            $header->set('Content-Length',
                         strlen($output));
        }

        $header->send();

        /* Todo: Make Log */ //        if (\Config::get('/debug/status',CMS_DEBUG_ACTIVE) === true) {
//            $debugMicrotimeDebug        = CMS_SYSTEM_DEBUG_MICROTIME_END - CMS_SET_RAW_DEBUG_DATA_END;
//            $debugMicrotimePage         = CMS_SYSTEM_MICROTIME_PAGE_END - CMS_SYSTEM_MICROTIME_PAGE_START;
//            $debugMicrotimePageComplete = microtime(true) - CMS_SYSTEM_START_TIME;
//
//            $query = Container::get('ContainerFactoryDatabaseQuery',
//                                     __METHOD__ . '#insert',
//                                     true,
//                                     ContainerFactoryDatabaseQuery::MODE_INSERT);
//            /** @var ContainerFactoryDatabaseQuery $query */
//            $query->setTable('debug_statistic');
//            $query->setInsertInto('pageComplete',
//                                  (string)($debugMicrotimePageComplete));
//            $query->setInsertInto('page',
//                                  (string)($debugMicrotimePage));
//            $query->setInsertInto('pageDebug',
//                                  (string)($debugMicrotimeDebug));
//            $query->setInsertInto('pageApplication',
//                                  (string)($debugMicrotimePageComplete - $debugMicrotimeDebug - $debugMicrotimePage));
//            $query->setInsertInto('pageApplicationView',
//                                  (string)($debugMicrotimePageComplete - $debugMicrotimeDebug));
//            $query->setInsertInto('memoryEnd',
//                                  memory_get_usage());
//            $query->setInsertInto('memoryPeak',
//                                  memory_get_peak_usage());
//            $query->construct();
//            $query->execute();
//
//        }

        print $output;

    }

}
