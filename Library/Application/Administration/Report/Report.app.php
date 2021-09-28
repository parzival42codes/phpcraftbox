<?php declare(strict_types=1);

/**
 * Blog View
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 4
 * @modul    language_path_de_DE /Administration/Meldung
 * @modul    language_name_de_DE Meldungen
 * @modul    language_path_en_US /Administration/Report
 * @modul    language_name_en_US Reports
 */
class ApplicationAdministrationReport_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);



        $template->parse();
        return $template->get();

    }

    protected function pageData($title): void
    {
        $className = Core::getRootClass(__CLASS__);

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');

        $page->setPageTitle($title);
        $page->setPageDescription(ContainerFactoryLanguage::get('/' . $className . '/meta/description'));

        $breadcrumb = $page->getBreadcrumb();

        $container = Container::DIC();
        /** @var ContainerFactoryRouter $router */
        $router = $container::get(ContainerFactoryRouter::class);

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationBlog/breadcrumb'),
                                       'index.php?application=ApplicationBlog');
        $breadcrumb->addBreadcrumbItem($title,
                                       $router->getUrlReadable());

        $menu = $this->getMenu();
        $menu->setMenuClassMain($this->___getRootClass());

    }

}
