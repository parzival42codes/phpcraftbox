<?php

declare(strict_types=1);

class ContainerIndexPage
{
    protected ContainerIndexPageBreadcrumb $breadcrumb;
    protected array                        $pageParseFinal      = [];
    protected array                        $pageParse
                                                                = [
            'iconLink' => '',
        ];
    protected array                        $tooltip             = [];
    protected array                        $message
                                                                = [
            'header'              => '',
            'header_content'      => '',
            'contentCenterTop'    => '',
            'contentCenterBottom' => '',
            'footer_content'      => '',
            'footer'              => '',
        ];
    protected array                        $pageContentLeft     = [];
    protected array                        $pageContentMain     = [];
    protected array                        $pageContent
                                                                = [
            'title'            => '',
            'description'      => '',
            'javascriptTop'    => '',
            'javascriptHeader' => '',
            'javascriptFooter' => '',
            'headerInclude'    => '',
            'footerInclude'    => '',
            'javascript'       => '',
            'headerCss'        => '',
            'additional'       => '',
        ];
    protected array                        $pageData            = [];
    protected array                        $pageAction          = [];
    protected array                        $headerAside         = [];
    protected array                        $menuAdditional      = [];
    protected array                        $menuApplication     = [];
    protected array                        $page                = [];
    protected string                       $route               = '';
    protected string                       $routeKey            = '';
    protected string                       $template            = '';
    protected                              $templatePage        = null;
    protected array                        $footer
                                                                = [
            'header'          => [],
            'headerSecondary' => [],
            'headerTop'       => [],
            'headerLeft'      => [],
            'headerRight'     => [],
            'content'         => [],
            'actionLeft'      => [],
            'actionRight'     => [],
            'actionMain'      => [],
        ];
    protected array                        $window
                                                                = [
            'header' => [],
            'center' => [],
            'footer' => [],
        ];
    protected array                        $pageContainerContent
                                                                = [
            'main' => [
                'headerBefore'  => [],
                'headerAfter'   => [],
                'contentBefore' => [],
                'contentAfter'  => [],
                'footerBefore'  => [],
                'footerAfter'   => [],
            ],
            'left' => [
                'headerBefore'  => [],
                'headerAfter'   => [],
                'contentBefore' => [],
                'contentAfter'  => [],
                'footerBefore'  => [],
                'footerAfter'   => [],
            ],
        ];
    protected array                        $switch
                                                                = [
            'render' => true,
        ];
    protected array                        $textareaSafe        = [];
    protected int                          $textareaSafeCounter = 0;
    protected ContainerExtensionTemplate   $pageTemplate;
    protected array                        $pageBox             = [];
    protected array                        $notification        = [];

    public function __construct()
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        __CLASS__,
                                        'page');

        $this->pageTemplate = Container::get('ContainerExtensionTemplate');

        $this->breadcrumb = Container::get('ContainerIndexPageBreadcrumb');

        /** @var ContainerExtensionTemplate $templatePage */
        $this->templatePage = Container::get('ContainerExtensionTemplate');

        $this->templatePage->set($templateCache->getCacheContent()['page']);
        $this->templatePage->setMetaClass(__CLASS__);
    }

    public function generateFinalPage(Application $application): string
    {
//        eol(true);

        define('CMS_SYSTEM_MICROTIME_PAGE_START',
               microtime(true));
        define('CMS_SYSTEM_MEMORY_PAGE_START',
               memory_get_usage());

        $container = Container::DIC();

        /** @var ContainerExtensionTemplate $templatePage */
        $templatePage = $this->templatePage;
        $templatePage->assign('cookieBanner',
                              $container->getDIC('/Cookie/CookieBanner'));

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($application->getContent());
        $template->parse();
        $content = $template->get();

        $debugTimeStart = microtime(true);

//        d(Config::get('/environment/debug/active',
//                      CMS_DEBUG_ACTIVE));
//        eol();

        if (
        \Config::get('/environment/debug/active',
                     CMS_DEBUG_ACTIVE)
        ) {
            $this->addPageJavascript(\CoreDebug::getJavascriptSource());
//            $this->pageContent['footerInclude'] = $application->getDebugBar();
            $this->pageContent['footerInclude'] = Container::get('CoreDebug')
                                                           ->createDebugbarFromRawDebugData();
        }

        \Event::trigger('/' . __CLASS__ . '/Template/Positions');

        $debugTimeEnd  = microtime(true);
        $debugTimeDiff = ($debugTimeEnd - $debugTimeStart);

        $this->addPageJavascript(\ContainerIndexTab::getCollect());

        $scope = [];

//        \Event::trigger(__CLASS__,
//                        __FUNCTION__,
//                        'final',
//                        $this,
//                        $scope);
        if ($this->switch['render'] === false) {
            return $content;
        }

        /** @var ContainerFactoryRequest $request */
        $request = Container::get('ContainerFactoryRequest',
                                  ContainerFactoryRequest::REQUEST_GET,
                                  '_notification');
        if ($request->exists()) {
            /** @var ContainerFactoryLog_crud_notification $crud */
            $crud = Container::get('ContainerFactoryLog_crud_notification');
            $crud->setCrudUniqueId($request->get());

            if ($crud->findById()) {

                $this->addNotificationDisplay($crud->getCrudMessage(),
                                              $crud->getCrudCssClass());

                if ($crud->getCrudType() === ContainerFactoryLog_crud_notification::NOTIFICATION_REQUEST) {
                    $crud->delete();
                }

            }

        }

        //-------------------------------------------------------------------------------------------------------

        if (($this->pageContent['header'] ?? 200) === 200) {
            $favIconObj = Container::get('ContainerIndexPage_cache_favicon');
        }
        else {
            $favIconObj = Container::get('ContainerIndexPage_cache_favicon',
                                         'error');
        }

        $favIcon = $favIconObj->getCacheContent();

        $favIconImg = imagecreatefromstring(base64_decode($favIcon));

        imagesavealpha($favIconImg,
                       true);
        $color = imagecolorallocatealpha($favIconImg,
                                         0,
                                         0,
                                         0,
                                         127);

        $color = imagecolorallocate($favIconImg,
                                    0,
                                    0,
                                    0);
        imagestring($favIconImg,
                    1,
                    1,
                    8,
                    '1234',
                    $color);

        $imgPath = CMS_PATH_STORAGE_CACHE . '/favicon.png';

        imagepng($favIconImg,
                 $imgPath,
                 9,
                 PNG_ALL_FILTERS);

        $favIcon = base64_encode(file_get_contents($imgPath));
        unlink($imgPath);
        imagedestroy($favIconImg);

        $templatePage->assign('headerLinkFavicon',
                              $favIcon);

        /** @var ContainerIndexHtmlAttribute $atrribute */
        $atrribute = Container::get('ContainerIndexHtmlAttribute');
        $atrribute->set('style',
                        'background',
                        'green');


        /** @var ContainerIndexPageBox $pageBox */
        $pageBox     = Container::get('ContainerIndexPageBox');
        $pageContent = $pageBox->get('page');

        $this->pageTemplate->set($pageContent);
        $this->pageTemplate->assign('notification',
                                    implode('',
                                            $this->notification));
        $this->pageTemplate->assign('applicationContentLeft',
                                    $application->getContentLeft());
        $this->pageTemplate->assign('applicationContent',
                                    $content);

        $this->pageTemplate->assign('breadcrumb',
                                    $this->breadcrumb->getHtmlList());

        $this->pageTemplate->assignArray($this->pageBox);

        $this->pageTemplate->parse();
        $this->pageTemplate->parse();
        $pageContent = $this->pageTemplate->get();

        $pageContent = preg_replace_callback("@<textarea(.*?)textarea>@is",
            function ($replace) {
                $this->textareaSafeCounter++;
                $replaceOutput                      = '####FormTextareaSafe' . $this->textareaSafeCounter . '####';
                $this->textareaSafe[$replaceOutput] = $replace[0];
                return $replaceOutput;
            },
                                             $pageContent);

        $pageContent = preg_replace("/[\n\r\t]+/is",
                                    '',
                                    $pageContent,
                                    -1);
        $pageContent = preg_replace("/[\s]{2,}/is",
                                    ' ',
                                    $pageContent,
                                    -1);

        foreach ($this->textareaSafe as $textareaSafeKey => $textareaSafeValue) {
            $pageContent = str_replace($textareaSafeKey,
                                       $textareaSafeValue,
                                       $pageContent);
        }

        //-------------------------------------------------------------------------------------------------------

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($pageContent);
        $template->setDelimiterStart('\<\#');
        $template->setDelimiterEnd('\#\>');
        $template->setDelimiterStartInner('\<\#');
        $template->setDelimiterEndInner('\#\>');
        $template->assignArray($this->pageParseFinal);
        $template->parse();
        $pageContent = $template->get();

        $linkrewrite = function ($link) {
             $strposIndex = strpos($link[2],
                                  'index.php');
            if ($strposIndex !== false) {
                /** @var ContainerFactoryRouter $router */
                $router = Container::get('ContainerFactoryRouter');
                $router->analyzeUrl($link[2]);

                $router->getUrlReadable();

                return 'href="' . $router->getUrlReadable(true) . '"';
            }
            else {
                return $link[0];
            }
        };
        $pageContent = preg_replace_callback('!href=([\"\']?)(.*?)[\"\']!i',
                                             $linkrewrite,
                                             $pageContent);

        $templatePage->assign('pageContent',
                              $pageContent);
        unset($content, $pageContent);

        //-------------------------------------------------------------------------------------------------------

        \Event::trigger('pageContentReady',
                        __FUNCTION__,
                        $this);

        $styleSelected = \Config::get('/Style/selected');

        $coreLoad = '';

        $pushHeaderCSS = \Config::get('/server/http/base/url') . '/resources/css/' . ucfirst($styleSelected) . '/core' . ((\Config::get('/Core/gzip') == 1) ? '_gzip' : '') . '.css';

        $coreLoad .= '<link rel="stylesheet" type="text/css" media="dummy" href="' . $pushHeaderCSS . '" onload="if (media != \'screen\') media = \'screen\'" />';
        $coreLoad .= '<noscript><link rel="stylesheet"f media="screen" href="' . $pushHeaderCSS . '" /></noscript>';

        /** @var ContainerExternResourcesJavascript $contentJsObject */
        $contentJsObject = Container::get('ContainerExternResourcesJavascript');

        $hashLoadJSCounter = 1;
        $coreLoad          .= '<script src="' . \Config::get('/server/http/base/url') . '/resources/javascript/core/javascript' . ((\Config::get('/Core/gzip') == 1) ? '_gzip' : '') . '.js" defer></script>' . PHP_EOL;

        $templatePage->assign('coreLoad',
                              $coreLoad);
        $templatePage->assign('hashLoadJSCounter',
                              $hashLoadJSCounter);

        $templatePage->assign('lang',
                              \Config::get('/environment/config/html_language_code'));

        $templatePageAboveTheFold = Container::get('ContainerIndexPage_cache_abovethefold');

        $headerCssHash = $templatePage->addParseFinal('<style type="text/css">' . $templatePageAboveTheFold->getCacheContent() . $this->pageContent['headerCss'] . '</style>');

        $templatePage->assign('headerCss',
                              $headerCssHash);

        $templatePage->assign('headerInclude',
            (($this->pageContent['headerInclude'] === '') ? '' : $this->pageContent['headerInclude']));
        $templatePage->assign('footerInclude',
            (($this->pageContent['footerInclude'] === '') ? '' : $this->pageContent['footerInclude']));

        $templatePage->assign('applicationID',
                              Core::getRootClass($application->getApplicationName()));

        $templatePage->assign('javascriptTop',
                              $this->pageContent['javascriptTop']);
        $templatePage->assign('javascriptHeader',
                              $this->pageContent['javascriptHeader']);
        $templatePage->assign('javascriptFooter',
                              $this->pageContent['javascriptFooter']);

        $javascript = '';
        //        $javascript .= '';
        //        $javascript .= 'function getReplace (replace) { ' . PHP_EOL . 'var replace_data = ' . json_encode($this->pageAll) . ';' . PHP_EOL . ' return replace_data[replace] '. PHP_EOL .'};';
        $javascript .= $this->pageContent['javascript'];

        $templatePage->assign('javascript',
                              $javascript);

        $templatePage->assign('pageContentAdditional',
                              $this->pageContent['additional']);
        $templatePage->assign('CMSUrl',
                              \Config::get('/server/http/base/url'));
        $templatePage->assign('CMSGzip',
            ((\Config::get('/Core/gzip') === true) ? '_gzip' : ''));

        $templatePage->assign('headerTitle',
                              htmlspecialchars(strtr(($this->pageContent['title'] ?? '?'),
                                  [
                                      '&#160;' => '',
                                      '&nbsp;' => '',
                                  ])));
        $templatePage->assign('headerMetaDescription',
                              $this->pageContent['description']);

        /** @var ContainerFactoryRouter $router */
        $router = Container::getInstance('ContainerFactoryRouter');

        $templatePage->assign('headerLinkRelCanonical',
                              $router->getUrlReadable());

        //-------------------------------------------------------------------------------------------------------

        $pageData = '';
        foreach ($this->pageData as $key => $value) {
            $pageData .= ' data-' . $key . '=\'' . $value . '\'';
        }

        $templatePage->assign('pageData',
                              $pageData);
        unset($pageData);

        $templatePage->assign('tooltipContainerFactoryDatabaseQuery',
                              '');

        $templatePage->parseString();

//        // load our document into a DOM object
//        $dom = new DOMDocument();
//        // we want nice output
//        $dom->preserveWhiteSpace = false;
//        $dom->loadHTML($templatePage->get());
//        $dom->formatOutput = true;
//        $templatePage->set($dom->saveHTML());

        $templatePage->set(preg_replace_callback('!href=([\"\']?)(.*?)[\"\']!i',
                                                 $linkrewrite,
                                                 $templatePage->get()));

        define('CMS_SYSTEM_MICROTIME_PAGE_END',
               microtime(true));

//        d($templatePage->get());
//        d($templatePage);
//        eol(true);

        $templatePage->parse();
        $templatePage->parse();

        $templatePage->parseFinal();

//        d($templatePage->get());
//        eol(true);

        if (
            \Config::get('/environment/debug/statistics/javascriptPageEnd',
                         0) == 0
        ) {
            return $templatePage->get();
        }
        else {
            $cmsTime      = ((microtime(true) - CMS_SYSTEM_START_TIME) - $debugTimeDiff);
            $timeTemplate = $templatePage->get();
            $timeTemplate = str_replace('</body>',
                                        '<script language="JavaScript">
console.log("Page Generated @ Page End: ' . ContainerHelperCalculate::calculateMicroTimeDisplay((microtime(true) - CMS_SYSTEM_START_TIME)) . '");
console.log("Page Generated @ Page End - Debug: ' . ContainerHelperCalculate::calculateMicroTimeDisplay($cmsTime) . '");
</script>
' . '</body>',
                                        $timeTemplate);
        }

//        eol(true);

        return $timeTemplate;
        //return preg_replace('/\[#([^\[##\]]+)#\]/i', '', $templatePage->get());
    }

    public function addPageJavascript(string $javascript): void
    {
        $this->pageContent['javascript'] .= PHP_EOL . $javascript;
    }

    public function addPageData(string $key, string $content): void
    {
        $this->pageData[$key] = $content;
    }

    public function addPageFooterInclude(string $footerInclude): void
    {
        $this->pageContent['footerInclude'] .= $footerInclude;
    }

    public function setPageTitle(string $title): void
    {
        $this->pageContent['title'] = $title;
    }

    public function setPageDescription(string $value): void
    {
        $this->pageContent['description'] = $value;
    }

    public function setRouteKey(string $routeKey): void
    {
        $this->routeKey = $routeKey;
    }

    public function getPageTitle(): string
    {
        return $this->pageContent['title'];
    }

    public function getPageDescription(): string
    {
        return $this->pageContent['description'];
    }

    /**
     * Fügt Inhalt zum Linken Bereich hinzu.
     *
     * @param string $content
     *
     * @return void
     */
    public function addPageContentLeft(string $content, int $position = 1): void
    {
        $this->pageContentLeft[$position][] = $content;
    }

    public function addPageContentMain(string $content): void
    {
        $this->pageContentMain[] = $content;
    }

    public function setFooter(string $part, string $content): void
    {
        $this->footer[$part][] = $content;
    }

    public function addNotification(ContainerFactoryLog_crud_notification $crud): string
    {
        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');

        $crud->setCrudUserId($user->getUserId());
        $crud->setCrudDisplayed($crud::DISPLAYED_NO);
        $crud->setCrudUniqueId(uniqid((string)rand(1000,
                                                   9999),
                                      true));

        if ($crud->getCrudType() === ContainerFactoryLog_crud_notification::NOTIFICATION_PAGE_DISPLAY) {
            $this->addNotificationDisplay($crud->getCrudMessage(),
                                          $crud->getCrudCssClass());
        }
        elseif ($crud->getCrudType() === ContainerFactoryLog_crud_notification::NOTIFICATION_REQUEST) {
            $crud->insert();
        }
        elseif ($crud->getCrudType() === ContainerFactoryLog_crud_notification::NOTIFICATION_LOG) {
            $this->addNotificationDisplay($crud->getCrudMessage(),
                                          $crud->getCrudCssClass());
            $crud->insert();
        }

        return $crud->getCrudUniqueId();

    }

    private function addNotificationDisplay(string $content, string $class = ''): void
    {
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               __CLASS__,
                                               'notification');
        $templateNotification = $templateCache->getCacheContent()['notification'];

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateNotification);
        $template->assign('class',
                          $class);
        $template->assign('content',
                          $content);
        $template->parse();

        $this->notification[] = $template->get();
    }

    /**
     * @return ContainerIndexPageBreadcrumb
     */
    public function getBreadcrumb(): ContainerIndexPageBreadcrumb
    {
        return $this->breadcrumb;
    }

    /**
     * @return mixed
     */
    public function getTemplatePage()
    {
        return $this->templatePage;
    }

}
