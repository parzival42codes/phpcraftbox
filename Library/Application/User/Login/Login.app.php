<?php
declare(strict_types=1);

/**
 * User Login
 *
 * User Login
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    language_name_de_DE Einloggen
 * @modul    language_name_en_US Login
 * @modul    language_path_de_DE /Benutzer
 * @modul    language_path_en_US /User
 *
 */
class ApplicationUserLogin_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {

        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = Container::get('ContainerExtensionTemplateParseCreateFormResponse',
                                   'Login');

        /** @var ContainerExtensionTemplateParseCreateFormRequest $request */
        $request = Container::get('ContainerExtensionTemplateParseCreateFormRequest',
                                  'Login');

        $this->pageData();

        /** @var ContainerFactoryRequest $requestNotification */
        $requestNotification = Container::get('ContainerFactoryRequest',
                                              ContainerFactoryRequest::REQUEST_GET,
                                              '_notification');

        if (!$requestNotification->exists()) {
            if (ContainerFactorySession::check()) {
                /** @var ContainerFactoryLog_crud_notification $crud */
                $crud = Container::get('ContainerFactoryLog_crud_notification');
                $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserLogin/notification/session/active'));
                $crud->setCrudClass(__CLASS__);
                $crud->setCrudCssClass('simpleModifyWarning');

                /** @var ContainerIndexPage $page */
                $page = Container::getInstance('ContainerIndexPage');
                $page->addNotification($crud);

                return '&nbsp';
            }
        }

        $responseResult = $this->formResponse($response);

        if (
        !is_null($responseResult)
        ) {
            /** @var ContainerIndexPage $page */
            $page = Container::getInstance('ContainerIndexPage');
            $nid  = $page->addNotification($responseResult);

            if ($responseResult->getCrudType() === $responseResult::NOTIFICATION_REQUEST) {
                /** @var ContainerFactoryRouter $router */
                $router = clone Container::getInstance('ContainerFactoryRouter');
                $router->setQuery('_notification',
                                  $nid);
                $router->setQuery('_form',
                                  null);
                $router->redirect();
            }
            if (ContainerFactorySession::check()) {
                /** @var ContainerFactoryLog_crud_notification $crud */
                $crud = Container::get('ContainerFactoryLog_crud_notification');
                $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserLogin/notification/session/active'));
                $crud->setCrudClass(__CLASS__);
                $crud->setCrudCssClass('simpleModifyWarning');

                /** @var ContainerIndexPage $page */
                $page = Container::getInstance('ContainerIndexPage');
                $page->addNotification($crud);

                return '&nbsp';
            }
        }

        if ($requestNotification->exists()) {
            return '';
        }

        /** @var ContainerExtensionTemplateParseCreateFormElementText $elementEmail */
        $elementEmail = Container::get('ContainerExtensionTemplateParseCreateFormElementText');

        $elementEmail->setValue(Config::get('/environment/install/user/email'));
        //$elementEmail->setValue($response->get('email'));
        $elementEmail->setLabel(ContainerFactoryLanguage::get('/ApplicationUserLogin/form/email/label'));
        $elementEmail->setInfo(ContainerFactoryLanguage::get('/ApplicationUserLogin/form/email/info'));
        $elementEmail->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired');
        $elementEmail->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorEmail');

        $elementEmail->setError(implode('<br/>',
                                        $response->getError('email')));

        $request->addElement('email',
                             $elementEmail);

        /** @var ContainerExtensionTemplateParseCreateFormElementPassword $elementPassword */
        $elementPassword = Container::get('ContainerExtensionTemplateParseCreateFormElementPassword');
        $elementPassword->setValue(\Config::get('/environment/install/user/password'));
        $elementPassword->setLabel(ContainerFactoryLanguage::get('/ApplicationUserLogin/form/password/label'));
        $elementPassword->setInfo(ContainerFactoryLanguage::get('/ApplicationUserLogin/form/password/info'));
        $elementPassword->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired');

        $request->addElement('password',
                             $elementPassword);

        $request->create();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);
        $template->parse();
        return $template->get();

    }

    protected function pageData(): void
    {

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationUserLogin/breadcrumb'));

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=ApplicationUserLogin');


        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationUser/breadcrumb'),
                                       'index.php?application=ApplicationUser');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationUserLogin/breadcrumb'),
                                       'index.php?application=ApplicationUserLogin');
    }

    private function formResponse(ContainerExtensionTemplateParseCreateFormResponse $response): ?ContainerFactoryLog_crud_notification
    {

        if ($response->isHasResponse()) {
            if (!$response->hasError()) {

                /** @var ContainerFactoryUser_crud $user */
                $user     = Container::get('ContainerFactoryUser_crud');
                $userFind = $user->find([
                                            'crudEmail' => $response->get('email')
                                        ]);

                /** @var ContainerFactoryUser_crud $userFound */
                $userFound = reset($userFind);

                if (empty($userFound) || empty($userFound->getCrudId())) {
                    return $this->pageNotificationPasswordError();
                }

                if ($userFound->isCrudActivated() === false) {
                    return $this->pageNotificationNotActivated();
                }

                if ($userFound->isCrudEmailCheck() === false) {
                    return $this->pageNotificationNotEMailCheck();
                }

                if (
                !password_verify($response->get('password'),
                                 $userFound->getCrudPassword())
                ) {
                    return $this->pageNotificationPasswordError();

//                if ($userFound->getCrudPasswordFailCounter() >= Config::get('/ApplicationUserLogin/login/fail/max')) {
//                    $page->addNotification(sprintf(ContainerFactoryLanguage::get('/ApplicationUserLogin/notification/error/failCounterFull'),
//                                                   Config::get('/ApplicationUserLogin/login/fail/max')),
//                                           __CLASS__,
//                                           'simpleModifyError');
//                }

                }
                else {

                    ContainerFactorySession::start(true);
                    ContainerFactorySession::set('/user/id',
                                                 $userFound->getCrudId());

                    /** @var ContainerFactoryLog_crud_notification $crud */
                    $crud = Container::get('ContainerFactoryLog_crud_notification');
                    $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserLogin/notification/login/success'));
                    $crud->setCrudClass(__CLASS__);
                    $crud->setCrudCssClass('simpleModifySuccess');
                    $crud->setCrudShowInLog($crud::SHOW_IN_LOG_NO);
                    $crud->setCrudType($crud::NOTIFICATION_REQUEST);

                    return $crud;
                }

            }
        }

        return null;
    }

    protected function pageNotificationPasswordError(): ContainerFactoryLog_crud_notification
    {
        /** @var ContainerFactoryLog_crud_notification $crud */
        $crud = Container::get('ContainerFactoryLog_crud_notification');
        $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserLogin/notification/error/notFound'));
        $crud->setCrudClass(__CLASS__);
        $crud->setCrudCssClass('simpleModifyError');
        $crud->setCrudClassIdent($crud->getCrudUserId());
        $crud->setCrudData();

        return $crud;
    }

    protected function pageNotificationNotEMailCheck(): ContainerFactoryLog_crud_notification
    {
        /** @var ContainerFactoryLog_crud_notification $crud */
        $crud = Container::get('ContainerFactoryLog_crud_notification');
        $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserLogin/notification/error/noEmailCheck'));
        $crud->setCrudClass(__CLASS__);
        $crud->setCrudCssClass('simpleModifyWarning');
        $crud->setCrudClassIdent($crud->getCrudUserId());
        $crud->setCrudData();

        return $crud;
    }

    protected function pageNotificationNotActivated(): ContainerFactoryLog_crud_notification
    {
        /** @var ContainerFactoryLog_crud_notification $crud */
        $crud = Container::get('ContainerFactoryLog_crud_notification');
        $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserLogin/notification/error/notActivated'));
        $crud->setCrudClass(__CLASS__);
        $crud->setCrudCssClass('simpleModifyError');
        $crud->setCrudClassIdent($crud->getCrudUserId());
        $crud->setCrudData();

        return $crud;
    }

}
