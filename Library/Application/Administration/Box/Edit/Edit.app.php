<?php declare(strict_types=1);

class ApplicationAdministrationBoxEdit_app extends ApplicationAdministration_abstract
{

    protected $id            = '';
    protected array  $boxItems      = [];
    protected int    $boxItemsCount = 0;

    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     'ApplicationAdministrationBoxEdit',
                                     'edit');

        /** @var ContainerFactoryRouter $router */
        $router   = Container::getInstance('ContainerFactoryRouter');
        $this->id = $router->getParameter('id');

        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'default,row,item,item.form,item.dialog,widgets');
        $templateCacheContent = $templateCache->getCacheContent();

        /** @var ContainerExtensionTemplateParseCreateFormRequest $request */
        $request = $formHelper->getRequest();

        /** @var ContainerIndexPageBox_crud $crud */
        $crud = Container::get('ContainerIndexPageBox_crud');

        if ($router->getQuery('action') === 'new') {
            $this->addBoxItem($crud,
                              $templateCacheContent,
                              $request,
                              $formHelper);
        }


        $crudCollect = $crud->find([
                                       'crudAssignment' => $this->id,
                                   ]);

        /** @var ContainerIndexPageBox_crud $crudCollectItem */
        foreach ($crudCollect as $crudCollectItem) {
            $this->addBoxItem($crudCollectItem,
                              $templateCacheContent,
                              $request,
                              $formHelper);
        }

        $boxItemsContent = '';
        foreach ($this->boxItems as $boxRow) {
            $boxItemsRow = '';
            foreach ($boxRow as $boxItems) {
                /** @var ContainerExtensionTemplate $template */
                $template = Container::get('ContainerExtensionTemplate');
                $template->set($templateCacheContent['item']);
                $template->assign('item',
                                  $boxItems);
                $template->parse();

                $boxItemsRow .= $template->get();
            }

            /** @var ContainerExtensionTemplate $templateRow */
            $templateRow = Container::get('ContainerExtensionTemplate');
            $templateRow->set($templateCacheContent['row']);
            $templateRow->assign('container',
                                 $boxItemsRow);
            $templateRow->parse();

            $boxItemsContent .= $templateRow->get();
        }

        $request->create();

        $this->formResponse($formHelper->getResponse());

        /** @var ContainerExtensionDocumentation_crud $crudDocumentationWidgets */
        $crudDocumentationWidgets = Container::get('ContainerExtensionDocumentation_crud');
        $documentationWidgets     = $crudDocumentationWidgets->find([
                                                                        'crudType' => ContainerExtensionDocumentation::DOCTYPE_WIDGET
                                                                    ]);

        $replace = [
            '{' => '&#123;',
            '}' => '&#125;',
        ];

        $tableTcs = [];
        /** @var ContainerExtensionDocumentation_crud $documentationWidgetsItem */
        foreach ($documentationWidgets as $documentationWidgetsItem) {

            $tableTcs[] = [
                'content' => $documentationWidgetsItem->getCrudContent(),
            ];
        }


        /** @var ContainerExtensionTemplate $templateWidgets */
        $templateWidgets = Container::get('ContainerExtensionTemplate');
        $templateWidgets->set($templateCacheContent['widgets']);

        $templateWidgets->assign('Widgets_Widgets',
                                 $tableTcs);

        $templateWidgets->parse();
        $templateWidgets->catchDataClear();

        /** @var ContainerExtensionTemplateParseHelperDialog $templateWidgetDialog */
        $templateWidgetDialog = Container::get('ContainerExtensionTemplateParseHelperDialog');
        $templateWidgetDialog->setHeader(ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/dialog/widgets/title'));
        $templateWidgetDialog->setBody($templateWidgets->get());
        $templateWidgetDialog->setFooter();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['default']);

        $template->assign('formHeader',
                          $formHelper->getHeader());
        $template->assign('formFooter',
                          $formHelper->getFooter());
        $template->assign('content',
                          $boxItemsContent);
        $template->assign('id',
                          $this->id);

        $template->assign('widgets',
                          $templateWidgetDialog->create(ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/button/widgets')));

        $template->parseQuote();
        $template->parse();

        return $template->get();

    }

    protected function pageData(): void
    {

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationAdministrationBox/breadcrumb'));

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=ApplicationAdministrationBox');



        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=administration');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationBox/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationBox');

        $menu = $this->getMenu();
        $menu->setMenuClassMain('ApplicationAdministrationBox');
    }

    public function addBoxItem(ContainerIndexPageBox_crud $crud, array &$templateCacheContent, ContainerExtensionTemplateParseCreateFormRequest $request, ContainerExtensionTemplateParseCreateForm_helper $formHelper): void
    {
        $this->boxItemsCount++;

        ContainerFactoryLanguage::set('/ApplicationAdministrationBoxEdit/form/crudContent' . $this->boxItemsCount . '/label',
                                      ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/crudContent/label'));

        $formHelper->addFormElement('crudContent' . $this->boxItemsCount,
                                    'Textarea',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudContent()
                                        ],
                                    ]);

        /** @var ContainerExtensionTemplate $templateDialog */
        $templateDialog = Container::get('ContainerExtensionTemplate');
        $templateDialog->set($templateCacheContent['item.dialog']);
        $templateDialog->assign('content',
                                $formHelper->getElements());
        $templateDialog->parseString();

        /** @var ContainerExtensionTemplateParseHelperDialog $templateDialogData */
        $templateDialogData = Container::get('ContainerExtensionTemplateParseHelperDialog');
        $templateDialogData->setHeader(ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/crudContent/label'));
        $templateDialogData->setBody($templateDialog->get());
        $templateDialogData->setFooter();

        ContainerFactoryLanguage::set('/ApplicationAdministrationBoxEdit/form/crudRow' . $this->boxItemsCount . '/label',
                                      ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/crudRow/label'));

        $formHelper->addFormElement('crudRow' . $this->boxItemsCount,
                                    'Number',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudRow()
                                        ],
                                    ]);

        ContainerFactoryLanguage::set('/ApplicationAdministrationBoxEdit/form/crudPosition' . $this->boxItemsCount . '/label',
                                      ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/crudPosition/label'));

        $formHelper->addFormElement('crudPosition' . $this->boxItemsCount,
                                    'Number',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudPosition()
                                        ],
                                    ]);

        ContainerFactoryLanguage::set('/ApplicationAdministrationBoxEdit/form/crudFlex' . $this->boxItemsCount . '/label',
                                      ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/crudFlex/label'));

        $formHelper->addFormElement('crudFlex' . $this->boxItemsCount,
                                    'Number',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudFlex()
                                        ],
                                    ]);

        ContainerFactoryLanguage::set('/ApplicationAdministrationBoxEdit/form/crudDescription' . $this->boxItemsCount . '/label',
                                      ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/crudDescription/label'));


        $formHelper->addFormElement('crudDescription' . $this->boxItemsCount,
                                    'text',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $crud->getCrudDescription()
                                        ],
                                    ]);

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['item.form']);

        ContainerFactoryLanguage::set('/ApplicationAdministrationBoxEdit/form/active' . $this->boxItemsCount . '/label',
                                      ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/active/label'));


        $isActive = $crud->getCrudActive();
        if ($isActive) {
            $isActive = 'active';
        }
        else {
            $isActive = '';
        }

        $formHelper->addFormElement('active' . $this->boxItemsCount,
                                    'Checkbox',
                                    [
                                        [
                                            'active' => ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/checkbox/active'),
                                        ]
                                    ],
                                    [
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $isActive
                                        ],
                                    ]);

        $formHelper->addFormElement('delete' . $this->boxItemsCount,
                                    'Checkbox',
                                    [
                                        [
                                            'delete' => ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/checkbox/delete'),
                                        ]
                                    ],
                                    [
                                        [
                                            __CLASS__,
                                            'javascriptDeleteCheckbox'
                                        ]
                                    ]);

        $template->assign('content',
                          $formHelper->getElements());
        $template->assign('dialog',
                          $templateDialogData->create(ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/button/edit')));
        $template->parse();

        /** @var ContainerExtensionTemplateParseHelperDialog $templateDialogData */
        $templateDialogData = Container::get('ContainerExtensionTemplateParseHelperDialog',
                                             'askDeleteDialogdelete' . $this->boxItemsCount);
        $templateDialogData->setHeader(ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/checkbox/delete/dialog/title'));
        $templateDialogData->setHeaderCLass('simpleModifyError');
        $templateDialogData->setBody('
        <span style="display: inline-block;width: 100%;box-sizing: border-box;text-align: center;padding: 1em;">
        <span class="btn simpleModifyError dialogDeleteBtn" data-dialogsource="" id="dialogDeleteBtn' . $this->boxItemsCount . '">' . ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/checkbox/delete/dialog/content') . '</span>
        </span>
        ');
        $templateDialogData->setFooter();
        $templateDialogData->create();

        $this->boxItems[$crud->getCrudRow()][] = $template->get();
    }

    protected function formResponse(ContainerExtensionTemplateParseCreateFormResponse $response): void
    {
        if ($response->isHasResponse()) {

            /** @var ContainerIndexPageBox_crud $crud */
            $crud = Container::get('ContainerIndexPageBox_crud');
            $crud->deleteFrom([
                                  'crudAssignment' => $this->id
                              ]);

            $countItems = 0;
            foreach ($this->boxItems as $itemRow) {
                foreach ($itemRow as $item) {
                    $countItems++;
                }
            }

            for ($i = 1; $i <= $countItems; $i++) {
                if ($response->get('delete' . $i) !== null) {
                    continue;
                }

                $crud->setCrudId(null);
                $crud->setCrudRow((int)$response->get('crudRow' . $i));
                $crud->setCrudPosition((int)$response->get('crudPosition' . $i));
                $crud->setCrudFlex((int)$response->get('crudFlex' . $i));
                $crud->setCrudDescription($response->get('crudDescription' . $i));
                $crud->setCrudContent($response->get('crudContent' . $i));
                $crud->setCrudAssignment($this->id);

                if ($response->get('active' . $i)[0] === 'active') {
                    $crud->setCrudActive(true);
                }
                else {
                    $crud->setCrudActive(false);
                }

                $crud->insert();
            }

            /** @var ContainerFactoryLog_crud_notification $crud */
            $crud = Container::get('ContainerFactoryLog_crud_notification');
            $crud->setCrudMessage(sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/notification/saved'),
                                          $this->id));
            $crud->setCrudClass(__CLASS__);
            $crud->setCrudCssClass('simpleModifySuccess');
            $crud->setCrudClassIdent($this->id);
            $crud->setCrudData();
            $crud->setCrudType($crud::NOTIFICATION_LOG);

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
    }

    public static function javascriptDeleteCheckbox($object, $attribut): void
    {

        if ($object instanceof ContainerExtensionTemplateParseCreateFormElementCheckbox && $attribut instanceof ContainerIndexHtmlAttribute) {

            $attribut->set('class',
                           'askDeleteDialog');
            $attribut->set('data-dialogid',
                           null,
                           'askDeleteDialog' . $attribut->get('name',
                                                              'name'));

        }
    }

}
