<?php declare(strict_types=1);

/**
 * UserMessage
 *
 * UserMessage
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 2,3,4
 * @modul    language_path_de_DE /Benutzer
 * @modul    language_name_de_DE Nachricht
 * @modul    language_path_en_US /User
 * @modul    language_name_en_US Message
 */
class ApplicationUserMessage_app extends Application_abstract
{

    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');

        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('user_messages');
        $query->select('crudId');
        $query->select('crudTitle');
        $query->select('crudMessage');
        $query->select('dataVariableCreated');

        $query->orderBy('dataVariableCreated DESC');

        $query->join([
                         'user',
                         'userSource',
                     ],
                     ['crudUsername'],
                     'userSource.crudId = user_messages.crudSource');

        $query->join([
                         'user',
                         'userTarget',
                     ],
                     ['crudUsername'],
                     'userTarget.crudId = user_messages.crudTarget');

        $query->setParameterWhere('crudSource',
                                  $user->getUserId());
        $query->whereModify('OR');
        $query->setParameterWhere('crudSource',
                                  $user->getUserId());

        $count = $query->count();

        /** @var ContainerExtensionTemplateParseCreatePaginationHelper $pagination */
        $pagination = Container::get('ContainerExtensionTemplateParseCreatePaginationHelper',
                                     'messages',
                                     $count);
        $pagination->create();

        $tableTcs = [];

        $query->setLimit($pagination->getPagesView(),
                         $pagination->getPageOffset());
        $query->construct();

        debugDump($query->getQueryParsed());

        $smtp = $query->execute();

        while ($smtpData = $smtp->fetch()) {
            $tableTcs[] = [
                'title'      => '<a href="index.php?application=' . $this->___getRootClass() . 'Reply&route=message&id=' . $smtpData['crudId'] . '" class="block">' . $smtpData['crudId'] . '</a>',
                'message'    => '<a href="index.php?application=' . $this->___getRootClass() . 'Reply&route=message&id=' . $smtpData['crudId'] . '" class="block">' . $smtpData['crudId'] . '</a>',
                'userSource' => $smtpData['userSource_crudUsername'],
                'userTarget' => $smtpData['userTarget_crudUsername'],
                'date'       => $smtpData['dataVariableCreated']
            ];
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        $template->assign('table_table',
                          $tableTcs);

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

    public static function checkAccessConfig(): bool
    {
        if (!Config::get('/ApplicationUserMessage/messages/receive')) {
            /** @var ContainerFactoryLog_crud_notification $crud */
            $crud = Container::get('ContainerFactoryLog_crud_notification');
            $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserMessage/notification/configNoMessage'));
            $crud->setCrudClass(__CLASS__);
            $crud->setCrudCssClass('simpleModifyError');

            /** @var ContainerIndexPage $page */
            $page = Container::getInstance('ContainerIndexPage');
            $page->addNotification($crud);

            return false;
        }
        else {
            return true;
        }
    }

    public static function checkAccessUser(int $user): bool
    {
        if (
        !ContainerFactoryUserConfig::get('/ApplicationUserMessage/messages/receive',
                                         $user)
        ) {
            /** @var ContainerFactoryLog_crud_notification $crud */
            $crud = Container::get('ContainerFactoryLog_crud_notification');
            $crud->setCrudMessage(ContainerFactoryLanguage::get('/ApplicationUserMessage/notification/userNoMessage'));
            $crud->setCrudClass(__CLASS__);
            $crud->setCrudCssClass('simpleModifyError');

            /** @var ContainerIndexPage $page */
            $page = Container::getInstance('ContainerIndexPage');
            $page->addNotification($crud);

            return false;
        }
        else {
            return true;
        }
    }


}
