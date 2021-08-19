<?php declare(strict_types=1);

/**
 * Report
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 1,2,3,4
 * @modul    language_path_de_DE /Meldung
 * @modul    language_name_de_DE Senden
 * @modul    language_path_en_US /Report
 * @modul    language_name_en_US Send
 */
class ApplicationAdministrationReportSend_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     $this->___getRootClass(),
                                     'report');

        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        $formHelper->addFormElement('username',
                                    'text',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                    ]);

        $template->assign('form',
                          $formHelper->getElements());

        $template->assign('formHeader',
                          $formHelper->getHeader());

        $template->assign('formFooter',
                          $formHelper->getFooter());

        $template->parse();
        return $template->get();
    }

    private function pageData($title): void
    {
        $className = Core::getRootClass(__CLASS__);

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');

        $page->setPageTitle($title);
        $page->setPageDescription(ContainerFactoryLanguage::get('/' . $className . '/meta/description'));

        $breadcrumb = $page->getBreadcrumb();

        $container = Container::DIC();
        /** @var ContainerFactoryRouter $router */
        $router = $container->getDIC('/Router');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationBlog/breadcrumb'),
                                       'index.php?application=ApplicationBlog');
        $breadcrumb->addBreadcrumbItem($title,
                                       $router->getUrlReadable());

        $menu = $this->getMenu();
        $menu->setMenuClassMain($this->___getRootClass());

    }

}
