<?php

/**
 * Class Application_abstract
 */
abstract class Application_abstract extends Base
{
    protected string $crudMain = '';

    protected int    $header     = 200;
    protected string $class      = '';
    protected string $javascript = '';
    protected string $content    = '';
    protected array  $template   = [];
    protected array  $parameter  = [];

    protected array $breadcrumb = [];

    protected ContainerFactoryMenu $menu;

    public function __construct(array ...$parameter)
    {
        $this->parameter = $parameter;
        $this->class     = Core::getRootClass(get_called_class());

        /** @var Application_cache_menu $cacheMenu */
        $cacheMenu  = Container::get('Application_cache_menu');
        $this->menu = $cacheMenu->getCacheContent();

    }

    public function getContent(): string
    {
        $app = Core::getRootClass(get_called_class());
        try {
            /** @var ContainerFactoryModul_crud $appModulData */
            $appModulData = Container::get('ContainerFactoryModul_crud');
            $appModulData->setCrudModul($app);
            $appModulData->findById();

            if (empty($appModulData->getCrudModul())) {
                throw new DetailedException('applicationAccessDenied',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    $app,
                                                ]
                                            ]);
            }

            /** @var ContainerFactoryUser $user */
            $user = Container::getInstance('ContainerFactoryUser');

            /** @var ContainerFactoryUserGroup_crud_groupaccess $groupAccess */
            $groupAccess = Container::get('ContainerFactoryUserGroup_crud_groupaccess');
            $foundAccess = $groupAccess->find([
                                                  'crudAccess'      => $app,
                                                  'crudUserGroupId' => $user->getUserGroupId()
                                              ]);

            if (count($foundAccess) === 0) {
                throw new DetailedException('applicationAccessDenied',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    $app,
                                                ]
                                            ]);
            }

            return $this->setContent();
        } catch (Throwable $e) {
//            d($e);
//            d(CoreErrorhandler::captureException($e));
//            eol();
            return CoreErrorhandler::captureException($e);
        }

    }

    /**
     * Setze Content
     *
     * @return string
     */
    abstract public function setContent(): string;

    public function getClass(): string
    {
        return $this->class;
    }

    public function getJavascript(): string
    {
        try {
            return $this->setJavascript();
        } catch (Throwable $e) {
            return CoreErrorhandler::captureException($e);
        }
    }

    public function setJavascript(): string
    {
        return '';
    }

    public function getDebugBar(): string
    {
//        debugDump(\Config::get('/debug/status',
//                               CMS_DEBUG_ACTIVE) );
//
//        if (
//            \Config::get('/debug/status',
//                         CMS_DEBUG_ACTIVE) === true
//        ) {
//            /** @var  CoreDebug $debugResult */
//            $debugResult = Container::get('CoreDebug')
//                                     ->createDebugbarFromRawDebugData();
//            return $debugResult;
//        }
//        else {
//            return '';
//        }
        return '';
    }

    public function getContentLeft(): string
    {
        try {
            return $this->setContentLeft();
        } catch (Throwable $e) {
            return CoreErrorhandler::captureException($e);
        }
    }

    public function setContentLeft(): string
    {
        return $this->generateMenu();
    }

    public function generateMenu(): string
    {
        /** @var ContainerFactoryRouter $router */
        $router = Container::getInstance('ContainerFactoryRouter');

        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');
        $this->menu->setMenuAccessList($user->getUserAccess());

        if (empty($this->menu->getMenuClassMain())) {
            $menuClass = $router->getApplication();
        }
        else {
            $menuClass = $this->menu->getMenuClassMain();
        }

        /** @var ContainerFactoryMenu_crud $menuCrud */
        $menuCrud       = Container::get('ContainerFactoryMenu_crud');
        $menuCrudResult = $menuCrud->find([
                                              'crudClass' => $menuClass
                                          ],
                                          [],
                                          [],
                                          1);

        /** @var ContainerFactoryMenu_crud $menuCrudResultFirst */
        $menuCrudResultFirst = reset($menuCrudResult);

        if (!empty($menuCrudResultFirst)) {

            /** @var ContainerFactoryLanguageParseIni $iniParser */
            $iniParser = Container::get('ContainerFactoryLanguageParseIni',
                                        $menuCrudResultFirst->getCrudData());

            $iniParserLanguages = $iniParser->getLanguages();
            if (isset($iniParserLanguages[Config::get('/environment/language')])) {
                $iniParserLanguagesMenu = $iniParserLanguages[Config::get('/environment/language')];
            }
            else {
                $iniParserLanguagesMenu = reset($iniParserLanguages);
            }
            return $this->menu->createMenu(($iniParserLanguagesMenu['path'] ?? ''),
                ($iniParserLanguagesMenu['title'] ?? ''));
        }
        else {
            return $this->menu->createMenu('',
                                           '');
        }
    }

    /**
     * @return ContainerFactoryMenu
     */
    public function ContainerFactoryMenu(): ContainerFactoryMenu
    {
        return $this->menu;
    }

    /**
     * @param ContainerFactoryMenu $menu
     */
    public function setMenu(ContainerFactoryMenu $menu): void
    {
        $this->menu = $menu;
    }

    /**
     * @return int
     */
    public function getHeader(): int
    {
        return $this->header;
    }

    /**
     * @param int $header
     */
    public function setHeader(int $header): void
    {
        $this->header = $header;
    }

    /**
     * @return ContainerExtensionTemplateParseCreatePaginationHelper
     * @throws DetailedException
     */
    protected function createPagination(): ContainerExtensionTemplateParseCreatePaginationHelper
    {
        /** @var ApplicationAdministrationContent_crud $crud */ //
        /** @var Base_abstract_crud $crud */
        $crud  = Container::get($this->crudMain);
        $count = $crud->count();

        /** @var ContainerExtensionTemplateParseCreatePaginationHelper $pagination */
        $pagination = Container::get('ContainerExtensionTemplateParseCreatePaginationHelper',
                                     'crudPagination',
                                     $count,
                                     3);
        $pagination->create();
        return $pagination;
    }

    public function getTitle(): string
    {
        return end($this->breadcrumb)['title'];
    }

    public function getMenu(): ContainerFactoryMenu
    {
        return $this->menu;
    }

}
