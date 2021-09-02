<?php

class ContainerExternResources_free extends Base
{
    public function __construct()
    {
        /** @var ContainerFactoryRouter $router */
        $router = Container::getInstance('ContainerFactoryRouter');

        if ($router->getRoute() === 'css') {
            $this->css();
        }
        elseif ($router->getRoute() === 'javascript') {
            $this->javascript();
        }
        elseif ($router->getRoute() === 'javascriptCollect') {
            $this->javascript(true);
        }
    }

    public function css(): string
    {

        /** @var ContainerFactoryRouter $router */
        $router          = Container::getInstance('ContainerFactoryRouter');
        $routerParameter = $router->getParameter();

//        d($router->getParameter());
//        eol();

        $gzip = ((isset($routerParameter['gzip']) && $routerParameter['gzip'] === '_gzip') ? true : false);

        /** @var ContainerExternResourcesCss_cache_css $cssCache */
        $cssCache = Container::get('ContainerExternResourcesCss_cache_css',
                                   $routerParameter['gzip'],
            ($routerParameter['design'] ?? ''));

        $hash = md5($cssCache->getCacheContent()['content']);

//        d($cssCache->getDataVariableUpdated());
//        eol();

        if ($cssCache->getDataVariableUpdated() !== '') {
            $updated = $cssCache->getDataVariableUpdated();
        }
        else {
            $updatedTine = new \DateTime();
            $updated     = $updatedTine->format((string)Config::get('/cms/date/dbase'));
        }

        /** @var ContainerHelperHeaderExtern $helperHeaderExtern */
        $helperHeaderExtern = Container::get('ContainerHelperHeaderExtern',
                                             $updated,
                                             $hash);

        $isChanged = $helperHeaderExtern->checkChanged();

        if ($cssCache->isCreated()) {
            $isChanged = true;
        }

        $cssContent = $cssCache->getCacheContent()['content'];

        $isChanged = true;

        if ($isChanged === true) {

            if ($gzip === true) {

                $cssContent = ContainerHelperData::Gzip($cssContent,
                                                        (int)Config::get('/CoreIndex/gzip/level'));

                $helperHeaderExtern->header('css',
                                            $cssContent);
            }
            else {
                $helperHeaderExtern->header('css');
            }

            /** @var ContainerFactoryHeader $header */
            $header = Container::getInstance('ContainerFactoryHeader');

            $header->send();

            echo $cssContent;
            exit();

        }
    }

    public function javascript(bool $collect = false): void
    {
        /** @var ContainerFactoryRouter $router */
        $router          = Container::getInstance('ContainerFactoryRouter');
        $routerParameter = $router->getParameter();

        $gzip = ((isset($routerParameter['gzip']) && $routerParameter['gzip'] === '_gzip') ? true : false);
        $part = ($routerParameter['part'] ?? false);

        /** @var ContainerExternResourcesJavascript $contentJsObject */
        $contentJsObject = Container::get('ContainerExternResourcesJavascript');

        /** @var ContainerExternResourcesJavascript_cache_js $jsCache */
        $jsCache = Container::get('ContainerExternResourcesJavascript_cache_js',
                                  $gzip);

        $hash = md5(implode('',
                            $jsCache->getCacheContent()));

        if ($jsCache->getDataVariableUpdated() !== '') {
            $updated = $jsCache->getDataVariableUpdated();
        }
        else {
            $updatedTine = new \DateTime();
            $updated     = $updatedTine->format((string)Config::get('/cms/date/dbase'));
        }


        /** @var ContainerHelperHeaderExtern $helperHeaderExtern */
        $helperHeaderExtern = Container::get('ContainerHelperHeaderExtern',
                                             $updated,
                                             $hash);

        $isChanged = $helperHeaderExtern->checkChanged();

        if ($jsCache->isCreated()) {
            $isChanged = true;
        }

        if ($isChanged === true) {
            $contentJs = $contentJsObject->get();

            if ($gzip === true) {
                $contentJs = ContainerHelperData::Gzip($contentJs,
                                                       (int)Config::get('/CoreIndex/gzip/level'));

                $helperHeaderExtern->header('js',
                                            $contentJs);
            }
            else {
                $helperHeaderExtern->header('js');
            }

            ob_clean();
            /** @var ContainerFactoryHeader $header */
            $header = Container::getInstance('ContainerFactoryHeader');
            $header->send();

            echo $contentJs;
        }

    }

}
