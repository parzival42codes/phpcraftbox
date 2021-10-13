<?php declare(strict_types=1);

class ApplicationAdministrationLogNotification_app extends ApplicationAdministration_abstract
{


    public function setContent(): string
    {

        $filterData = $this->getFilterData();

        /** @var ContainerExtensionTemplateParseCreateFilterHelper $filter */
        $filter = Container::get('ContainerExtensionTemplateParseCreateFilterHelper',
                                 'notification');

        $filter->addFilter('crudClass',
                           null,
                           ContainerFactoryLanguage::get('/ApplicationAdministrationLogNotification/filter/header/class'),
                           'select',
                           $filterData);

        $filter->addFilter('crudClassIdent',
                           null,
                           ContainerFactoryLanguage::get('/ApplicationAdministrationLogNotification/filter/header/ident'),
                           'input',
                           []);

        $filter->create();

        $filterCrud = $filter->getFilterCrud();

        /** @var ContainerFactoryLog_crud_notification $crud */
        $crud  = Container::get('ContainerFactoryLog_crud_notification');
        $count = $crud->count($filterCrud);

        /** @var ContainerExtensionTemplateParseCreatePaginationHelper $pagination */
        $pagination = Container::get('ContainerExtensionTemplateParseCreatePaginationHelper',
                                     'notification',
                                     $count,
                                     3);
        $pagination->create();
        $this->pageData();

        $filterCrud['crudShowInLog'] = $crud::SHOW_IN_LOG_YES;

        $tableTcs = [];

        /** @var ContainerFactoryLog_crud_notification $crud */
        $crud                = Container::get('ContainerFactoryLog_crud_notification');
        $crudNotificationAll = $crud->find($filterCrud,
                                           [],
                                           [
                                               'crudId DESC'
                                           ],
                                           $pagination->getPagesView(),
                                           $pagination->getPageOffset());

        /** @var ContainerFactoryLog_crud_notification $crudNotificationAllItem */
        foreach ($crudNotificationAll as $crudNotificationAllItem) {

            $content = $crudNotificationAllItem->getCrudData();

            if (!empty($content)) {
                /** @var ContainerExtensionTemplateParseHelperDialog $templateDialogContent */
                $templateDialogContent = Container::get('ContainerExtensionTemplateParseHelperDialog');
                $templateDialogContent->setHeader('Data');
                $templateDialogContent->setBody($content);
                $templateDialogContent->setFooter();

                $content = $templateDialogContent->create('Content');
            }

            $dataVariableCreatedDateTime = new DateTime($crudNotificationAllItem->getDataVariableCreated());

            $tableTcs[] = [
                'crudId'              => $crudNotificationAllItem->getCrudId(),
                'userName'            => $crudNotificationAllItem->getAdditionalQuerySelect('user_crudUsername'),
                'crudClass'           => $crudNotificationAllItem->getCrudClass(),
                'crudClassIdent'      => $crudNotificationAllItem->getCrudClassIdent(),
                'crudMessage'         => '<span class="' . $crudNotificationAllItem->getCrudCssClass() . ' withFill">' . $crudNotificationAllItem->getCrudMessage() . '</span>',
                'crudData'            => $content,
                'dataVariableCreated' => $dataVariableCreatedDateTime->format(ContainerFactoryLanguage::get('/ContainerFactoryLanguage/language/dateTime')),
            ];
        }

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['default']);
        $template->assign('LogNotificationTable_LogNotificationTable',
                          $tableTcs);

        $template->parseQuote();
        $template->parse();
        $template->catchDataClear();
        return $template->get();
    }

    protected function pageData():void
    {
        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'));

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=Administration');

        $breadcrumb->addBreadcrumbItem('Log');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationLogNotification/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationLogNotification');

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=ApplicationAdministrationLogNotification');


    }

    protected function getFilterData(): array
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('log_notification');
        $query->select('crudClass');
        $query->selectFunction('count(*) as c');
        $query->groupBy('crudClass');
        $query->construct();
        $smtp = $query->execute();

        $filterData = [
            ''
        ];
        while ($smtpData = $smtp->fetch()) {
            $filterData[$smtpData['crudClass']] = $smtpData['crudClass'] . ' (' . $smtpData['c'] . ')';
        }

        return $filterData;
    }
}
