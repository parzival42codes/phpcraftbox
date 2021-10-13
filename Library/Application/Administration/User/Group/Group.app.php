<?php declare(strict_types=1);

/**
 * Administration Group Edit
 *
 * Administration Group Edit
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 4
 * @modul    language_name_de_DE Gruppen Administration
 * @modul    language_name_en_US Group Administration
 * @modul    language_path_de_DE /Administration/Benutzer
 * @modul    language_path_en_US /Administration/User
 *
 */

class ApplicationAdministrationUserGroup_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('user_group');
        $query->select('crudId');
        $query->select('crudData');

        $query->construct();
        $smtp = $query->execute();

        $templateContent = '';

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'default,item');
        $templateCacheContent = $templateCache->get();

        $userGroupCollect = [];

        while ($smtpData = $smtp->fetch()) {
            $userGroupCollect[$smtpData['crudId']] = $smtpData;
        }

        /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccess */
        $crudAccess     = Container::get('ContainerFactoryUserGroup_crud_groupaccess');
        $crudAccessRead = $crudAccess->find();

        $crudAccessArray = [];

        /** @var ContainerFactoryUserGroup_crud_groupaccess $crudAccessReadItem */
        foreach ($crudAccessRead as $crudAccessReadItem) {
            $crudAccessArray[$crudAccessReadItem->getCrudUserGroupId()][] = $crudAccessReadItem->getCrudAccess();
        }

        foreach ($userGroupCollect as $userGroupCollectElem) {
            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCacheContent['item']);

            /** @var ContainerFactoryLanguageParseIni $groupLanguage */
            $groupLanguage     = Container::get('ContainerFactoryLanguageParseIni',
                                                $userGroupCollectElem['crudData']);
            $groupLanguageItem = $groupLanguage->get();

            $template->assign('crudName',
                              $groupLanguageItem['name']);
            $template->assign('crudDescription',
                              $groupLanguageItem['description']);
            $template->assign('groupRights',
                              implode('<br />',
                                      $crudAccessArray[$userGroupCollectElem['crudId']]));

            /** @var ContainerFactoryRouter $route */
            $route = Container::get('ContainerFactoryRouter');
            $route->setApplication('ApplicationAdministrationUserGroupEdit');
            $route->setRoute('edit');
            $route->setParameter('id',
                                 $userGroupCollectElem['crudId']);

            $template->assign('linkEdit',
                              $route->getUrlReadable());

            $template->parse();
            $templateContent .= $template->get();
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['default']);
        $template->assign('groups',
                          $templateContent);
        $template->parse();

        return $template->get();

    }

    protected function pageData():void
    {
/** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');

        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroup/breadcrumb'));

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=ApplicationAdministration');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationUserGroup/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationUserGroup');
    }

}
