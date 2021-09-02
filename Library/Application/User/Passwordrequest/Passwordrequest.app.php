<?php declare(strict_types=1);


/**
 * Password Request
 *
 * Password Request
 *
 * @modul author Stefan Schlombs
 * @modul version 1.0.0
 * @modul versionRequiredSystem 1.0.0
 * @modul groupAccess 1
 * @modul language_path_de_DE Benutzer
 * @modul language_name_de_DE User
 * @modul language_path_en_US Passwort zurücksetzen
 * @modul language_name_en_US Password Request
 *
 */
class ApplicationUserPasswordrequest_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        $this->___getRootClass(),
                                        'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     $this->___getRootClass(),
                                     'passwordRequest');

        /** @var ContainerExtensionTemplateParseCreateFormResponse $formHelperResponse */
        $formHelperResponse = $formHelper->getResponse();
        if (
        $formHelperResponse->isHasResponse()
        ) {
            if ($this->formResponse($formHelper)) {


                /** @var ContainerFactoryLog_crud_notification $crud */
                $crud = Container::get('ContainerFactoryLog_crud_notification');
                $crud->setCrudMessage(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/notification/password/new'));
                $crud->setCrudClass(__CLASS__);
                $crud->setCrudCssClass('simpleModifySuccess');
                $crud->setCrudClassIdent($crud->getCrudUserId());
                $crud->setCrudData();

                /** @var ContainerIndexPage $page */
                $page = Container::getInstance('ContainerIndexPage');
                $page->addNotification($crud);

                return '';
            }
        }


        $formHelper->addFormElement('email',
                                    'email',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorEmail',
                                        [
                                            'ApplicationUserPasswordrequest_app',
                                            'mailExistsCheck'
                                        ],
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            'stefan.schlombs@gmail.com'
                                        ],
                                    ]);

        $formHelper->addFormElement('verify',
                                    'checkbox',
                                    [
                                        [
                                            1 => ContainerFactoryLanguage::get('/ApplicationUserPasswordrequest/form/checkbox/verify')
                                        ],
                                    ],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                    ]);

        $template->assign('passwordRecover',
                          $formHelper->getElements());

        $template->assign('passwordRecoverHeader',
                          $formHelper->getHeader());

        $template->assign('passwordRecoverFooter',
                          $formHelper->getFooter());

        $template->parse();
        return $template->get();

    }

    protected function pageData(): void
    {
        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/breadcrumb'));

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=' . $this->___getRootClass());

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationUser/breadcrumb'),
                                       'index.php?application=ApplicationUser');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/breadcrumb'),
                                       'index.php?application=' . $this->___getRootClass());

        $menu = $this->getMenu();
        $menu->setMenuClassMain($this->___getRootClass());
    }

    public static function mailExistsCheck($element): void
    {
        if ($element instanceof ContainerExtensionTemplateParseCreateFormElement_abstract) {

        }
        elseif ($element instanceof ContainerExtensionTemplateParseCreateFormResponse) {
            $userEMail = func_get_arg(4);

            /** @var ContainerFactoryUser_crud $crud */
            $crud = Container::get('ContainerFactoryUser_crud');

            if (
                $crud->count([
                                 'crudEmail' => $userEMail
                             ]) == 0
            ) {


                $element->setError(func_get_arg(3),
                                   ContainerFactoryLanguage::get('/ApplicationUserPasswordrequest/error/emailExists',
                                                                 [
                                                                     'de_DE' => 'Ein Benutzer mit der von Ihnen angegebenen E-Mail Adresse existiert nicht !',
                                                                     'en_US' => 'An User with the email address you specified not exists!',
                                                                 ]));
            }
        }
    }

    public function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper): bool
    {

        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = $formHelper->getResponse();
        if (!$response->hasError()) {

            /** @var ContainerFactoryUser_crud $crud */
            $crud = Container::get('ContainerFactoryUser_crud');
            $crud->setCrudEmail($response->get('email'));
            $crud->findByColumn('crudEmail',
                                true);

            /** @var ContainerFactoryUuid $uuid */
            $uuid        = Container::get('ContainerFactoryUuid');
            $uuidCreated = $uuid->create();

            $crud->setCrudPasswordRequest($uuidCreated);
            $crud->update();

            /** @var ContainerFactoryRouter $router */
            $router = Container::get('ContainerFactoryRouter');
            $router->setApplication('ApplicationUserPasswordrequestSetnewpassword');
            $router->setRoute('request');
            $router->setParameter('ident',
                                  base64_encode($uuidCreated));

            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            $this->___getRootClass(),
                                            'request.mail');

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->getCacheContent()['request.mail']);
            $template->assign('pageTitle',
                              Config::get('/CoreIndex/page/title'));
            $template->assign('url',
                              $router->getUrlReadable());

            $template->parse();

            /** @var ContainerFactoryMail $mailer */
            $mailer = Container::get('ContainerFactoryMail');
            $mailer->addAddress($crud->getCrudEmail());
            $mailer->setSubject(sprintf(ContainerFactoryLanguage::get('/ApplicationUserPasswordrequest/mail/password/request/header'),
                                        Config::get('/CoreIndex/page/title')));

            $mailer->setBody($template->get());

            $mailer->send();

            return true;

        }

        return false;

    }

}
