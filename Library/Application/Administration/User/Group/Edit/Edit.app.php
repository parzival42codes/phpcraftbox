<?php declare(strict_types=1);

class ApplicationAdministrationUserGroupEdit_app extends ApplicationAdministration_abstract
{

    protected int $id = 0;

    public function setContent(): string
    {
        /** @var ContainerFactoryRouter $router */
        $router   = Container::getInstance('ContainerFactoryRouter');
        $this->id = (int)$router->getParameter('id');

        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = Container::get('ContainerExtensionTemplateParseCreateFormResponse',
                                   'GroupEdit');

        $this->formResponse($response);
        $this->formResponseTake();
        $this->formResponseDelete();

        /** @var ContainerExtensionTemplateParseCreateFormRequest $request */
        $request = Container::get('ContainerExtensionTemplateParseCreateFormRequest',
                                  'GroupEdit');

        $this->formGroupAccess($request,
                               $response);
        $this->formGroupEdit($request,
                             $response);
        $this->formGroupTake();
        $this->formGroupDelete();

        $request->create();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'default');
        $templateCacheContent = $templateCache->getCacheContent();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['default']);

        $template->parseQuote();
        $template->parse();

        return $template->get();

    }

    protected function formGroupEdit(ContainerExtensionTemplateParseCreateFormRequest $request, ContainerExtensionTemplateParseCreateFormResponse $response): void
    {
        /** @var ContainerFactoryUserGroup_crud $crud */
        $crud = Container::get('ContainerFactoryUserGroup_crud');
        $crud->setCrudId($this->id);
        if ($this->id > 0) {
            $crud->findById(true);
        }

        $this->pageData($crud);

        /** @var ContainerFactoryLanguageParseIni $groupLanguage */
        $groupLanguage = Container::get('ContainerFactoryLanguageParseIni',
                                        $crud->getCrudData());

        /** @var ContainerExtensionTemplateParseCreateFormElementTextarea $elementGroupData */
        $elementGroupData = Container::get('ContainerExtensionTemplateParseCreateFormElementTextarea');
        $elementGroupData->setLabel(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/form/groupData/label'));
        $elementGroupData->setValue($response->get('groupData',
                                                   $groupLanguage->getIniClean()));
        $elementGroupData->setInfo(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/form/groupData/info'));
        $elementGroupData->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired');

        $request->addElement('groupData',
                             $elementGroupData);

        /** @var ContainerExtensionTemplateParseCreateFormElementCheckbox $element */
        $element = Container::get('ContainerExtensionTemplateParseCreateFormElementCheckbox',
                                  [
                                      'delete' => ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/checkbox/delete'),
                                  ]);
        $element->setLabel(ContainerFactoryLanguage::get('/ApplicationAdministrationBoxEdit/form/checkbox/delete/label'));
        $element->setValue([]);
        $request->addElement('enhanced',
                             $element);

    }

    protected function formGroupDelete(): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormRequest $requestDelete */
        $requestDelete = Container::get('ContainerExtensionTemplateParseCreateFormRequest',
                                        'GroupDelete');

        /** @var ContainerExtensionTemplateParseCreateFormElementCheckbox $element */
        $element = Container::get('ContainerExtensionTemplateParseCreateFormElementCheckbox',
                                  [
                                      'delete' => ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/form/checkbox/delete'),
                                  ]);
        $element->setLabel(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/form/checkbox/delete/label'));
        $element->setValue([]);
        $element->addModify([
                                __CLASS__,
                                'javascriptDeleteCheckbox'
                            ]);
        $element->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired');
        $requestDelete->addElement('delete',
                                   $element);

        $requestDelete->create();

        /** @var ContainerExtensionTemplateParseHelperDialog $templateDialogData */
        $templateDialogData = Container::get('ContainerExtensionTemplateParseHelperDialog',
                                             'ApplicationAdministrationUserGroupEditAskDeleteDialog');
        $templateDialogData->setHeader(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/form/checkbox/delete/dialog/title'));
        $templateDialogData->setHeaderCLass('simpleModifyError');
        $templateDialogData->setBody('<span class="btn simpleModifyError dialogDeleteBtn" data-dialogsource="" id="dialogDeleteBtn">' . ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/form/checkbox/delete/dialog/content') . '</span>');
        $templateDialogData->setFooter();
        $templateDialogData->create();

    }


    protected function formGroupTake(): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormRequest $requestTake */
        $requestTake = Container::get('ContainerExtensionTemplateParseCreateFormRequest',
                                      'GroupTake');

        /** @var ContainerFactoryUserGroup_crud $crud */
        $crud    = Container::get('ContainerFactoryUserGroup_crud');
        $crudRed = $crud->find();

        $crudArray = [
            '' => ''
        ];

        /** @var ContainerFactoryUserGroup_crud $crudRedItem */
        foreach ($crudRed as $crudRedItem) {
            if ($crudRedItem->getCrudId() === $this->id) {
                continue;
            }

            /** @var ContainerFactoryLanguageParseIni $groupLanguage */
            $groupLanguage        = Container::get('ContainerFactoryLanguageParseIni',
                                                   $crudRedItem->getCrudData());
            $groupLanguageContent = $groupLanguage->get();

            $crudArray[$crudRedItem->getCrudId()] = $groupLanguageContent['name'];
        }

        /** @var ContainerExtensionTemplateParseCreateFormElementSelect $elementGroupTake */
        $elementGroupTake = Container::get('ContainerExtensionTemplateParseCreateFormElementSelect',
                                           $crudArray);
        $elementGroupTake->setLabel(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/form/take/label'));
        $elementGroupTake->setInfo(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/form/take/info'));
        $elementGroupTake->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired');

        $requestTake->addElement('take',
                                 $elementGroupTake);

        $requestTake->create();


    }

    public function formGroupAccess(ContainerExtensionTemplateParseCreateFormRequest $request, ContainerExtensionTemplateParseCreateFormResponse $response): void
    {
        /** @var ContainerFactoryUserGroupAccess_crud $crud */
        $crud    = Container::get('ContainerFactoryUserGroupAccess_crud');
        $crudRed = $crud->find([],
                               [
                                   'crudPath ASC'
                               ]);

        $crudArray = [];

        /** @var ContainerFactoryUserGroupAccess_crud $crudRedItem */
        foreach ($crudRed as $crudRedItem) {
            $crudArray[$crudRedItem->getCrudPath()] = $crudRedItem->getCrudPath();
        }

        /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccess */
        $crudAccess     = Container::get('ContainerFactoryUserGroup_crud_groupaccess');
        $crudAccessRead = $crudAccess->find([
                                                'crudUserGroupId' => $this->id
                                            ]);

        $crudAccessArray = [];

        /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccessReadItem */
        foreach ($crudAccessRead as $crudAccessReadItem) {
            $crudAccessArray[] = $crudAccessReadItem->getCrudAccess();
        }

        /** @var ContainerExtensionTemplateParseCreateFormElementCheckbox $elementGroupAccess */
        $elementGroupAccess = Container::get('ContainerExtensionTemplateParseCreateFormElementCheckbox',
                                             $crudArray);
        $elementGroupAccess->setValue($response->get('groupAccess',
                                                     $crudAccessArray));
        $request->addElement('groupAccess',
                             $elementGroupAccess);

    }

    protected function pageData(ContainerFactoryUserGroup_crud $crud): void
    {

        /** @var ContainerFactoryLanguageParseIni $groupLanguage */
        $groupLanguage     = Container::get('ContainerFactoryLanguageParseIni',
                                            $crud->getCrudData());
        $groupLanguageItem = $groupLanguage->get();

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/breadcrumb'),
                                    $crud->getCrudId(),
                                    $groupLanguageItem['name']));

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=administration');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroup/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationUserGroup');

        $breadcrumb->addBreadcrumbItem(sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/breadcrumb'),
                                               $crud->getCrudId(),
                                               $groupLanguageItem['name']),
                                       'index.php?application=ApplicationAdministrationUserGroupEdit&route=edit&id=' . $crud->getCrudId());

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=ApplicationAdministrationBox');

        $menu = $this->getMenu();
        $menu->setMenuClassMain('ApplicationAdministrationUserGroup');
    }

    protected function formResponse(ContainerExtensionTemplateParseCreateFormResponse $response): void
    {
        if ($response->isHasResponse()) {
            $notificationData = [];

            /** @var ContainerFactoryLanguageParseIni $groupLanguage */
            $groupLanguage     = Container::get('ContainerFactoryLanguageParseIni',
                                                $response->get('groupData'));
            $groupLanguageItem = $groupLanguage->get();

            /** @var ContainerFactoryUserGroup_crud $crud */
            $crud = Container::get('ContainerFactoryUserGroup_crud');
            $crud->setCrudId($this->id);
            $crud->findById();

            $notificationData['groupDataFrom'] = $crud->getDataAsArray();

            $crud->setCrudData($groupLanguage->getIniClean());

            if ($this->id != 0) {
                $crud->update();
            }
            else {
                $crud->insert();
            }

            $notificationData['groupDataTo'] = $crud->getDataAsArray();

            $id = $crud->getCrudId();

            if ($id === null) {
                throw new DetailedException('crudIdHasNotSet');
            }

            $this->id = $id;

            /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccess */
            $crudAccess     = Container::get('ContainerFactoryUserGroup_crud_groupaccess');
            $crudAccessRead = $crudAccess->find([
                                                    'crudUserGroupId' => $this->id
                                                ]);

            $notificationData['groupAccessFrom'] = [];

            /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccessReadItem */
            foreach ($crudAccessRead as $crudAccessReadItem) {
                $notificationData['groupAccessFrom'][] = $crudAccessReadItem->getCrudAccess();
            }

            /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccess */
            $crudAccess = Container::get('ContainerFactoryUserGroup_crud_groupaccess');
            $crudAccess->deleteFrom([
                                        'crudUserGroupId' => $this->id
                                    ]);

            $groupAccess = $response->get('groupAccess');

            $notificationData['groupAccessTo'] = [];

            if (is_array($groupAccess)) {
                foreach ($groupAccess as $groupAccessItem) {
                    $crudAccess->setCrudId(null);
                    $crudAccess->setCrudUserGroupId($this->id);
                    $crudAccess->setCrudAccess($groupAccessItem);
                    $crudAccess->insert();

                    $notificationData['groupAccessTo'][] = $groupAccessItem;
                }
            }

            $differences = '';

            /** @var ContainerHelperViewDifference $difference */
            $difference  = Container::get('ContainerHelperViewDifference',
                                          $notificationData['groupDataFrom'],
                                          $notificationData['groupDataTo']);
            $differences .= $difference->get();

            /** @var ContainerHelperViewDifference $difference */
            $difference  = Container::get('ContainerHelperViewDifference',
                                          $notificationData['groupAccessFrom'],
                                          $notificationData['groupAccessTo']);
            $differences .= $difference->get();

            /** @var ContainerFactoryLog_crud_notification $crud */
            $crud = Container::get('ContainerFactoryLog_crud_notification');
            $crud->setCrudMessage(sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/notification/saved'),
                                          $groupLanguageItem['name']));
            $crud->setCrudClass(__CLASS__);
            $crud->setCrudCssClass('simpleModifySuccess');
            $crud->setCrudClassIdent($this->id);
            $crud->setCrudData($differences);
            $crud->setCrudType($crud::NOTIFICATION_LOG);

            /** @var ContainerIndexPage $page */
            $page = Container::getInstance('ContainerIndexPage');
            $page->addNotification($crud);
        }
    }

    protected function formResponseTake(): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = Container::get('ContainerExtensionTemplateParseCreateFormResponse',
                                   'GroupTake');

        if ($response->isHasResponse()) {

            if (!empty($response->get('take')) && !empty($this->id)) {
                /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccess */
                $crudAccess     = Container::get('ContainerFactoryUserGroup_crud_groupaccess');
                $crudAccessRead = $crudAccess->find([
                                                        'crudUserGroupId' => $response->get('take')
                                                    ]);

                $crudAccessArray = [];

                /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccessReadItem */
                foreach ($crudAccessRead as $crudAccessReadItem) {
                    $crudAccessArray[] = $crudAccessReadItem->getCrudAccess();
                }

                /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccess */
                $crudAccess = Container::get('ContainerFactoryUserGroup_crud_groupaccess');
                $crudAccess->deleteFrom([
                                            'crudUserGroupId' => $this->id
                                        ]);

                if (is_array($crudAccessArray)) {
                    foreach ($crudAccessArray as $groupAccessItem) {
                        $crudAccess->setCrudId(null);
                        $crudAccess->setCrudUserGroupId($this->id);
                        $crudAccess->setCrudAccess($groupAccessItem);
                        $crudAccess->insert();
                    }
                }

                /** @var ContainerFactoryUserGroup_crud $crud */
                $crud = Container::get('ContainerFactoryUserGroup_crud');
                $crud->setCrudId($this->id);
                if ($this->id > 0) {
                    $crud->findById(true);
                }

                /** @var ContainerFactoryLanguageParseIni $groupLanguage */
                $groupLanguage     = Container::get('ContainerFactoryLanguageParseIni',
                                                    $crud->getCrudData());
                $groupLanguageItem = $groupLanguage->get();

                /** @var ContainerFactoryUserGroup_crud $crudTake */
                $crudTake = Container::get('ContainerFactoryUserGroup_crud');
                $crudTake->setCrudId($response->get('take'));
                if ($this->id > 0) {
                    $crudTake->findById(true);
                }

                /** @var ContainerFactoryLanguageParseIni $groupLanguageTake */
                $groupLanguageTake     = Container::get('ContainerFactoryLanguageParseIni',
                                                        $crudTake->getCrudData());
                $groupLanguageItemTake = $groupLanguageTake->get();

                /** @var ContainerFactoryLog_crud_notification $crud */
                $crud = Container::get('ContainerFactoryLog_crud_notification');
                $crud->setCrudMessage(sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/notification/take'),
                                              $groupLanguageItemTake['name']));
                $crud->setCrudClass(__CLASS__);
                $crud->setCrudCssClass('simpleModifySuccess');
                $crud->setCrudClassIdent($this->id);
                $crud->setCrudType($crud::NOTIFICATION_LOG);

                /** @var ContainerIndexPage $page */
                $page = Container::getInstance('ContainerIndexPage');
                $page->addNotification($crud);
            }
        }
    }

    protected function formResponseDelete(): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = Container::get('ContainerExtensionTemplateParseCreateFormResponse',
                                   'GroupDelete');

        if ($response->isHasResponse()) {

            if ($this->id) {

                /** @var ContainerFactoryUserGroup_crud $crud */
                $crud = Container::get('ContainerFactoryUserGroup_crud');
                $crud->setCrudId($this->id);
                if ($this->id > 0) {
                    $crud->findById(true);
                }

                $crud->delete();


                /** @var ContainerFactoryLanguageParseIni $groupLanguage */
                $groupLanguage     = Container::get('ContainerFactoryLanguageParseIni',
                                                    $crud->getCrudData());
                $groupLanguageItem = $groupLanguage->get();

                /** @var ContainerFactoryLog_crud_notification $crud */
                $crud = Container::get('ContainerFactoryLog_crud_notification');
                $crud->setCrudMessage(sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroupEdit/notification/delete'),
                                              $groupLanguageItem['name']));
                $crud->setCrudClass(__CLASS__);
                $crud->setCrudCssClass('simpleModifySuccess');
                $crud->setCrudClassIdent($this->id);
                $crud->setCrudType($crud::NOTIFICATION_LOG);

                /** @var ContainerIndexPage $page */
                $page = Container::getInstance('ContainerIndexPage');
                $nid  = $page->addNotification($crud);

                /** @var ContainerFactoryRouter $router */
                $router = clone Container::get('ContainerFactoryRouter');

                $router->analyzeUrl('index.php?application=ApplicationAdministrationUserGroup');

                $router->setQuery('_notification',
                                  $nid);

                $router->redirect();
            }
        }
    }

    public static function javascriptDeleteCheckbox(ContainerExtensionTemplateParseCreateFormElementCheckbox $object, ContainerIndexHtmlAttribute $attribut): void
    {
        $attribut->set('class',
                       'askDeleteDialog');
        $attribut->set('data-dialogid',
                       null,
                       'askDeleteDialog' . $attribut->get('name',
                                                          'name'));
    }

}
