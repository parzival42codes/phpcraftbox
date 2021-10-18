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
        $this->createPageData();

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

        $classes = [];
        /** @var Config_crud $crudConfigItem */
        foreach ($crudConfig as $crudConfigItem) {
            $classes[] = $crudConfigItem->getCrudClass();
        }

        $classes = array_unique($classes);

        foreach ($classes as $class) {
            $path = ContainerFactoryModul::getModulMenuLanguage($class,
                                                                $class);

            $pathExplode = explode('/',
                                   $path);
            $pathTitle   = array_pop($pathExplode);
            $path        = implode('/',
                                   $pathExplode);
            if ($path === '') {
                $path = '/';
            }

            /** @var ContainerFactoryMenuItem $menuItem */
            $menuItem = Container::get('ContainerFactoryMenuItem');
            $menuItem->setPath($path);
            $menuItem->setDescription('');
            $menuItem->setTitle($pathTitle);
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

            $formHelperRequest = $formHelper->getRequest();
            $formHelperRequest::setRequestData($formHelper->getFormName(),
                                               'class',
                                               $requestConfig->get());

            $formHelperResponse = $formHelper->getResponse();

            if (
                $formHelperResponse->isHasResponse() && !$formHelperResponse->hasError()
            ) {
                $this->formResponse($formHelper);
            }

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
                                              sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationConfig/info/text'),
                                                      $configFindItem->getCrudConfigValueDefault()));

                $content .= $this->formConfig($configFindItem,
                                              $formHelper,
                                              $formHelperResponse);
            }

            $template->assign('content',
                              $formHelper->getHeader() . $content . $formHelper->getFooter());

            $path = ContainerFactoryModul::getModulMenuLanguage($class,
                                                                $class);

            $pathExplode = explode('/',
                                   $path);
            $pathTitle   = array_pop($pathExplode);
            $path        = implode('/',
                                   $pathExplode);
            if ($path === '') {
                $path = '/';
            }

            $template->assign('menu',
                              $configMenu->createMenu($path,
                                                      $pathTitle));

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
        $configForm = Config::getForm($crudConfig->getCrudClass(),
                                      $crudConfig->getCrudConfigKey());

        $configForm['_id']     = substr($crudConfig->getCrudConfigKey(),
                                        1);
        $configForm['_class']  = $crudConfig->getCrudClass();
        $configForm['_modify'] = [
            [
                'ContainerExtensionTemplateParseCreateFormModifyDefault',
                $crudConfig->getCrudConfigValue()
            ]
        ];

        if (($configForm['_modify'] ?? false) === true) {
            $configForm['_modify'][] = 'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired';
        }

        $formType = ($configForm['type'] ?? '');

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

    protected final function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper): void
    {
        $response = $formHelper->getResponse();

        $responseRequestData = $response->getResponseRequestData();

        $formMetaKeys = [];
        foreach ($response->getMetaData() as $key => $value) {
            if ($key !== 'Header' && $key !== 'Footer') {
                $formMetaKeys[] = $key;
            }
        }

        $allResponse = $response->getAll();

        foreach ($formMetaKeys as $key) {
            $crudConfig = new Config_crud();
            $crudConfig->setCrudClass($responseRequestData['class']);
            $crudConfig->setCrudConfigKey('/' . $key);
            $crudConfig->findById(true);

            $crudConfigForm = json_decode($crudConfig->getCrudConfigForm(),
                                          true);

            if (isset($crudConfigForm['type'])) {
                if ($crudConfigForm['type'] === 'switch') {
                    if (isset($allResponse[$key][0]) && $allResponse[$key][0] === '1') {
                        $crudConfig->setCrudConfigValue('1');
                    }
                    else {
                        $crudConfig->setCrudConfigValue('0');
                    }
                }
                else {
                    $crudConfig->setCrudConfigValue($allResponse[$key]);
                }
            }
            else {
                $crudConfig->setCrudConfigValue($allResponse[$key]);
            }

            $crudConfig->update();
        }

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

    protected function formTypeSwitch(ContainerExtensionTemplateParseCreateForm_helper $formHelper, Config_crud $crudConfig, array $configForm, array $languageContainer)
    {

        $formHelper->addFormElement($configForm['_id'],
                                    'checkbox',
                                    [
                                        [
                                            1 => ContainerFactoryLanguage::getLanguageText(($languageContainer ?? []))
                                        ],
                                    ],
                                    $configForm['_modify'],
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
                                    $configForm['_modify'],
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
                                    $configForm['_modify'],
                                    $configForm['_class']);
    }

    protected function formType(ContainerExtensionTemplateParseCreateForm_helper $formHelper, Config_crud $crudConfig, array $configForm)
    {
        $formHelper->addFormElement($configForm['_id'],
                                    'Textarea',
                                    [],
                                    $configForm['_modify'],
                                    $configForm['_class']);
    }
}
