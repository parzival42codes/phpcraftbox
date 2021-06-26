<?php declare(strict_types=1);

class ApplicationUserLogout_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerFactoryRequest $requestNotification */
        $requestNotification = Container::get('ContainerFactoryRequest',
                                              ContainerFactoryRequest::REQUEST_GET,
                                              '_notification');

        if (!$requestNotification->exists()) {

            if (ContainerFactorySession::check()) {
                ContainerFactorySession::destroy();

                /** @var ContainerFactoryLog_crud_notification $crud */
                $crud = Container::get('ContainerFactoryLog_crud_notification');
                $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserLogout/notification/logout/success'));
                $crud->setCrudClass(__CLASS__);
                $crud->setCrudCssClass('simpleModifySuccess');
                $crud->setCrudShowInLog($crud::SHOW_IN_LOG_NO);
                $crud->setCrudType($crud::NOTIFICATION_REQUEST);

                /** @var ContainerIndexPage $page */
                $page = Container::getInstance('ContainerIndexPage');
                $nid  = $page->addNotification($crud);

                /** @var ContainerFactoryRouter $router */
                $router = clone Container::getInstance('ContainerFactoryRouter');
                $router->setQuery('_notification',
                                  $nid);
                $router->setQuery('_form',
                                  null);
                $router->redirect();
                return '';
            }
            else {
                /** @var ContainerFactoryLog_crud_notification $crud */
                $crud = Container::get('ContainerFactoryLog_crud_notification');
                $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserLogout/notification/logout/noSession'));
                $crud->setCrudClass(__CLASS__);
                $crud->setCrudCssClass('simpleModifyWarning');

                /** @var ContainerIndexPage $page */
                $page = Container::getInstance('ContainerIndexPage');
                $page->addNotification($crud);
                return '';
            }
        }

        return '';

    }

    protected function pageData(): void
    {
        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/breadcrumb'));

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=ApplicationUserLogout');


        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationUser/breadcrumb'),
                                       'index.php?application=ApplicationUser');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/breadcrumb'),
                                       'index.php?application=ApplicationUserLogin');
    }

}
