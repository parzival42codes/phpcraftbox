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
class ApplicationAdministrationLanguage_app extends Application_abstract
{

    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');

        /** @var ContainerFactoryLanguage_crud $crud */
        $crud          = Container::get('ContainerFactoryLanguage_crud');
        $crudCLanguage = $crud->find();

        $collectClasses = [];

        /** @var ContainerFactoryLanguage_crud $crudLanguageItem */
        foreach ($crudCLanguage as $crudLanguageItem) {
            $collectClasses[ContainerFactoryModul::getModulMenuLanguage($crudLanguageItem->getCrudClass(),
                                                                        $crudLanguageItem->getCrudClass())]
            []
                = $crudLanguageItem;
        }

        /** @var ContainerFactoryMenu $configMenu */
        $configMenu = Container::get('ContainerFactoryMenu');

        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');
        $configMenu->setMenuAccessList($user->getUserAccess());

        $menuPath = array_keys($collectClasses);
        /** @var ContainerFactoryLanguage_crud $crudLanguageItem */
        foreach ($menuPath as $menuPathItem) {
            $menuPathItemData = explode('/',
                                        $menuPathItem);

            $menuPathItemTitle = array_pop($menuPathItemData);
            $path              = '/' . implode('/',
                                               $menuPathItemData);

            /** @var ContainerFactoryMenuItem $menuItem */
            $menuItem = Container::get('ContainerFactoryMenuItem');
            $menuItem->setPath($path);
            $menuItem->setTitle($menuPathItemTitle);
            $menuItem->setLink('index.php?application=ApplicationAdministrationLanguage&id=' . base64_encode($menuPathItem));
            $menuItem->setAccess('ApplicationAdministrationConfig');

            $configMenu->addMenuItem($menuItem);
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        /** @var ContainerFactoryRequest $requestLanguage */
        $requestLanguage = Container::get('ContainerFactoryRequest',
                                          ContainerFactoryRequest::REQUEST_GET,
                                          'id');

        if ($requestLanguage->exists() && !empty($requestLanguage->get())) {

            $requestLanguagePath = base64_decode($requestLanguage->get());

            $menuPathItemData = explode('/',
                                        $requestLanguagePath);

            $menuPathItemTitle = array_pop($menuPathItemData);
            $path              = implode('/',
                                         $menuPathItemData);

            $template->assign('menu',
                              $configMenu->createMenu('/' . $path,
                                                      $menuPathItemTitle));

            $template->assign('formKey',
                              $requestLanguagePath);
            $template->assign('formValue',
                              $this->formConfig($collectClasses[$requestLanguagePath]));
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
    private function formConfig(array $crudLanguage): string
    {
        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     'ApplicationAdministrationLanguage',
                                     'language');
//
//        d($crudLanguage);
//        eol();


        /** @var ContainerExtensionTemplateParseCreateFormResponse $formHelperResponse */
        $formHelperResponse = $formHelper->getResponse();
        if (
            $formHelperResponse->isHasResponse() && !$formHelperResponse->hasError()
        ) {
            $this->formResponse($formHelper);
        }


        $languageEntries = '';

        /** @var ContainerFactoryLanguage_crud $crudLanguageItem */
        foreach ($crudLanguage as $crudLanguageKey => $crudLanguageItem) {

            $contentValue = strtr($crudLanguageItem->getCrudLanguageValue(),
                                  [
                                      '{' => '&#123;',
                                      '}' => '&#125;',
                                  ]);

            $contentValueDefault = strtr($crudLanguageItem->getCrudLanguageValueDefault(),
                                         [
                                             '{' => '&#123;',
                                             '}' => '&#125;',
                                         ]);
            $contentValueDefault = htmlentities($contentValueDefault);

            $formHelper->addFormElement('plain1' . $crudLanguageKey,
                                        'Plain',
                                        [],
                                        [
                                            [
                                                'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                                $crudLanguageItem->getCrudLanguageKey() . ' :: ' . $crudLanguageItem->getCrudLanguageLanguage()
                                            ],
                                        ]);

            $formHelper->addFormElement('Value' . $crudLanguageItem->getCrudId(),
                                        'Textarea',
                                        [],
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                            [
                                                'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                                $contentValue
                                            ],
                                        ]);

            $elementObj = $formHelper->getElement('Value' . $crudLanguageItem->getCrudId());
            $elementObj->setFlex(2);

            $formHelper->addFormElement('plainValueDefault' . $crudLanguageKey,
                                        'Plain',
                                        [],
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyReadonly',
                                            [
                                                'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                                $contentValueDefault
                                            ],
                                        ]);

            $languageEntries .= $formHelper->getElements(true);
        }

        return $formHelper->getHeader() . $languageEntries . $formHelper->getFooter();

    }

    protected final function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = $formHelper->getResponse();

        $languageAll = $response->getAll();

        foreach ($languageAll as $itemValue => $item) {
            if (
            str_contains($itemValue,
                         'Value')
            ) {
                $id = substr($itemValue,
                             5);

                /** @var ContainerFactoryLanguage_crud $crudLanguage */
                $crudLanguage = Container::get('ContainerFactoryLanguage_crud');
                $crudLanguage->setCrudId((int)$id);
                $crudLanguage->findById(true);
                $crudLanguage->setCrudLanguageValue($item);
                $crudLanguage->update();
            }
        }

        /** @var ContainerFactoryLog_crud_notification $crud */
        $crud = Container::get('ContainerFactoryLog_crud_notification');
        $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationAdministrationConfig/notification/updated'));
        $crud->setCrudClass(__CLASS__);
        $crud->setCrudCssClass('simpleModifySuccess');
        $crud->setCrudClassIdent(__CLASS__);
        $crud->setCrudData('');

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->addNotification($crud);
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
}
