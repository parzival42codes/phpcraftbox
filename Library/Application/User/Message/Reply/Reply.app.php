<?php declare(strict_types=1);

/**
 * UserMessageView
 *
 * UserMessageView
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 2,3,4
 * @modul    language_path_de_DE User
 * @modul    language_name_de_DE View
 * @modul    language_path_en_US User
 * @modul    language_name_en_US View
 */
class ApplicationUserMessageReply_app extends Application_abstract
{
    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default,form,mail');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['default']);

        /** @var ContainerFactoryRouter $router */
        $router = Container::getInstance('ContainerFactoryRouter');

        /** @var ApplicationUserMessage_crud $crud */
        $crud = Container::get('ApplicationUserMessage_crud');
        $crud->setCrudId((int)$router->getParameter('id'));
        $crud->findById(true);

        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');

        if ((int)$crud->getCrudTarget() !== (int)$user->getUserId() || (int)$crud->getCrudSource() !== (int)$user->getUserId()) {
            throw new DetailedException('noAccessUserForMessage',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'target' => $crud->getCrudTarget(),
                                                'user '  => $user->getUserId(),
                                            ]
                                        ]);
        }

        /** @var ContainerFactoryUser_crud $userSource */
        $userSource = Container::get('ContainerFactoryUser_crud');
        $userSource->setCrudId((int)$crud->getCrudSource());
        $userSource->findById(true);

        if (ApplicationUserMessage_app::checkAccessUser((int)$userSource)) {

            /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
            $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                         $this->___getRootClass(),
                                         'replyMessage');

            $formHelperResponse = $formHelper->getResponse();
            if (
            $formHelperResponse->isHasResponse()
            ) {
                $this->formResponse($formHelper,
                                    $crud,
                                    $user,
                                    $templateCache);
            }

            /** @var ContainerExtensionTemplate $templateForm */
            $templateForm = Container::get('ContainerExtensionTemplate');
            $templateForm->set($templateCache->get()['form']);

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
                                                $user->getUserName()
                                            ],
                                        ]);

            $templateForm->assign('replyMessageUser',
                                  $formHelper->getElements(true));

            $formHelper->addFormElement('title',
                                        'text',
                                        [],
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired'
                                        ]);

            $formHelper->addFormElement('message',
                                        'textarea',
                                        [],
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired'
                                        ]);

            $templateForm->assign('replyMessage',
                                  $formHelper->getElements());
            $templateForm->assign('replyMessageHeader',
                                  $formHelper->getHeader());
            $templateForm->assign('replyMessageFooter',
                                  $formHelper->getFooter());

            $templateForm->parse();

            $template->assign('form',
                              $templateForm->get());

        }
        else {
            $template->assign('form',
                              '');
        }

        $template->assign('title',
                          $crud->getCrudTitle());
        $template->assign('message',
                          $crud->getCrudMessage());

        $tableTcs = [];

        $crudFindData = [
            'crudMessageId' => $crud->getCrudId(),
        ];

        /** @var ApplicationUserMessage_crud_messages $crudMessages */
        $crudMessages = Container::get('ApplicationUserMessage_crud_messages');
        $crudList     = $crudMessages->find($crudFindData,
                                            [],
                                            [
                                                'dataVariableCreated DESC'
                                            ]);

        /** @var ApplicationUserMessage_crud_messages $crudListItem */
        foreach ($crudList as $crudListItem) {
            $tableTcs[] = [
                'message' => $crudListItem->getCrudMessage(),
                'user'    => $crudListItem->getAdditionalQuerySelect('user_crudUsername'),
                'date'    => $crudListItem->getDataVariableCreated(),
            ];
        }

        $template->assign('table_table',
                          $tableTcs);

        $template->parse();
        return $template->get();
    }

    public function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper, ApplicationUserMessage_crud $crud, ContainerFactoryUser $user, ContainerExtensionTemplateLoad_cache_template $templateCache): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = $formHelper->getResponse();
        if (!$response->hasError()) {
            /** @var ApplicationUserMessage_crud_messages $crudMessage */
            $crudMessage = Container::get('ApplicationUserMessage_crud_messages');

            $crudMessage->setCrudMessageId($crud->getCrudId());
            $crudMessage->setCrudUser($user->getUserId());
            $crudMessage->setCrudMessage($response->get('message'));
            $crudMessage->insert();

            $userName  = null;
            $userEmail = null;
            if ($user->getUserId() == $crud->getCrudSource()) {
                $userName  = $crud->getAdditionalQuerySelect('userSource_crudUsername');
                $userEmail = $crud->getAdditionalQuerySelect('userSource_crudEmail');
            }
            elseif ($user->getUserId() == $crud->getCrudTarget()) {
                $userName  = $crud->getAdditionalQuerySelect('userTarget_crudUsername');
                $userEmail = $crud->getAdditionalQuerySelect('userTarget_crudEmail');
            }

            $dateTine = new DateTime();;

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->get()['mail']);
            $template->assign('from',
                              $userName);
            $template->assign('date',
                              $dateTine->format((string)Config::get('/environment/datetime/format')));
            $template->assign('message',
                              $response->get('message'));

            $template->parse();

            /** @var ContainerFactoryMail $mailer */
            $mailer = Container::get('ContainerFactoryMail');
            $mailer->addAddress($userEmail);
            $mailer->setSubject(sprintf(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/mail/subject'),
                                        Config::get('/CoreIndex/page/title')));

            $mailer->setBody($template->get());

            $mailer->send();

            /** @var ContainerFactoryLog_crud_notification $crud */
            $crud = Container::get('ContainerFactoryLog_crud_notification');
            $crud->setCrudMessage(sprintf(ContainerFactoryLanguage::get('/' . $this->___getRootClass() . '/notification/success'),
                                          $userName));
            $crud->setCrudClass(__CLASS__);
            $crud->setCrudCssClass('simpleModifySuccess');

            /** @var ContainerIndexPage $page */
            $page = Container::getInstance('ContainerIndexPage');
            $page->addNotification($crud);
        }
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
