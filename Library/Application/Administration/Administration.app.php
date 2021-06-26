<?php

class ApplicationAdministration_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        $this->pageData();

//        throw new DetailedException('test123',
//                                    0,
//                                    null,
//                                    [
//                                        'debug' => [
//                                            'foo' => 'Bar',
//                                        ]
//                                    ]);

        /** @var ContainerIndexPageBox $pageBox */
        $pageBox = Container::get('ContainerIndexPageBox');
        return $pageBox->get('administration');

    }

    protected function pageData(): void
    {
        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'));

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=administration');
    }

}
