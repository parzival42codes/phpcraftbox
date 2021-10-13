<?php declare(strict_types=1);

/**
 * User
 *
 * User
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    groupAccess 2,3,4
 * @modul    versionRequiredSystem 1.0.0
 * @modul    language_name_de_DE Benutzer Dashboard
 * @modul    language_name_en_US User Dashboard
 * @modul    language_path_de_DE /Benutzer
 * @modul    language_path_en_US /User
 *
 */
class ApplicationUser_app extends ApplicationAdministration_abstract
{
    public function setContent(): string
    {
        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');

        $this->pageData();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        $this->___getRootClass(),
                                        'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['default']);

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=ApplicationUserProfil&route=profil&userid=1');

        $template->assign('userID',
                          $user->getUserId());

        $template->parse();
        return $template->get();

    }

    public function pageData(): void
    {
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
        $menu->setMenuClassMain($this->___getRootClass());

    }

}
