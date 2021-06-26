<?php declare(strict_types=1);

class ApplicationAdministrationBox_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('page_box');
        $query->selectFunction('count(*) as c');
        $query->select('crudAssignment');
        $query->groupBy('crudAssignment');

        $query->construct();
        $smtp = $query->execute();

        $tableTcs = [];

        while ($smtpData = $smtp->fetch()) {
            $tableTcs[] = [
                'crudAssignment' => '<a class="block" href="index.php?application=ApplicationAdministrationBoxEdit&route=edit&id=' . $smtpData['crudAssignment'] . '">' . $smtpData['crudAssignment'] . '</a>',
                'count'          => $smtpData['c'],
            ];
        }

        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'default');
        $templateCacheContent = $templateCache->getCacheContent();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['default']);
        $template->assign('ApplicationAdministrationBox_ApplicationAdministrationBox',
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

        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationAdministrationBox/breadcrumb'));

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=administration');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationBox/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationBox');
    }

}
