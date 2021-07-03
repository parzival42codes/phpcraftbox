<?php declare(strict_types=1);

class ApplicationAdministrationContent_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
//        $this->importQueryDatabaseFromCrud('ApplicationAdministrationContent_crud');

        /** @var ContainerFactoryRouter $router */
        $router = clone Container::getInstance('ContainerFactoryRouter');

        if ($router->getQuery('createIndex')) {
            /** @var ApplicationAdministrationContent_crud_index $crud */
            $crud = Container::get('ApplicationAdministrationContent_crud_index');
            $crud->setCrudContentIdent($router->getQuery('createIndex'));
            $crud->createIndexFromContentIdent();
        }

        $indexCollect  = [];
        $crudIndex     = new ApplicationAdministrationContent_crud_index();
        $crudIndexList = $crudIndex->find();
        /** @var ApplicationAdministrationContent_crud_index $crudIndexListItem */
        foreach ($crudIndexList as $crudIndexListItem) {
            $indexCollect[$crudIndexListItem->getCrudContentIdent()][] = $crudIndexListItem->getCrudLanguage() . ' - ' . $crudIndexListItem->getCrudPath();
        }

        /** @var ApplicationAdministrationContent_crud $crud */ //
        /** @var ContainerFactoryLogPage_crud $crud */
        $crud  = Container::get('ApplicationAdministrationContent_crud');
        $count = $crud->count();

        /** @var ContainerExtensionTemplateParseCreatePaginationHelper $pagination */
        $pagination = Container::get('ContainerExtensionTemplateParseCreatePaginationHelper',
                                     'content',
                                     $count,
                                     3);
        $pagination->create();
        $this->pageData();

        $tableTcs = [];

        /** @var ApplicationAdministrationContent_crud $crud */
        $crud           = Container::get('ApplicationAdministrationContent_crud');
        $crudContentAll = $crud->find([],
                                      [],
                                      [
                                          'crudIdent DESC'
                                      ],
                                      $pagination->getPagesView(),
                                      $pagination->getPageOffset());

        /** @var ApplicationAdministrationContent_crud $crudContentAllItem */
        foreach ($crudContentAll as $crudContentAllItem) {

            /** @var ContainerExtensionTemplateParseHelperDialog $templateDialog */
            $templateDialog = Container::get('ContainerExtensionTemplateParseHelperDialog');
            $templateDialog->setHeader('Data');
            $templateDialog->setBody('<span class="box ApplicationAdministrationContentBox">' . $crudContentAllItem->getCrudData() . '</span>');
            $templateDialog->setFooter();

            $contentData = $templateDialog->create('Data');

            $dataVariableCreatedDateTime = new DateTime($crudContentAllItem->getDataVariableCreated());
            $dataVariableEditedDateTime  = new DateTime($crudContentAllItem->getDataVariableEdited());

            $router->setQuery('createIndex',
                              $crudContentAllItem->getCrudIdent());
            $linkCreateIndex = $router->getUrlReadable();

            if (isset($indexCollect[$crudContentAllItem->getCrudIdent()])) {
                $indexButton = '<a href"' . $linkCreateIndex . '" class="simpleModifySuccess withFill btn">' . ContainerFactoryLanguage::get('/ApplicationAdministrationContent/table/button/reCreate') . '</a>';
            }
            else {
                $router->getUrlReadable();
                $indexButton = '<a href="' . $linkCreateIndex . '" class="simpleModifyError withFill btn btn">' . ContainerFactoryLanguage::get('/ApplicationAdministrationContent/table/button/create') . '</a>';
            }

            $tableTcs[] = [
//                'crudIdent'           => $crudContentAllItem->getCrudIdent(),
'crudIdent'           => '<a href="index.php?application=ApplicationAdministrationContentEdit&route=edit&id=' . $crudContentAllItem->getCrudIdent() . '" class="block">' . $crudContentAllItem->getCrudIdent() . '</a>',
'index'               => implode('<br />',
    ($indexCollect[$crudContentAllItem->getCrudIdent()] ?? [])),
'crudData'            => $contentData,
'dataVariableCreated' => $dataVariableCreatedDateTime->format(ContainerFactoryLanguage::get('/ContainerFactoryLanguage/language/dateTime')),
'dataVariableEdited'  => $dataVariableEditedDateTime->format(ContainerFactoryLanguage::get('/ContainerFactoryLanguage/language/dateTime')),
'createIndex'         => $indexButton,
            ];
        }

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);
        $template->assign('ContentTable_ContentTable',
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

        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationAdministrationContent/breadcrumb'));

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=administration');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationContent/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationContent');

    }


}
