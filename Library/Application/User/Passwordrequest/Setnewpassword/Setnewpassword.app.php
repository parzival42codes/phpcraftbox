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
 * @modul language_path_de_DE Benutzer/Passwort
 * @modul language_name_de_DE User/Password
 * @modul language_path_en_US Passwort setzen
 * @modul language_name_en_US Set Password
 *
 */
class ApplicationUserPasswordrequestSetnewpassword_app extends ApplicationAdministration_abstract
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
                                     'newPassword');

        /** @var ContainerFactoryRouter $router */
        $router = Container::getInstance('ContainerFactoryRouter');
        $uuid   = base64_decode($router->getParameter('ident'));

        /** @var ContainerFactoryUser_crud $crudUser */
        $crudUser = Container::get('ContainerFactoryUser_crud');
        $crudUser->setCrudPasswordRequest($uuid);
        $crudUser->findByColumn('crudPasswordRequest',
                                true);

        debugDump($crudUser);

        /** @var ContainerExtensionTemplateParseCreateFormResponse $formHelperResponse */
        $formHelperResponse = $formHelper->getResponse();
        if (
        $formHelperResponse->isHasResponse()
        ) {
            if (
            $this->formResponse($formHelper,
                                $crudUser)
            ) {
                return '';
            }
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        $formHelper->addFormElement('request',
                                    'text',
                                    [],
                                    [
                                        'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $uuid
                                        ],
                                    ]);

        $template->assign('request',
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

        $template->assign('newPassword',
                          $formHelper->getElements(true));

        $template->assign('newPasswordHeader',
                          $formHelper->getHeader());

        $template->assign('newPasswordFooter',
                          $formHelper->getFooter());

        $template->parse();
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
        $router->analyzeUrl('index.php?application=ApplicationUserPasswordrequest');

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationUser/breadcrumb'),
                                       'index.php?application=ApplicationUser');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationUserPasswordrequest/breadcrumb'),
                                       'index.php?application=ApplicationUserPasswordrequest');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/breadcrumb'),
                                       'index.php?application=' . $this->___getRootClass());

        $menu = $this->getMenu();
        $menu->setMenuClassMain('ApplicationUserPasswordrequest');

    }

    public function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper, ContainerFactoryUser_crud $crudUser): bool
    {

        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = $formHelper->getResponse();
        if (!$response->hasError()) {

            $crudUser->setCrudPassword(password_hash($response->get('password'),
                                                     PASSWORD_DEFAULT));
            $crudUser->setCrudPasswordRequest('');
            $crudUser->update();

            return true;

        }

        return false;

    }

}
