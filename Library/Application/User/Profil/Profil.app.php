<?php declare(strict_types=1);

/**
 * UserProfil
 *
 * UserProfil
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 1,2,3,4
 * @modul    language_path_de_DE User
 * @modul    language_name_de_DE Profil
 * @modul    language_path_en_US User
 * @modul    language_name_en_US Profil
 */
class ApplicationUserProfil_app extends Application_abstract
{
    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');


        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['default']);

        $template->parse();
        return $template->get();
    }

    public function pageData(): void
    {
        $thisClassName = Core::getRootClass(__CLASS__);

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/meta/title'));
        $page->setPageDescription(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/meta/description'));

        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=' . $this->___getRootClass() . '');

        $breadcrumb = $page->getBreadcrumb();

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/meta/title'),
                                       'index.php?application=' . $this->___getRootClass());

        $menu = $this->getMenu();
        $menu->setMenuClassMain($thisClassName);

    }
}
