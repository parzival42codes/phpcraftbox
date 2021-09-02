<?php declare(strict_types=1);

/**
 * UserMessageNew
 *
 * UserMessageNew
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 2,3,4
 * @modul    language_path_de_DE User
 * @modul    language_name_de_DE Nachricht
 * @modul    language_path_en_US User
 * @modul    language_name_en_US Message
 */
class ApplicationUserMessageNew_app extends Application_abstract
{
    public function setContent(): string
    {
        $this->pageData();

        if (!ApplicationUserMessage_app::checkAccessConfig()) {
            return '';
        }

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        $this->___getRootClass(),
                                        'default,mail');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     $this->___getRootClass(),
                                     'newMessage');

        /** @var ContainerFactoryRouter $router */
        $router    = Container::getInstance('ContainerFactoryRouter');
        $userGiven = (($router->getParameter('userid') !== null) ? (int)$router->getParameter('userid') : null);

        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');

        /** @var ContainerFactoryUser_crud $userSource */
        $userSource = Container::get('ContainerFactoryUser_crud');
        $userSource->setCrudId((int)$user->getUserId());
        $userSource->findById(true);

        /** @var ContainerFactoryUser_crud $userTarget */
        $userTarget = Container::get('ContainerFactoryUser_crud');
        if ($userGiven !== null) {
            $userTarget->setCrudId($userGiven);
            $userTarget->findById();
        }
        if ($userGiven === 0) {
            $userTarget->setCrudId(0);
            $userTarget->setCrudUsername(ContainerFactoryLanguage::get('/ApplicationUserMessage/target/admin'));
        }

        if (!ApplicationUserMessage_app::checkAccessUser($userGiven)) {
            return '';
        }


        $formHelperResponse = $formHelper->getResponse();
        if (
        $formHelperResponse->isHasResponse()
        ) {
            if (
                $this->formResponse($formHelper,
                                    $userSource,
                                    $userTarget,
                                    $templateCache) === true
            ) {
                return '';
            }
        }

        $formHelper->addFormElement('plainSource',
                                    'plain',
                                    [],
                                    [
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $userSource->getCrudUsername()
                                        ],
                                    ]);

        $formHelper->addFormElement('plainTarget',
                                    'plain',
                                    [],
                                    [
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyDefault',
                                            $userTarget->getCrudUsername()
                                        ],
                                    ]);

        $template->assign('newMessageUser',
                          $formHelper->getElements(true));

        $formTitleModify = [
            'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
        ];
        if ($userGiven === null) {
            $formTitleModify[] = 'ContainerExtensionTemplateParseCreateFormModifyDisabled';
        }

        $formHelper->addFormElement('title',
                                    'text',
                                    [],
                                    $formTitleModify);

        $formMessageModify = [
            'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
        ];
        if ($userGiven === null) {
            $formMessageModify[] = 'ContainerExtensionTemplateParseCreateFormModifyDisabled';
        }

        $formHelper->addFormElement('message',
                                    'textarea',
                                    [],
                                    $formMessageModify);

        if ($userGiven === null) {
            /** @var ContainerFactoryLog_crud_notification $crud */
            $crud = Container::get('ContainerFactoryLog_crud_notification');
            $crud->setCrudMessage(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/notification/empty'));
            $crud->setCrudClass(__CLASS__);
            $crud->setCrudCssClass('simpleModifyError');
            $crud->setCrudClassIdent((string)$userGiven);

            /** @var ContainerIndexPage $page */
            $page = Container::getInstance('ContainerIndexPage');
            $page->addNotification($crud);
        }

        $template->assign('newMessage',
                          $formHelper->getElements());
        $template->assign('newMessageHeader',
                          $formHelper->getHeader());
        $template->assign('newMessageFooter',
                          $formHelper->getFooter());

        $template->parse();
        return $template->get();


    }

    public function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper, ContainerFactoryUser_crud $userSource, ContainerFactoryUser_crud $userTarget, ContainerExtensionTemplateLoad_cache_template $templateCache): bool
    {
        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = $formHelper->getResponse();
        if (!$response->hasError()) {
            /** @var ApplicationUserMessage_crud $crudMessage */
            $crudMessage = Container::get('ApplicationUserMessage_crud');

            $crudMessage->setCrudSource($userSource->getCrudId());
            $crudMessage->setCrudTarget($userTarget->getCrudId());
            $crudMessage->setCrudTitle($response->get('title'));
            $crudMessage->setCrudMessage($response->get('message'));
            $crudMessage->insert();

            /** @var ContainerFactoryUser_crud $crudUser */
            $crudUser = Container::get('ContainerFactoryUser_crud');
            $crudUser->setCrudId($userTarget->getCrudId());
            $crudUser->findById(true);

            $dateTine = new DateTime();;

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->getCacheContent()['mail']);
            $template->assign('from',
                              $crudUser->getCrudUsername());
            $template->assign('date',
                              $dateTine->format((string)Config::get('/environment/datetime/format')));
            $template->assign('message',
                              $response->get('message'));

            $template->parse();

            /** @var ContainerFactoryMail $mailer */
            $mailer = Container::get('ContainerFactoryMail');
            $mailer->addAddress($crudUser->getCrudEmail());
            $mailer->setSubject(sprintf(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/mail/subject'),
                                        Config::get('/CoreIndex/page/title')));

            $mailer->setBody($template->get());

            $mailer->send();


            /** @var ContainerFactoryLog_crud_notification $crud */
            $crud = Container::get('ContainerFactoryLog_crud_notification');
            $crud->setCrudMessage(sprintf(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/notification/success'),
                                          $userTarget->getCrudUsername()));
            $crud->setCrudClass(__CLASS__);
            $crud->setCrudCssClass('simpleModifySuccess');
            $crud->setCrudClassIdent((string)$userSource->getCrudId());

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

        $router = Container::get('ContainerFactoryRouter');
        $router->analyzeUrl('index.php?application=' . $this->___getRootClass() . '');

        $breadcrumb = $page->getBreadcrumb();

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationUserMessage/meta/title'),
                                       'index.php?application=ApplicationUserMessage');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/meta/title'),
                                       'index.php?application=' . $this->___getRootClass());

        $menu = $this->getMenu();
        $menu->setMenuClassMain('ApplicationUserMessage');

    }
}
