<?php declare(strict_types=1);

/**
 * User Verwaltung
 *
 * User Verwaltung
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 4
 * @modul    language_path_de_DE /Administration/Benutzer
 * @modul    language_name_de_DE Benutzer Administration
 * @modul    language_path_en_US /Administration/User
 * @modul    language_name_en_US User Administration
 */
class ApplicationAdministrationUser_app extends Application_abstract
{
    public function setContent(): string
    {

        $this->pageData();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['default']);

        /** @var ContainerExtensionTemplateParseCreateFilterHelper $filter */
        $filter = Container::get('ContainerExtensionTemplateParseCreateFilterHelper',
                                 'user');

        $filter->addFilter('crudUsername',
                           null,
                           ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/name',
                                                         [
                                                             'de_DE' => 'Benutzername',
                                                             'en_US' => 'Username',
                                                         ]),
                           'input');

        $crudUserGroupCollect = [
            '' => ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/crudEmailCheck/undefined',
                                                [
                                                    'de_DE' => 'Unbestimmt',
                                                    'en_US' => 'Undefined',
                                                ])
        ];
        /** @var ContainerFactoryUserGroup_crud $crudUserGroup */
        $crudUserGroup      = Container::get('ContainerFactoryUserGroup_crud');
        $crudUserGroupFound = $crudUserGroup->find();
        /** @var ContainerFactoryUserGroup_crud $crudUserGroupFoundItem */
        foreach ($crudUserGroupFound as $crudUserGroupFoundItem) {
            $crudUserGroupCollect[$crudUserGroupFoundItem->getCrudId()] = $crudUserGroupFoundItem->getCrudLanguage();
        }

        $filter->addFilter('crudUserGroupId',
                           null,
                           ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/crudUserGroupId',
                                                         [
                                                             'de_DE' => 'Benutzer Gruppe',
                                                             'en_US' => 'User Group',
                                                         ]),
                           'select',
                           $crudUserGroupCollect);

        $filter->addFilter('crudActivated',
                           null,
                           ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/activated',
                                                         [
                                                             'de_DE' => 'Ist der Benutzer aktiviert ?',
                                                             'en_US' => 'Is the User activated ?',
                                                         ]),
                           'select',
                           [
                               '' => ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/crudActivated/undefined',
                                                                   [
                                                                       'de_DE' => 'Unbestimmt',
                                                                       'en_US' => 'Undefined',
                                                                   ]),
                               1  => ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/crudActivated/active',
                                                                   [
                                                                       'de_DE' => 'Aktiviert',
                                                                       'en_US' => 'Activated',
                                                                   ]),
                               0  => ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/crudActivated/inactive',
                                                                   [
                                                                       'de_DE' => 'Nicht aktiviert',
                                                                       'en_US' => 'Not Activated',
                                                                   ]),
                           ]);


        $filter->addFilter('crudEmailCheck',
                           null,
                           ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/crudEmailCheck',
                                                         [
                                                             'de_DE' => 'Ist der Benutzer die E-Mai bestätigt ?',
                                                             'en_US' => 'Is the User E-Mail checked ?',
                                                         ]),
                           'select',
                           [
                               '' => ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/crudEmailCheck/undefined',
                                                                   [
                                                                       'de_DE' => 'Unbestimmt',
                                                                       'en_US' => 'Undefined',
                                                                   ]),
                               1  => ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/crudEmailCheck/yes',
                                                                   [
                                                                       'de_DE' => 'Aktiviert',
                                                                       'en_US' => 'Activated',
                                                                   ]),
                               0  => ContainerFactoryLanguage::get('/ApplicationAdministrationUser/filter/crudEmailCheck/no',
                                                                   [
                                                                       'de_DE' => 'Nicht aktiviert',
                                                                       'en_US' => 'Not Activated',
                                                                   ]),
                           ]);

        $filter->create();

        $filterValues = $filter->getFilterValues();

//        d($filterValues);

        $filterCrud = [];
        if (isset($filterValues['crudUsername']) && $filterValues['crudUsername'] !== '') {
            $filterCrud['crudUsername'] = $filterValues['crudUsername'];
        }
        if (isset($filterValues['crudUserGroupId']) && $filterValues['crudUserGroupId'] !== '') {
            $filterCrud['crudUserGroupId'] = $filterValues['crudUserGroupId'];
        }
        if (isset($filterValues['crudActivated']) && $filterValues['crudActivated'] !== '') {
            $filterCrud['crudActivated'] = $filterValues['crudActivated'];
        }
        if (isset($filterValues['crudEmailCheck']) && $filterValues['crudEmailCheck'] !== '') {
            $filterCrud['crudEmailCheck'] = $filterValues['crudEmailCheck'];
        }

//        d($filterCrud);
//
//        eol();

        /** @var ContainerFactoryUser_crud $crud */
        $crud  = Container::get('ContainerFactoryUser_crud');
        $count = $crud->count($filterCrud);

        /** @var ContainerExtensionTemplateParseCreatePaginationHelper $pagination */
        $pagination = Container::get('ContainerExtensionTemplateParseCreatePaginationHelper',
                                     'user',
                                     $count);
        $pagination->create();

        /** @var ContainerFactoryUser_crud $crud */
        $crud        = Container::get('ContainerFactoryUser_crud');
        $crudImports = $crud->find($filterCrud,
                                   [],
                                   [],
                                   $pagination->getPagesView(),
                                   $pagination->getPageOffset());

        $tableTcs = [];

        /** @var ContainerFactoryUser_crud $crudImport */
        foreach ($crudImports as $crudImport) {
            /** @var ContainerFactoryRouter $editRouter */
            $editRouter = Container::get('ContainerFactoryRouter');
            $editRouter->setRoute('edit');
            $editRouter->setApplication('ApplicationAdministrationUserEdit');
            $editRouter->setParameter('id',
                                      $crudImport->getCrudId());

            $tableTcs[] = [
                'crudId'          => $crudImport->getCrudId(),
                'crudUsername'    => $crudImport->getCrudUsername(),
                'crudUserGroupId' => $crudImport->getCrudUserGroupId(),
                'groupName'       => $crudImport->getAdditionalQuerySelect('user_group_crudLanguage'),
                'crudEmail'       => $crudImport->getCrudEmail(),
                'crudActivated'   => $crudImport->isCrudActivated(),
                'crudEmailCheck'  => $crudImport->isCrudEmailCheck(),
                'edit'            => '<a href="' . $editRouter->getUrlReadable() . '" class="btn">{insert/resources resource="icon" icon="edit"}</a>',
            ];
        }

        $template->assign('Table_Table',
                          $tableTcs);

        $template->parse();

        self::createMenu($this->___getRootClass());

        return $template->get();
    }

    public function pageData(): void
    {
        $thisClassName = Core::getRootClass(__CLASS__);

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/' . $thisClassName . '/meta/title'));
        $page->setPageDescription(ContainerFactoryLanguage::get('/' . $thisClassName . '/meta/description'));

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=' . $thisClassName . '');



        $breadcrumb = $page->getBreadcrumb();

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=ApplicationAdministration');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/' . $thisClassName . '/meta/title'),
                                       'index.php?application=ApplicationAdministrationUser');

        /** @var ContainerFactoryMenu $menu */
        $menu = $this->getMenu();
        $menu->setMenuClassMain($thisClassName);

    }

    public static function createMenu(string $class): void
    {
        /** @var ContainerFactoryMenu $menu */
        $menu = Container::get('ContainerFactoryMenu',
                               ContainerFactoryMenu::MENU_HORIZONTAL);
        $menu->setIsTab(true);
        $menu->setMenuAccessList();

        /** @var ContainerFactoryMenuItem $menuItemOverview */
        $menuItemOverview = Container::
get('ContainerFactoryMenuItem');
        $menuItemOverview->setAccess('');
        $menuItemOverview->setLink('index.php?application=ApplicationAdministrationUser');
        $menuItemOverview->setPath('/');
        $menuItemOverview->setTitle('1|' . ContainerFactoryLanguage::get('/ApplicationAdministrationUser/meta/title'));

        $menu->addMenuItem($menuItemOverview);

        /** @var ContainerFactoryMenuItem $menuItemEdit */
        $menuItemEdit = Container::get('ContainerFactoryMenuItem');
        $menuItemEdit->setAccess('');
        $menuItemEdit->setLink('index.php?application=ApplicationAdministrationUserEdit');
        $menuItemEdit->setPath('/');
        $menuItemEdit->setTitle('2|' .sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationUserEdit/meta/title'),''));

        $menu->addMenuItem($menuItemEdit);

        ContainerExtensionTemplateParseInsertPositions::insert('/page/box/main/header',
                                                               $menu->createMenu('/',
                                                                                 sprintf(ContainerFactoryLanguage::get('/' . $class . '/meta/title'),'')));

    }
}
