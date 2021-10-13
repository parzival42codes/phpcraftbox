<?php declare(strict_types=1);

/**
 * User Edit
 *
 * User Edit
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 1
 * @modul    language_name_de_DE Registration
 * @modul    language_name_en_US Registration
 * @modul    language_path_de_DE /Benutzer
 * @modul    language_path_en_US /User
 */
class ApplicationUserRegister_app extends Application_abstract
{

    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        $this->___getRootClass(),
                                        'default');

        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     $this->___getRootClass(),
                                     'register');

        /** @var ContainerExtensionTemplateParseCreateFormResponse $formHelperResponse */
        $formHelperResponse = $formHelper->getResponse();
        if (
        $formHelperResponse->isHasResponse()
        ) {
            if ($this->formResponse($formHelper)) {
                return '';
            }
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['default']);

        $formHelper->addFormElement('username',
                                    'text',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ApplicationUserRegister_app',
                                            'userExistsCheck'
                                        ],
                                    ]);

        $formHelper->addFormElement('email',
                                    'email',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorEmail',
                                        [
                                            'ApplicationUserRegister_app',
                                            'mailExistsCheck'
                                        ],
                                    ]);

        $template->assign('register',
                          $formHelper->getElements());

        $formHelper->addFormElement('password',
                                    'password',
                                    [],
                                    [
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyValidatorPassword',
                                            [
//                                                'uppercase' => true,
//                                                'lowercase' => true,
//                                                'spezial'   => true,
//                                                'number'    => true,
                                            ]
                                        ],
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                    ]);

        $passwordElement = $formHelper->getElement('password');

        $formHelper->addFormElement('passwordVerify',
                                    'password',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyValidatorEqual',
                                            [
                                                'password',
                                                $passwordElement->getLabel(),

                                            ]
                                        ]
                                    ]);

        $template->assign('registerPassword',
                          $formHelper->getElements(true));

        $template->assign('registerHeader',
                          $formHelper->getHeader());

        $template->assign('registerFooter',
                          $formHelper->getFooter());

        $template->parse();
        return $template->get();


    }

    public function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper): bool
    {

        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = $formHelper->getResponse();
        if (!$response->hasError()) {

            /** @var ContainerFactoryUser_crud $crud */
            $crud = Container::get('ContainerFactoryUser_crud');

            $crud->setCrudEmail($response->get('email'));
            $crud->setCrudPassword(password_hash($response->get('password'),
                                                 PASSWORD_DEFAULT));

            $crud->setCrudUserGroupId(2);
            $crud->insert();

            /** @var ApplicationUserEmailcheck $emailCheck */
            $emailCheck = Container::get('ApplicationUserEmailcheck');

            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            $this->___getRootClass(),
                                            'register.mail');

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->get()['register.mail']);
            $template->assign('pageTitle',
                              Config::get('/CoreIndex/page/title'));

            $template->parse();

            $emailCheck->create($response->get('email'),
                                sprintf(ContainerFactoryLanguage::get('/ApplicationUserRegister/notification/registered',
                                                                      [
                                                                          'de_DE' => 'Ihre Registrierung bei %s.',
                                                                          'en_US' => 'Your registration at %s',
                                                                      ]),
                                        Config::get('/CoreIndex/page/title')),
                                $template->get(),
                                self::class,
                                'doActivateUser',
                                [
                                    'userID' => $crud->getCrudId(),
                                ]);

            /** @var ContainerFactoryLog_crud_notification $crud */
            $crud = Container::get('ContainerFactoryLog_crud_notification');
            $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserRegister/notification/registered',
                                                                [
                                                                    'de_DE' => 'Ihre Registrierung wurde ausgeführt.<br />Bitte bestätigen SIe noch Ihre E-Mail Adresse um SIe abzuschließen.',
                                                                    'en_US' => 'Your registration has been carried out.<br />Please confirm your email address to complete.',
                                                                ]));
            $crud->setCrudClass(__CLASS__);
            $crud->setCrudCssClass('simpleModifySuccess');
            $crud->setCrudClassIdent($crud->getCrudUserId());
            $crud->setCrudData();

            /** @var ContainerIndexPage $page */
            $page = Container::getInstance('ContainerIndexPage');
            $page->addNotification($crud);

            return true;

        }

        return false;

    }

    public function pageData(): void
    {
        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->setPageTitle(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/meta/title'));
        $page->setPageDescription(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/meta/description'));

        /** @var ContainerFactoryRouter $router */
        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=' . $this->___getRootClass() . '');



        $breadcrumb = $page->getBreadcrumb();

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationUser/breadcrumb'),
                                       'index.php?application=ApplicationUser');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/meta/title'),
                                       'index.php?application=ApplicationUserRegister');

        /** @var ContainerFactoryMenu $menu */
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
                             ]) > 0
            ) {


                $element->setError(func_get_arg(3),
                                   ContainerFactoryLanguage::get('/ApplicationUserRegister_app/decorator/error/emailExists',
                                                                 [
                                                                     'de_DE' => 'Ein Benutzer mit der von Ihnen angegebenen E-Mail Adresse existiert bereits !',
                                                                     'en_US' => 'An User with the email address you specified already exists!',
                                                                 ]));
            }
        }
    }

    public static function userExistsCheck($element): void
    {
        if ($element instanceof ContainerExtensionTemplateParseCreateFormElement_abstract) {

        }
        elseif ($element instanceof ContainerExtensionTemplateParseCreateFormResponse) {
            $userName = func_get_arg(4);

            /** @var ContainerFactoryUser_crud $crud */
            $crud = Container::get('ContainerFactoryUser_crud');

            if (
                $crud->count([
                                 'crudUsername' => $userName
                             ]) > 0
            ) {

                $element->setError(func_get_arg(3),
                                   ContainerFactoryLanguage::get('/ApplicationUserRegister_app/error/emailExists',
                                                                 [
                                                                     'de_DE' => 'Ein Benutzer mit dem von Ihnen angegebenen Benutzernamen existiert bereits !',
                                                                     'en_US' => 'Am User with the Username you specified already exists!',
                                                                 ]));
            }
        }
    }

    public static function doActivateUser(array $parameter): bool
    {
        /** @var ContainerFactoryUser_crud $crud */
        $crud = Container::get('ContainerFactoryUser_crud');
        $crud->setCrudId($parameter['userID']);
        $crud->findById(true);

        $crud->setCrudEmailCheck(true);
        $crud->update();

        /** @var ContainerFactoryLog_crud_notification $crud */
        $crud = Container::get('ContainerFactoryLog_crud_notification');
        $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserRegister/notification/emailchek',
                                                            [
                                                                'de_DE' => 'Vielen Dank.<br /> Ihre E_Mail Adresse wurde bestätigt.<br />Sie können sich nun einloggen.',
                                                                'en_US' => 'Thank you. <br /> Your email address has been confirmed. <br /> You can now log in.',
                                                            ]));
        $crud->setCrudClass(__CLASS__);
        $crud->setCrudCssClass('simpleModifySuccess');
        $crud->setCrudClassIdent($crud->getCrudUserId());
        $crud->setCrudData();

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->addNotification($crud);

        return true;

    }
}
