<?php declare(strict_types=1);

class ApplicationAdministrationContentView_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        $ident = Container::get('ApplicationAdministrationContentView/ident');
        $crud  = new ApplicationAdministrationContent_crud();
        $crud->setCrudIdent($ident);
        $crud->findById(true);

        /** @var ContainerIndexPage $page */
//        $page = Container::getInstance('ContainerIndexPage');
//        $page->setPageTitle('');

        $breadcrumb = $page->getBreadcrumb();
//        $breadcrumb->addBreadcrumbItem($crud->getCrudTitle(),
//                                       $crud->getCrudPath());

        return $crud->getCrudContent();

    }

}
