<?php declare(strict_types=1);

class ApplicationAdministrationModulOverview_app extends ApplicationAdministration_abstract
{
    /**
     * @var string
     */
    protected string $crudMain = 'ContainerFactoryModul_crud';

    public function setContent(): string
    {
        $directory     = new RecursiveDirectoryIterator(CMS_PATH_CUSTOM_LOCAL,
                                                        RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator      = new RecursiveIteratorIterator($directory,
                                                       RecursiveIteratorIterator::LEAVES_ONLY);
        $iteratorArray = iterator_to_array($iterator);

        $allModule = [];

        /** @var SplFileInfo $iteratorArrayItemObj */
        foreach ($iteratorArray as $iteratorArrayItemObj) {
            $fileClassName = $iteratorArrayItemObj->getPathname();
            $fileClassName = ContainerFactoryFile::getReducedFilename($fileClassName);
            $fileClassName = Core::identifyFileClass($fileClassName);
            $allModule[]   = Core::getRootClass(strtr($fileClassName,
                                                      [
                                                          'CustomLocal'      => '',
                                                          'CustomRepository' => '',
                                                      ]));
        }

        $allModule = array_unique($allModule);

        $pagination = $this->createPagination();
        $this->pageData();

        $tableTcs = [];

        /** @var ContainerFactoryModul_crud $crud */
        $crud         = Container::get('ContainerFactoryModul_crud');
        $crudModulAll = $crud->find([//                                          'crudParentModul' => '',
                                    ],
                                    [
                                        'crudModul',
                                        'crudParentModul'
                                    ],
                                    [],
                                    $pagination->getPagesView(),
                                    $pagination->getPageOffset());


//        eol();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default,tooltip');

        /** @var ContainerFactoryModul_crud $crudModulAllItem */
        foreach ($crudModulAll as $crudModulAllItem) {

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->getCacheContent()['tooltip']);
            $template->assign('crudName',
                              $crudModulAllItem->getCrudName());
            $template->assign('crudDescription',
                              $crudModulAllItem->getCrudDescription());
            $template->assign('crudAuthor',
                              $crudModulAllItem->getCrudAuthor());
            $template->parse();

            /** @var ContainerExtensionTemplateParseHelperTooltip $toolTip */
            $toolTip = Container::get('ContainerExtensionTemplateParseHelperTooltip',
                                      uniqid(),
                                      $template->get());

            $dataVariableCreatedDateTime = new DateTime($crudModulAllItem->getDataVariableCreated());
            $dataVariableEditedDateTime  = new DateTime($crudModulAllItem->getDataVariableEdited());

            if (
                !in_array($crudModulAllItem->getCrudModul(),
                          $allModule)
            ) {
                $tooltipString = $crudModulAllItem->getCrudModul();
            }
            else {
                $tooltipString = '<span class="ApplicationAdministrationModulOverview_custom">' . $crudModulAllItem->getCrudModul() . '</span>';
            }

            $tableTcs[] = array_merge($crudModulAllItem->getDataAsArray(),
                                      [
                                          'crudClassDetail'     => $toolTip->create($tooltipString),
                                          'dataVariableCreated' => $dataVariableCreatedDateTime->format(ContainerFactoryLanguage::get('/ContainerFactoryLanguage/language/dateTime')),
                                          'dataVariableEdited'  => $dataVariableEditedDateTime->format(ContainerFactoryLanguage::get('/ContainerFactoryLanguage/language/dateTime')),
                                      ]);
        }

//        d($customIndex);
//        d($allModule);

//        eol();


        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);
        $template->assign('ModulTable_ModulTable',
                          $tableTcs);
        $template->parse();
        return $template->get();

    }

    protected function pageData(): void
    {
        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');

        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationAdministrationModulOverview/breadcrumb'));

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=administration');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationModul/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationModul');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationModulOverview/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationModulOverview');

    }

}
