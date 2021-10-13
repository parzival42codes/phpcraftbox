<?php declare(strict_types=1);

class ApplicationAdministrationLogPage_app extends ApplicationAdministration_abstract
{


    public function setContent(): string
    {

        /** @var ContainerExtensionTemplateParseCreateFilterHelper $filter */
        $filter = Container::get('ContainerExtensionTemplateParseCreateFilterHelper',
                                 'page');

        $filterData = $this->getFilterData();

        $filter->addFilter('crudType',
                           null,
                           ContainerFactoryLanguage::get('/ApplicationAdministrationLogPage/filter/header/type'),
                           'select',
                           $filterData);

        $filter->create();

        $filterCrud = $filter->getFilterCrud();

        /** @var ContainerFactoryLogPage_crud $crud */
        $crud  = Container::get('ContainerFactoryLogPage_crud');
        $count = $crud->count($filterCrud);

        /** @var ContainerExtensionTemplateParseCreatePaginationHelper $pagination */
        $pagination = Container::get('ContainerExtensionTemplateParseCreatePaginationHelper',
                                     'page',
                                     $count,
                                     3);
        $pagination->create();
        $this->pageData();

        $tableTcs = [];

        /** @var ContainerFactoryLogPage_crud $crud */
        $crud        = Container::get('ContainerFactoryLogPage_crud');
        $crudPageAll = $crud->find($filterCrud,
                                   [],
                                   [
                                       'crudId DESC'
                                   ],
                                   $pagination->getPagesView(),
                                   $pagination->getPageOffset());

        /** @var ContainerFactoryLogPage_crud $crudPageAllItem */
        foreach ($crudPageAll as $crudPageAllItem) {

            $content = $crudPageAllItem->getCrudData();

            if (!empty($content)) {
                /** @var ContainerExtensionTemplateParseHelperDialog $templateDialogContent */
                $templateDialogContent = Container::get('ContainerExtensionTemplateParseHelperDialog');
                $templateDialogContent->setHeader('Data');
                $templateDialogContent->setBody($content);
                $templateDialogContent->setFooter();

                $content = $templateDialogContent->create('Content');
            }

            $dataVariableCreatedDateTime = new DateTime($crudPageAllItem->getDataVariableCreated());

//            d($crudPageAllItem);
//            eol();

            $tableTcs[] = [
                'crudId'              => $crudPageAllItem->getCrudId(),
                'userName'            => $crudPageAllItem->getAdditionalQuerySelect('user_crudUsername'),
                'crudType'            => $crudPageAllItem->getCrudType(),
                'crudUrlPure'         => $crudPageAllItem->getCrudUrlPure(),
                'crudUrlReadable'     => $crudPageAllItem->getCrudUrlReadable(),
                'crudMessage'         => $crudPageAllItem->getCrudMessage(),
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
        $template->assign('LogPageTable_LogPageTable',
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

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationLogPage/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationLogPage');

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=ApplicationAdministrationLogPage');


    }

    protected function getFilterData(): array
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('log_page');
        $query->select('crudType');
        $query->selectFunction('count(*) as c');
        $query->groupBy('crudType');
        $query->construct();
        $smtp = $query->execute();

        $collectCount = [];
        while ($smtpData = $smtp->fetch()) {
            $collectCount[$smtpData['crudType']] = $smtpData['c'];
        }

        $filterData = [
            ''                                                 => '',
            ContainerFactoryLogPage_crud::PAGE_NOT_FOUND       => sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationLogPage/filter/notFound'),
                ($collectCount[ContainerFactoryLogPage_crud::PAGE_NOT_FOUND] ?? 0)),
            ContainerFactoryLogPage_crud::PAGE_ACCESS_DENTITED => sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationLogPage/filter/denited'),
                                                                          ($collectCount[ContainerFactoryLogPage_crud::PAGE_ACCESS_DENTITED] ?? 0)),
        ];

        return $filterData;
    }
}
