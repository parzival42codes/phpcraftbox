<?php declare(strict_types=1);

class ApplicationAdministrationLogError_app extends ApplicationAdministration_abstract
{
    /**
     * @return string
     * @throws DetailedException
     */
    public function setContent(): string
    {
        /** @var ContainerFactoryRequest $response */
        $response = Container::get('ContainerFactoryRequest',
                                   ContainerFactoryRequest::REQUEST_GET,
                                   'cache');

        if ($response->exists() && $response->get() === 'clean') {
            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__ . '#select',
                                    'cache',
                                    ContainerFactoryDatabaseQuery::MODE_OTHER);
            $query->query('DELETE FROM log_error');
            $query->execute();

//            $query->query('VACUUM');
//            $query->execute();

        }

        $filterData = $this->getFilterData();

        /** @var ContainerExtensionTemplateParseCreateFilterHelper $filter */
        $filter = Container::get('ContainerExtensionTemplateParseCreateFilterHelper',
                                 'error');

        $filter->addFilter('crudType',
                           null,
                           ContainerFactoryLanguage::get('/ApplicationAdministrationLogError/filter/header/type'),
                           'select',
                           $filterData);

        $filter->create();

        $filterValues = $filter->getFilterValues();
        $filterCrud   = [];
        if (isset($filterValues['crudType']) && !empty($filterValues['crudType'])) {
            $filterCrud['crudType'] = $filterValues['crudType'];
        }

        /** @var ContainerFactoryLogError_crud $crud */
        $crud  = Container::get('ContainerFactoryLogError_crud');
        $count = $crud->count($filterCrud);

        /** @var ContainerExtensionTemplateParseCreatePaginationHelper $pagination */
        $pagination = Container::get('ContainerExtensionTemplateParseCreatePaginationHelper',
                                     'error',
                                     $count,
                                     3);
        $pagination->create();
        $this->pageData();

        $tableTcs = [];

        /** @var ContainerFactoryLogError_crud $crud */
        $crud         = Container::get('ContainerFactoryLogError_crud');
        $crudErrorAll = $crud->find($filterCrud,
                                    [],
                                    [
                                        'crudId DESC'
                                    ],
                                    $pagination->getPagesView(),
                                    $pagination->getPageOffset());

        /** @var ContainerFactoryLogError_crud $crudErrorAllItem */
        foreach ($crudErrorAll as $crudErrorAllItem) {

            if ($crudErrorAllItem->getCrudType() !== ContainerFactoryLogError_crud::LOG_TYPE_EXCEPTION) {
                $content = $crudErrorAllItem->getCrudContent();
            }
            else {
                /** @var ContainerExtensionTemplateParseHelperDialog $templateDialog */
                $templateDialog = Container::get('ContainerExtensionTemplateParseHelperDialog');
                $templateDialog->setHeader('Exception');
                $templateDialog->setBody(CoreErrorhandler::doExceptionView(unserialize($crudErrorAllItem->getCrudContent())));
                $templateDialog->setFooter();
                $content = $templateDialog->create('Exception');
            }

            /** @var ContainerExtensionTemplateParseHelperDialog $templateDialog */
            $templateDialog = Container::get('ContainerExtensionTemplateParseHelperDialog');
            $templateDialog->setHeader('Backtrace');
            $templateDialog->setBody(ContainerHelperView::convertBacktraceView(unserialize($crudErrorAllItem->getCrudBacktrace())));
            $templateDialog->setFooter();

            $backtrace = $templateDialog->create('Backtrace');

            $path = strtr($crudErrorAllItem->getCrudPath(),
                          [
                              '/' => '&shy;/',
                          ]);

            if (!empty($content) && $crudErrorAllItem->getCrudType() !== ContainerFactoryLogError_crud::LOG_TYPE_EXCEPTION) {
                /** @var ContainerExtensionTemplateParseHelperDialog $templateDialogContent */
                $templateDialogContent = Container::get('ContainerExtensionTemplateParseHelperDialog');
                $templateDialogContent->setHeader($crudErrorAllItem->getCrudTitle());
                $templateDialogContent->setBody($content);
                $templateDialogContent->setFooter();

                $content = $templateDialogContent->create('Content');
            }

            $crudType     = ucfirst($crudErrorAllItem->getCrudType());
            $crudTypeView = '<span class="simpleModify' . $crudType . '">' . $crudType . '</span>';

            $dataVariableCreatedDateTime = new DateTime($crudErrorAllItem->getDataVariableCreated());

            $tableTcs[] = [
                'crudId'              => $crudErrorAllItem->getCrudId(),
                'crudType'            => $crudTypeView,
                'crudPath'            => $path,
                'crudTitle'           => $crudErrorAllItem->getCrudTitle(),
                'crudContent'         => $content,
                'crudBacktrace'       => $backtrace,
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
        $template->assign('LogErrorTable_LogErrorTable',
                          $tableTcs);

        $template->parseQuote();
        $template->parse();
        $template->catchDataClear();
        return $template->get();
    }

    protected function pageData(): void
    {
        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'));

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=Administration');

        $breadcrumb->addBreadcrumbItem('Log');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationLogError/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationLogError');

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=ApplicationAdministrationLogError');


    }

    protected function getFilterData(): array
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                'cache',
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('log_error');
        $query->select('crudType');
        $query->selectFunction('count () as c');
        $query->groupBy('crudType');
        $query->construct();
        $smtp = $query->execute();

        $filterData = [
            ''                                                => '',
            ContainerFactoryLogError_crud::LOG_TYPE_TRIGGER   => sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationLogError/filter/trigger'),
                                                                         0),
            ContainerFactoryLogError_crud::LOG_TYPE_WARNING   => sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationLogError/filter/warning'),
                                                                         0),
            ContainerFactoryLogError_crud::LOG_TYPE_EXCEPTION => sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationLogError/filter/exception'),
                                                                         0),
            ContainerFactoryLogError_crud::LOG_TYPE_INFO      => sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationLogError/filter/info'),
                                                                         0),
        ];
        while ($smtpData = $smtp->fetch()) {
            if (isset($filterData[$smtpData['crudType']])) {
                $filterData[$smtpData['crudType']] = sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationLogError/filter/' . $smtpData['crudType']),
                                                             $smtpData['c']);
            }
        }

        return $filterData;
    }
}
