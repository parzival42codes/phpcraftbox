<?php declare(strict_types=1);

/**
 * Config Administration
 *
 * Config Administration
 *
 * @author  Stefan Schlombs
 * @version 1.0.0
 * @modul   versionRequiredSystem 1.0.0
 * @modul   hasCSS
 */
class ApplicationAdministrationConfig_app extends Application_abstract
{

    public function setContent(): string
    {
        $this->pageData();

        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'default');

        /** @var Config_crud $crud */
        $crud       = Container::get('Config_crud');
        $crudConfig = $crud->find();

        /** @var ContainerFactoryMenu $configMenu */
        $configMenu = Container::get('ContainerFactoryMenu');

        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');
        $configMenu->setMenuAccessList($user->getUserAccess());

//        d($crudConfig);
//        eol();

        $classes = [];
        /** @var Config_crud $crudConfigItem */
        foreach ($crudConfig as $crudConfigItem) {
            $classes[] = $crudConfigItem->getCrudClass();
        }

        $classes = array_unique($classes);

        foreach ($classes as $class) {
            $path = ContainerFactoryModul::getModulMenuLanguage($class,
                                                                $class);

            /** @var ContainerFactoryMenuItem $menuItem */
            $menuItem = Container::get('ContainerFactoryMenuItem');
            $menuItem->setPath('/');
            $menuItem->setDescription('');
            $menuItem->setTitle($path);
            $menuItem->setLink('index.php?application=ApplicationAdministrationConfig&id=' . $class);
            $menuItem->setAccess('ApplicationAdministrationConfig');

            $configMenu->addMenuItem($menuItem);
        }

        $template = new ContainerExtensionTemplate();
        $template->set($templateCache->get()['default']);

        $requestConfig = new ContainerFactoryRequest(ContainerFactoryRequest::REQUEST_GET,
                                                     'id');

        if ($requestConfig->exists()) {

            $class = Core::getRootClass($requestConfig->get());

            $formHelper = new ContainerExtensionTemplateParseCreateForm_helper('ApplicationAdministrationConfig',
                                                                               'config');

            $formHelperResponse = $formHelper->getResponse();

            $crudConfig = new Config_crud();
            $configFind = $crudConfig->find([
                                                'crudClass' => $class
                                            ]);

            $content = '';

            /** @var Config_crud $configFindItem */
            foreach ($configFind as $configFindItem) {

                $labelSource = $configFindItem->getCrudConfigLanguage();
                if ($labelSource) {
                    $label = ContainerFactoryLanguage::getLanguageText($labelSource);
                }
                else {
                    $label = $labelSource;
                }

                ContainerFactoryLanguage::set('/' . $class . '/form' . $configFindItem->getCrudConfigKey() . '/label',
                                              $label);
                ContainerFactoryLanguage::set('/' . $class . '/form' . $configFindItem->getCrudConfigKey() . '/info',
                                              sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationConfig/info/text') , $configFindItem->getCrudConfigValueDefault()));

                $content .= $this->formConfig($configFindItem,
                                              $formHelper,
                                              $formHelperResponse);
            }

            $template->assign('content',
                              $formHelper->getHeader() . $content . $formHelper->getFooter());

            $path = ContainerFactoryModul::getModulMenuLanguage($class,
                                                                $class);

            $template->assign('menu',
                              $configMenu->createMenu('/',
                                                      $path));

        }
        else {
            $template->assign('content',
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
    private function formConfig(Config_crud $crudConfig, ContainerExtensionTemplateParseCreateForm_helper $formHelper, ContainerExtensionTemplateParseCreateFormResponse $formHelperResponse): string
    {
        $jsonDecode = $crudConfig->getCrudConfigForm();
        $configForm = json_decode((($jsonDecode !== null) ? $jsonDecode : '{}'),
            true);

        $configForm['_id']    = substr($crudConfig->getCrudConfigKey(),
                                       1);
        $configForm['_class'] = $crudConfig->getCrudClass();
        $configForm['modify'] = [
            [
                'ContainerExtensionTemplateParseCreateFormModifyDefault',
                $crudConfig->getCrudConfigValue()
            ]
        ];

        if (($configForm['modify'] ?? false) === true) {
            $configForm['modify'][] = 'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired';
        }


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

        if ($formType === 'switch') {
            $this->formTypeSwitch($formHelper,
                                  $crudConfig,
                                  $configForm,
                                  $languageContainer);
        }
        elseif ($formType === 'select') {
            $this->formTypeSelect($formHelper,
                                  $crudConfig,
                                  $configForm);
        }
        elseif ($formType === 'number') {
            $this->formTypeNumber($formHelper,
                                  $crudConfig,
                                  $configForm);
        }
        else {
            $this->formType($formHelper,
                            $crudConfig,
                            $configForm);
        }

        $elementObj = $formHelper->getElement($configForm['_id']);
        $elementObj->setFlex(2);

        return $formHelper->getElements(true);
    }

    protected final function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper, Config_crud $crudConfig, string $formType): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = $formHelper->getResponse();
        $value    = $response->get('value');

        if ($formType === 'switch') {
            if (is_array($value)) {
                $value = reset($value);
            }
        }

        $crudConfig->setCrudConfigValue($value);
        $crudConfig->update();

        /** @var ContainerFactoryLog_crud_notification $crud */
        $crud = Container::get('ContainerFactoryLog_crud_notification');
        $crud->setCrudMessage(sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationConfig/notification/updated'),
                                      '/' . $crudConfig->getCrudClass() . $crudConfig->getCrudConfigKey()));
        $crud->setCrudClass(__CLASS__);
        $crud->setCrudCssClass('simpleModifySuccess');
        $crud->setCrudClassIdent(__CLASS__);
        $crud->setCrudData('');

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
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

        /** @var ContainerFactoryMenu $menu */
        $menu = $this->getMenu();
        $menu->setMenuClassMain($thisClassName);

    }

    protected function formTypeSwitch(ContainerExtensionTemplateParseCreateForm_helper $formHelper, Config_crud $crudConfig, array $configForm, array $languageContainer)
    {


        $formHelper->addFormElement($configForm['_id'],
                                    'checkbox',
                                    [
                                        [
                                            1 => ContainerFactoryLanguage::getLanguageText(($languageContainer ?? []))
                                        ],
                                    ],
                                    [

                                    ],
                                    $configForm['_class']);
    }

    protected function formTypeSelect(ContainerExtensionTemplateParseCreateForm_helper $formHelper, Config_crud $crudConfig, array $configForm)
    {

        $options = [];
        if (isset($configForm['options'])) {
            foreach ($configForm['options'] as $optionKey => $optionValue) {
                $options[$optionKey] = ContainerFactoryLanguage::getLanguageText($optionValue);
            }
        }

        $formHelper->addFormElement($configForm['_id'],
                                    'select',
                                    [
                                        $options
                                    ],
                                    [
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crudConfig->getCrudConfigValue()
                                        ],
                                    ],
                                    $configForm['_class']);
    }

    protected function formTypeNumber(ContainerExtensionTemplateParseCreateForm_helper $formHelper, Config_crud $crudConfig, array $configForm)
    {

        $options = [];
        if (isset($configForm['options'])) {
            foreach ($configForm['options'] as $optionKey => $optionValue) {
                $options[$optionKey] = ContainerFactoryLanguage::getLanguageText($optionValue);
            }
        }

        $formHelper->addFormElement($configForm['_id'],
                                    'number',
                                    [],
                                    [
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crudConfig->getCrudConfigValue()
                                        ],
                                    ],
                                    $configForm['_class']);
    }

    protected function formType(ContainerExtensionTemplateParseCreateForm_helper $formHelper, Config_crud $crudConfig, array $configForm)
    {
        $formHelper->addFormElement($configForm['_id'],
                                    'Textarea',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crudConfig->getCrudConfigValue()
                                        ],
                                    ],
                                    $configForm['_class']);
    }
}
