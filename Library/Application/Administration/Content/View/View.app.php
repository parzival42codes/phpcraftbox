<?php declare(strict_types=1);

class ApplicationAdministrationContentView_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        $ident  = Container::get('ApplicationAdministrationContentView/ident');

        /** @var ApplicationAdministrationContent_crud_index $crudIndex */
        $crudIndex = Container::get('ApplicationAdministrationContent_crud_index');
        $crudIndex->setCrudIdent($ident);
        $crudIndex->findById();

        /** @var ApplicationAdministrationContent_crud $crud */
        $crud = Container::get('ApplicationAdministrationContent_crud');
        $crud->setCrudIdent($crudIndex->getCrudContentIdent());
        $crud->findById();

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle($crudIndex->getCrudTitle());

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem($crudIndex->getCrudTitle(),
                                       $crudIndex->getCrudPath());

        return $crud->getCrudContent();

        123
        456
        789

    }

}
