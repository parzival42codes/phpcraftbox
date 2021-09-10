<?php declare(strict_types=1);

/**
 * Admin User Edit
 *
 * Admin User Edit
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 4
 * @modul    language_name_de_DE Benutzer bearbeiten
 * @modul    language_name_en_US User edit
 * @modul    language_path_de_DE /Administration/Benutzer
 * @modul    language_path_en_US /Administration/User
 */
class ApplicationAdministrationUserEdit_app extends Application_abstract
{

    public function setContent(): string
    {
        /** @var ContainerFactoryRouter $route */
        $route = Container::getInstance('ContainerFactoryRouter');

        /** @var ContainerFactoryUser_crud $crud */
        $crud = Container::get('ContainerFactoryUser_crud');
        if (!empty($route->getParameter('id'))) {
            $crud->setCrudId((int)$route->getParameter('id'));
            $crud->findById(true);
        }

        $this->pageData($crud);

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        $this->___getRootClass(),
                                        'default');

        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     $this->___getRootClass(),
                                     'register');

        /** @var ContainerExtensionTemplateParseCreateFormResponse $formHelperResponse */
        $formHelperResponse = $formHelper->getResponse();
        if (
            $formHelperResponse->isHasResponse()
        ) {
            $this->formResponse($formHelper,
                                $crud);
        }


        $registerDate = new DateTime($crud->getDataVariableCreated());


        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        $formHelper->addFormElement('username',
                                    'text',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudUsername()
                                        ]
                                    ]);

        $formHelper->addFormElement('email',
                                    'email',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorEmail',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudEmail()
                                        ]
                                    ]);


        /** @var ContainerFactoryUserGroup_crud $cudUserGroup */
        $cudUserGroup       = Container::get('ContainerFactoryUserGroup_crud');
        $cudUserGroupResult = $cudUserGroup->find();

        $cudUserGroupCollect = [];
        /** @var ContainerFactoryUserGroup_crud $cudUserGroupResultItem */
        foreach ($cudUserGroupResult as $cudUserGroupResultItem) {
            $cudUserGroupCollect[$cudUserGroupResultItem->getCrudId()] = $cudUserGroupResultItem->getCrudLanguage() . ')';
        };

        $formHelper->addFormElement('usergroup',
                                    'select',
                                    [$cudUserGroupCollect],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudUserGroupId()
                                        ]
                                    ]);

        $template->assign('register',
                          $formHelper->getElements(true));

        $formHelper->addFormElement('password',
                                    'password',
                                    [],
                                    [
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyValidatorPassword',
                                            [
//                                                'length' => 8,
//                                                'uppercase' => true,
//                                                'lowercase' => true,
//                                                'spezial'   => true,
//                                                'number'    => true,
                                            ]
                                        ],
                                    ]);

        /** @var ContainerExtensionTemplateParseCreateFormElement_abstract $passwordElement */
        $passwordElement = $formHelper->getElement('password');

        $formHelper->addFormElement('passwordVerify',
                                    'password',
                                    [],
                                    [
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyValidatorEqual',
                                            [
                                                'password',
                                                $passwordElement->getLabel(),
                                            ]
                                        ]
                                    ]);

        $template->assign('registerPassword',
                          $formHelper->getElements(true));

        $checkboxArray = [];

        if ($crud->isCrudActivated()) {
            $checkboxArray[] = 'crudActivated';
        }
        if ($crud->isCrudEmailCheck()) {
            $checkboxArray[] = 'crudEmailCheck';
        }

        $formHelper->addFormElement('activate',
                                    'checkbox',
                                    [
                                        [
                                            'crudActivated'  => ContainerFactoryLanguage::get('/ApplicationAdministrationUserEdit/form/checkbox/crudActivated',
                                                                                              [
                                                                                                  'de_DE' => 'Benutzer aktiviert ?',
                                                                                                  'en_US' => 'User activated ?',
                                                                                              ]),
                                            'crudEmailCheck' => ContainerFactoryLanguage::get('/ApplicationAdministrationUserEdit/form/checkbox/crudEmailCheck',
                                                                                              [
                                                                                                  'de_DE' => 'Benutzer E-Mail überprüft ? ?',
                                                                                                  'en_US' => 'User E-;ail checked ?',
                                                                                              ])
                                        ],
                                    ],
                                    [
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $checkboxArray
                                        ],
                                    ]);

        $template->assign('registerActivate',
                          $formHelper->getElements());

        $template->assign('registerHeader',
                          $formHelper->getHeader());

        $template->assign('registerFooter',
                          $formHelper->getFooter());

        $template->assign('infoUserId',
            ($crud->getCrudId() ?? ''));

        $template->assign('infoRegisterDate',
                          $registerDate->format((string)Config::get('/environment/datetime/format')));

        ApplicationAdministrationUser_app::createMenu($this->___getRootClass());

        $template->parse();
        return $template->get();


    }

    public function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper, ContainerFactoryUser_crud $crudUser): void
    {

        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = $formHelper->getResponse();
        if (!$response->hasError()) {

            $crud = clone $crudUser;

            $crud->setCrudUsername($response->get('username'));
            $crud->setCrudEmail($response->get('email'));
            $crud->setCrudUserGroupId((int)$response->get('usergroup'));

            $activates = $response->get('activate');

            if (
                in_array('crudActivated',
                         $activates)
            ) {
                $crud->setCrudActivated(true);
            }
            else {
                $crud->setCrudActivated(false);
            }

            if (
                in_array('crudEmailCheck',
                         $activates)
            ) {
                $crud->setCrudEmailCheck(true);
            }
            else {
                $crud->setCrudEmailCheck(false);
            }

            if (!empty($response->get('password')) && !empty($response->get('passwordVerify'))) {
                if ($response->get('password') === $response->get('passwordVerify')) {
                    $crud->setCrudPassword(password_hash($response->get('password'),
                                                         PASSWORD_DEFAULT));
                }
            }

            $crud->insertUpdate();

            $differences = '';

            /** @var ContainerHelperViewDifference $difference */
            $difference  = Container::get('ContainerHelperViewDifference',
                                          $crud->getDataAsArray(),
                                          $crudUser->getDataAsArray());
            $differences .= $difference->get();

            /** @var ContainerHelperViewDifference $difference */
            $difference  = Container::get('ContainerHelperViewDifference',
                                          $crudUser->getDataAsArray(),
                                          $crud->getDataAsArray());
            $differences .= $difference->get();

            /** @var ContainerFactoryLog_crud_notification $crudNotification */
            $crudNotification = Container::get('ContainerFactoryLog_crud_notification');
            $crudNotification->setCrudMessage(sprintf(ContainerFactoryLanguage::get('/ApplicationUserEdit/notification/registered',
                                                                                    [
                                                                                        'de_DE' => 'Der User "%s" wurde bearbeitet.',
                                                                                        'en_US' => 'User "%s" edited.',
                                                                                    ]),
                                                      $crud->getCrudUsername()));
            $crudNotification->setCrudClass(__CLASS__);
            $crudNotification->setCrudCssClass('simpleModifySuccess');
            $crudNotification->setCrudClassIdent(($crud->getCrudId() ?? 0));
            $crudNotification->setCrudData($differences);
            $crudNotification->setCrudType($crudNotification::NOTIFICATION_LOG);

            /** @var ContainerIndexPage $page */
            $page = Container::getInstance('ContainerIndexPage');
            $page->addNotification($crudNotification);
        }

    }

    public function pageData(ContainerFactoryUser_crud $crudUser): void
    {
        $thisClassName = Core::getRootClass(__CLASS__);

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');

        debugDump($crudUser->getCrudUsername());

        $pageTitle = sprintf(ContainerFactoryLanguage::get('/' . $thisClassName . '/meta/title'),
                             $crudUser->getCrudUsername());

        $page->setPageTitle($pageTitle);
        $page->setPageDescription(ContainerFactoryLanguage::get('/' . $thisClassName . '/meta/description'));

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=ApplicationAdministrationUser');


        $breadcrumb = $page->getBreadcrumb();

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=ApplicationAdministration');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationUser/meta/title'),
                                       'index.php?application=ApplicationAdministrationUser');

        $breadcrumb->addBreadcrumbItem($pageTitle);

        /** @var ContainerFactoryMenu $menu */
        $menu = $this->getMenu();
        $menu->setMenuClassMain('ApplicationAdministrationUser');

    }

}
