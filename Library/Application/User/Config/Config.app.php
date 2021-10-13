<?php declare(strict_types=1);

/**
 * Config Administration
 *
 * Config Administration
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 2,3,4
 * @modul    language_path_de_DE /Benutzer
 * @modul    language_name_de_DE Konfiguration
 * @modul    language_path_en_US /User
 * @modul    language_name_en_US Config
 */
class ApplicationUserConfig_app extends Application_abstract
{

    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');

        /** @var ContainerFactoryUserConfig_crud $crud */
        $crud       = Container::get('ContainerFactoryUserConfig_crud');
        $crudConfig = $crud->find();

        /** @var ContainerFactoryMenu $configMenu */
        $configMenu = Container::get('ContainerFactoryMenu');

        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');
        $configMenu->setMenuAccessList($user->getUserAccess());

        /** @var ContainerFactoryUserConfig_crud $crudConfigItem */
        foreach ($crudConfig as $crudConfigItem) {

            $path = ContainerFactoryModul::getModulMenuLanguage($crudConfigItem->getCrudClass(),
                                                                $crudConfigItem->getCrudClass());

            $jsonDecode        = $crudConfigItem->getCrudConfigLanguage();
            $languageContainer = json_decode((($jsonDecode !== null) ? $jsonDecode : '{}'),
                true);
            if (!empty($languageContainer)) {
                $name = ContainerFactoryLanguage::getLanguageText($languageContainer);
            }
            else {
                $name = $crudConfigItem->getCrudConfigKey();
            }

            /** @var ContainerFactoryMenuItem $menuItem */
            $menuItem = Container::get('ContainerFactoryMenuItem');
            $menuItem->setPath($path);
            $menuItem->setDescription('');
            $menuItem->setTitle($name);
            $menuItem->setLink('index.php?application=ApplicationUserConfig&id=' . $crudConfigItem->getCrudId());
            $menuItem->setAccess('ApplicationAdministrationConfig');

            $configMenu->addMenuItem($menuItem);
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['default']);

        /** @var ContainerFactoryRequest $requestConfig */
        $requestConfig = Container::get('ContainerFactoryRequest',
                                        ContainerFactoryRequest::REQUEST_GET,
                                        'id');

        if ($requestConfig->exists()) {

            /** @var ContainerFactoryUserConfig_crud $crud */
            $crud = Container::get('ContainerFactoryUserConfig_crud');
            $crud->setCrudId((int)$requestConfig->get());
            $crud->findById(true);

            $path = ContainerFactoryModul::getModulMenuLanguage($crud->getCrudClass(),
                                                                $crud->getCrudClass());

            $jsonDecode        = $crud->getCrudConfigLanguage();
            $languageContainer = json_decode((($jsonDecode !== null) ? $jsonDecode : '{}'),
                true);

            if (!empty($languageContainer)) {
                $name = ContainerFactoryLanguage::getLanguageText($languageContainer);
            }
            else {
                $name = $crud->getCrudConfigKey();
            }

            $template->assign('menu',
                              $configMenu->createMenu($path,
                                                      $name));

//            eol();

            $template->assign('formKey',
                              '/' . $crud->getCrudClass() . $crud->getCrudConfigKey());
            $template->assign('formValue',
                              $this->formConfig($crud));
        }
        else {
            $template->assign('formKey',
                              '');
            $template->assign('formValue',
                              '');
            $template->assign('menu',
                              $configMenu->createMenu());
        }

        $template->parse();
        return $template->get();

    }

    /**
     *
     */
    private function formConfig(ContainerFactoryUserConfig_crud $crudConfig): string
    {
        /** @var ContainerFactoryUserConfig_crud $crudConfig */

        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     'ApplicationAdministrationConfig',
                                     'config');

        /** @var ContainerExtensionTemplateParseCreateFormResponse $formHelperResponse */
        $formHelperResponse = $formHelper->getResponse();

        $jsonDecode = $crudConfig->getCrudConfigForm();
        $configForm = json_decode((($jsonDecode !== null) ? $jsonDecode : '{}'),
            true);

        $formType = ($configForm['type'] ?? '');

        if (
            $formHelperResponse->isHasResponse() && !$formHelperResponse->hasError()
        ) {
            $this->formResponse($formHelper,
                                $crudConfig,
                                $formType);
        }

        $jsonDecode        = $crudConfig->getCrudConfigLanguage();
        $languageContainer = json_decode((($jsonDecode !== null) ? $jsonDecode : '{}'),
            true);

        if ($formType !== 'switch') {

            $formHelper->addFormElement('language',
                                        'Plain',
                                        [],
                                        [
                                            [
                                                'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                                ContainerFactoryLanguage::getLanguageText(($languageContainer ?? []))
                                            ],
                                        ]);
        }

        $formHelper->addFormElement('id',
                                    'Hidden',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crudConfig->getCrudId()
                                        ],
                                    ]);

        if ($formType === 'switch') {

            $formHelper->addFormElement('value',
                                        'checkbox',
                                        [
                                            [
                                                1 => ContainerFactoryLanguage::getLanguageText(($languageContainer ?? []))
                                            ],
                                        ],
                                        [
                                            [
                                                'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                                ContainerFactoryUserConfig::get($crudConfig->getCrudIdent())
                                            ],
                                        ]);
        }

        else {
            $formHelper->addFormElement('value',
                                        'Textarea',
                                        [],
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                            [
                                                'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                                ContainerFactoryUserConfig::get($crudConfig->getCrudIdent())
                                            ],
                                        ]);
        }

        $elementObj = $formHelper->getElement('value');
        $elementObj->setFlex(2);

        $formHelper->addFormElement('valueDefault',
                                    'Plain',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyReadonly',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            htmlentities((is_string($crudConfig->getCrudConfigValueDefault()) ? $crudConfig->getCrudConfigValueDefault() : ''))
                                        ],
                                    ]);

        return $formHelper->getHeader() . $formHelper->getElements(true) . $formHelper->getFooter();
    }

    protected final function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper, ContainerFactoryUserConfig_crud $crudConfig, string $formType): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = $formHelper->getResponse();
        $value    = $response->get('value');

        if ($formType === 'switch') {
            if (is_array($value)) {
                $value = reset($value);
            }
        }

        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');

        /** @var ContainerFactoryUserConfig_crud_user $crudConfigUser */
        $crudConfigUser = Container::get('ContainerFactoryUserConfig_crud_user');
        $crudConfigUser->setCrudIdent($crudConfig->getCrudIdent());
        $crudConfigUser->setCrudUserId($user->getUserId());
        $crudConfigUser->findByColumn([
                                          'crudIdent',
                                          'crudUserId',
                                      ]);

        $crudConfigUser->setCrudConfigValue((string)$value);
        $crudConfigUser->insertUpdate();

        /** @var ContainerFactoryLog_crud_notification $crud */
        $crud = Container::get('ContainerFactoryLog_crud_notification');
        $crud->setCrudMessage(sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationConfig/notification/updated'),
                                      '/' . $crudConfig->getCrudClass() . $crudConfig->getCrudConfigKey()));
        $crud->setCrudClass(__CLASS__);
        $crud->setCrudCssClass('simpleModifySuccess');
        $crud->setCrudClassIdent(__CLASS__);
        $crud->setCrudData('');
        $crud->setCrudType($crud::NOTIFICATION_REQUEST);

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $nid  = $page->addNotification($crud);

        /** @var ContainerFactoryRouter $router */
        $router = clone Container::getInstance('ContainerFactoryRouter');
        $router->setQuery('_notification',
                          $nid);
        $router->setQuery('_form',
                          null);
        $router->redirect();
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
                                       'index.php?application=ApplicationAdministrationConfig');

        $menu = $this->getMenu();
        $menu->setMenuClassMain($thisClassName);

    }
}
