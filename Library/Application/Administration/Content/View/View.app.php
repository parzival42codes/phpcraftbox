<?php declare(strict_types=1);

class ApplicationAdministrationContentView_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        $ident = Container::get('ApplicationAdministrationContentView/ident');
        $crud  = new ApplicationAdministrationContent_crud();
        $crud->setCrudIdent($ident);
        $crud->findById(true);

        $crudData = json_decode($crud->getCrudData(),
                                true);

        $title       = ContainerFactoryLanguage::getLanguageText((string)Config::get('/environment/language'),
                                                                 $crudData['title']);
        $description = ContainerFactoryLanguage::getLanguageText((string)Config::get('/environment/language'),
                                                                 $crudData['description']);

        /** @var ContainerIndexPage $page */ //
        $page = Container::getInstance('ContainerIndexPage');

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem($title,
                                       $description);

        return $crud->getCrudContent();

    }

}
