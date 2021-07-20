<?php declare(strict_types=1);

class ApplicationAdministrationContentEdit_app extends ApplicationAdministration_abstract
{

    const CONTENT_SOURCE_MAIN    = 'main';
    const CONTENT_SOURCE_HISTORY = 'history';

    protected string $id      = '';
    protected string $version = '';

    protected string $source = self::CONTENT_SOURCE_MAIN;

    public function setContent(): string
    {
        /** @var ContainerFactoryRouter $router */
        $router   = Container::getInstance('ContainerFactoryRouter');
        $this->id = (string)$router->getParameter('id');

        /** @var ApplicationAdministrationContent_crud $crud */
        $crud = Container::get('ApplicationAdministrationContent_crud');

        if ($this->source === self::CONTENT_SOURCE_MAIN) {
            $crud->setCrudIdent($this->id);
            if (!empty($this->id)) {
                $crud->findById(true);
            }
        }
        elseif ($this->source === self::CONTENT_SOURCE_HISTORY) {
            /** @var ApplicationAdministrationContent_crud_history $crud */
            $crud = Container::get('ApplicationAdministrationContent_crud_history');
            $crud->setCrudId((int)$this->id);
            if (!empty($this->version)) {
                $crud->findById(true);
            }
        }

        $this->pageData($this->id);

        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = Container::get('ContainerExtensionTemplateParseCreateFormResponse',
                                   'ContentEditContentEdit');

        /** @var ContainerExtensionTemplateParseCreateFormResponse $responseHistory */
        $responseHistory = Container::get('ContainerExtensionTemplateParseCreateFormResponse',
                                          'ContentEditContentHistory');

        $this->formResponse($response,
                            $responseHistory);

        /** @var ContainerExtensionTemplateParseCreateFormRequest $request */
        $request = Container::get('ContainerExtensionTemplateParseCreateFormRequest',
                                  'ContentEditContentEdit');
        /** @var ContainerExtensionTemplateParseCreateFormRequest $requestHistory */
        $requestHistory = Container::get('ContainerExtensionTemplateParseCreateFormRequest',
                                         'ContentEditContentHistory');

        $crudArray = [
            '' => ''
        ];

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('content_history');
        $query->select('crudId');
        $query->setParameterWhere('crudIdent',
                                  $this->id);
        $query->orderBy('dataVariableCreated DESC');

        $query->construct();
        $smtp = $query->execute();

        while ($smtpData = $smtp->fetch()) {
            $crudArray[$smtpData['crudId']] = $smtpData['dataVariableCreated'];
        }

        /** @var ContainerExtensionTemplateParseCreateFormElementText $elementContentHistory */
        $elementContentHistory = Container::get('ContainerExtensionTemplateParseCreateFormElementSelect',
                                                $crudArray);
        $elementContentHistory->setLabel(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/form/contentHistory/label'));
        $elementContentHistory->setValue($responseHistory->get('contentHistory'));
        $elementContentHistory->setInfo(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/form/contentHistory/info'));

        $requestHistory->addElement('contentHistory',
                                    $elementContentHistory);

        $requestHistory->create();

        $content = $response->get('contentContent',
                                  $crud->getCrudContent());

        $content = strtr($content,
                         [
                             '{' => '&#123;',
                             '}' => '&#125;',
                             '<' => '&#60;',
                             '>' => '&#62;',
                         ]);

        /** @var ContainerExtensionTemplateParseCreateFormElementTextarea $elementContentContent */
        $elementContentContent = Container::get('ContainerExtensionTemplateParseCreateFormElementTextarea');
        $elementContentContent->setLabel(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/form/contentContent/label'));
        $elementContentContent->setValue($content);
        $elementContentContent->setInfo(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/form/contentContent/info'));
        $elementContentContent->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired');

        if ($this->source === self::CONTENT_SOURCE_HISTORY) {
            $elementContentContent->addModify([
                                                  __CLASS__,
                                                  'identReadOnly'
                                              ]);
        }

        $request->addElement('contentContent',
                             $elementContentContent);

        /** @var ContainerExtensionTemplateParseCreateFormElementText $elementContentIdent */
        $elementContentIdent = Container::get('ContainerExtensionTemplateParseCreateFormElementText');
        $elementContentIdent->setLabel(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/form/contentIdent/label'));
        $elementContentIdent->setValue($response->get('contentIdent',
                                                      $crud->getCrudIdent()));
        $elementContentIdent->setInfo(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/form/contentIdent/info'));
        $elementContentIdent->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired');

        if (!empty($this->id) || $this->source === self::CONTENT_SOURCE_HISTORY) {
            $elementContentIdent->addModify([
                                                __CLASS__,
                                                'identReadOnly'
                                            ]);
        }

        $request->addElement('contentIdent',
                             $elementContentIdent);

        /** @var ContainerExtensionTemplateParseCreateFormElementTextarea $elementContentData */
        $elementContentData = Container::get('ContainerExtensionTemplateParseCreateFormElementTextarea');
        $elementContentData->setLabel(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/form/contentData/label'));
        $elementContentData->setValue($response->get('contentData',
                                                     $crud->getCrudData()));
        $elementContentData->setInfo(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/form/contentData/info'));
        $elementContentData->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired');

        if ($this->source === self::CONTENT_SOURCE_HISTORY) {
            $elementContentData->addModify([
                                               __CLASS__,
                                               'identReadOnly'
                                           ]);
        }

        $request->addElement('contentData',
                             $elementContentData);

        $request->create();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);
        $template->assign('APP_SOURCE',
                          $this->source);

        $template->setRegisteredFunctions('historyEditFormFooter',
            function ($content, $htmlTags, $templateObject) {
                /** @var ContainerExtensionTemplate $templateObject */

                if ($templateObject->getAssign('APP_SOURCE') === ApplicationAdministrationContentEdit_app::CONTENT_SOURCE_MAIN) {

                    /** @var ContainerExtensionTemplate $template */
                    $template = Container::get('ContainerExtensionTemplate');
                    $template->set($content);
                    $template->parse();

                    return $template->get();
                }
                else {
                    return '';
                }

            });

        $template->assign('link',
                          Config::get('/server/http/base/url') . '/' . $crud->getCrudIdent());

        $template->parseQuote();
        $template->parse();
        return $template->get();
    }

    protected function pageData($id): void
    {

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/breadcrumb'));

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=ApplicationAdministrationContentEdit');

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=administration');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationContent/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationContent');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationContentEdit');

        $breadcrumb->addBreadcrumbItem($id,
                                       'index.php?application=ApplicationAdministrationContentEdit&id=' . $id);

        /** @var ContainerFactoryMenu $menu */
        $menu = $this->getMenu();
        $menu->setMenuClassMain('ApplicationAdministrationContent');
    }

    protected function formResponse(ContainerExtensionTemplateParseCreateFormResponse $response, ContainerExtensionTemplateParseCreateFormResponse $responseHistory): void
    {

        if ($responseHistory->isHasResponse()) {
            $this->version = (string)$responseHistory->get('contentHistory');
            $this->source  = self::CONTENT_SOURCE_HISTORY;
        }

        if ($response->isHasResponse()) {
            /** @var ContainerFactoryLanguageParseIni $groupLanguage */
            $groupLanguage = Container::get('ContainerFactoryLanguageParseIni',
                                            $response->get('contentData'));

            /** @var ApplicationAdministrationContent_crud $crud */
            $crud = Container::get('ApplicationAdministrationContent_crud');
            $crud->setCrudIdent($response->get('contentIdent'));
            $crud->setCrudData($groupLanguage->getIniClean());
            $crud->setCrudContent($response->get('contentContent'));
            $crud->setCrudData($response->get('contentData'));
            $crud->setCrudRequired($crud->getCrudRequired());

            $crud->insertUpdate();


            /** @var ContainerFactoryLog_crud_notification $crudNotification */
            $crudNotification = Container::get('ContainerFactoryLog_crud_notification');
            $crudNotification->setCrudMessage(sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationContentEdit/notification/saved'),
                                                      $crud->getCrudIdent()));
            $crudNotification->setCrudClass(__CLASS__);
            $crudNotification->setCrudCssClass('simpleModifySuccess');
            $crudNotification->setCrudClassIdent($this->id);
            $crudNotification->setCrudData($crud->getCrudData());

            /** @var ContainerIndexPage $page */
            $page = Container::getInstance('ContainerIndexPage');
            $page->addNotification($crudNotification);

            /** @var ApplicationAdministrationContent_crud_history $crudHistory */
            $crudHistory = Container::get('ApplicationAdministrationContent_crud_history');
            $crudHistory->setCrudIdent($this->id);
            $crudHistory->setCrudData($crud->getCrudData());
            $crudHistory->setCrudContent($crud->getCrudContent());
            $crudHistory->insert();#
        }
    }

    public static function identReadOnly($element, $attribute): void
    {
        if ($element instanceof ContainerExtensionTemplateParseCreateFormElement_abstract) {
            if (!empty($element->getValue())) {
                $attribute->set('readonly',
                                null,
                                'readonly');
            }
            elseif ($element instanceof ContainerExtensionTemplateParseCreateFormResponse) {

            }
        }

    }

}
