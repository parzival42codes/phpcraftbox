<?php declare(strict_types=1);

class ApplicationAdministrationContentView_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        $ident = Container::get('ApplicationAdministrationContentView/ident');
        $crud  = new ApplicationAdministrationContent_crud();
        $crud->setCrudIdent($ident);

        try {
            $crud->findById(true);
        } catch (Throwable $exception) {
            return '';
        }

        $crudData = json_decode($crud->getCrudData(),
                                true);

        $title       = ContainerFactoryLanguage::getLanguageText($crudData['title']);
        $description = ContainerFactoryLanguage::getLanguageText($crudData['description']);

        /** @var ContainerIndexPage $page */ //
        $page = Container::getInstance('ContainerIndexPage');

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem($title,
                                       $description);

        $page->setPageTitle($title);
        $page->setPageDescription($description);

        return $crud->getCrudContent();

    }

}
