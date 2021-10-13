<?php declare(strict_types=1);

class ApplicationAdministrationModul_app extends ApplicationAdministration_abstract
{
    /**
     * @var string
     */
    protected string $crudMain = 'ContainerFactoryModul_crud';

    /**
     * @Assert assertTrue FooBar
     *
     * @return string
     * @throws DetailedException
     */
    public function setContent(): string
    {
        $this->pageData();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default,dialog,table.button');

        $customClasses = Custom::getCustomCLasses();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['default']);

        $statusList = [
            'Active',
            'InActive',
            'UnInstall',
        ];

        $statusListContent = [];
        $counter           = 0;

        foreach ($statusList as $statusListValue) {

            foreach ($customClasses[$statusListValue] as $customClassesItem) {
                ++$counter;

                CoreDebugLog::addLog('Modul Custom Class:: ' . $customClassesItem['class'],
                                     $statusListValue);

                /** @var ContainerExtensionTemplate $templateBtn */
                $templateBtn = Container::get('ContainerExtensionTemplate');
                $templateBtn->set($templateCache->get()['table.button']);

                $templateBtn->assign('status',
                                     $statusListValue);
                $templateBtn->assign('class',
                                     $customClassesItem['class']);

                $templateBtnUnInstall = clone $templateBtn;
                $templateBtnInActive  = clone $templateBtn;

                $templateBtnUnInstall->assign('disabled',
                                              '');
                $templateBtnUnInstall->assign('disabledClass',
                                              '');
                $templateBtnInActive->assign('disabled',
                                             '');
                $templateBtnInActive->assign('disabledClass',
                                             '');

                if ($statusListValue === 'UnInstall') {

                    $templateBtnUnInstall->assign('action',
                                                  'install');
                    $templateBtnUnInstall->assign('checked',
                                                  '');
                    $templateBtnUnInstall->assign('button',
                                                  'un_install');
                    $templateBtnUnInstall->assign('hash',
                        ('uninstall' . $counter));

                    $templateBtnUnInstall->parse();
                    $customClassesItem['UnInstall'] = $templateBtnUnInstall->get();

                    $templateBtnInActive->assign('action',
                                                 'deactivate');
                    $templateBtnInActive->assign('checked',
                                                 '');
                    $templateBtnInActive->assign('button',
                                                 'in_active');
                    $templateBtnInActive->assign('hash',
                        ('inactive' . $counter));

                    $templateBtnInActive->assign('disabled',
                                                  'disabled');
                    $templateBtnInActive->assign('disabledClass',
                                                  'labelClosed');

                    $templateBtnInActive->parse();
                    $customClassesItem['InActive'] = $templateBtnInActive->get();
                }

                if ($statusListValue === 'InActive') {

                    $templateBtnUnInstall->assign('action',
                                                  'uninstall');
                    $templateBtnUnInstall->assign('checked',
                                                  'checked');
                    $templateBtnUnInstall->assign('button',
                                                  'un_install');
                    $templateBtn->assign('hash',
                        ('uninstall' . $counter));

                    $templateBtnUnInstall->parse();
                    $customClassesItem['UnInstall'] = $templateBtnUnInstall->get();

                    $templateBtnInActive->assign('action',
                                                 'activate');
                    $templateBtnInActive->assign('checked',
                                                 '');
                    $templateBtnInActive->assign('button',
                                                 'in_active');
                    $templateBtn->assign('hash',
                        ('inactive' . $counter));

                    $templateBtnInActive->parse();

                    $customClassesItem['InActive'] = $templateBtnInActive->get();

                }

                if ($statusListValue === 'Active') {

                    $templateBtnUnInstall->assign('action',
                                                  'uninstall');
                    $templateBtnUnInstall->assign('checked',
                                                  'checked');
                    $templateBtnUnInstall->assign('button',
                                                  'un_install');
                    $templateBtnUnInstall->assign('disabled',
                                                  'disabled');
                    $templateBtnUnInstall->assign('disabledClass',
                                                  'labelClosed');
                    $templateBtn->assign('hash',
                        ('uninstall' . $counter));

                    $templateBtnUnInstall->parse();
                    $customClassesItem['UnInstall'] = $templateBtnUnInstall->get();

                    $templateBtnInActive->assign('action',
                                                 'deactivate');
                    $templateBtnInActive->assign('checked',
                                                 'checked');
                    $templateBtnInActive->assign('button',
                                                 'in_active');
                    $templateBtn->assign('hash',
                        ('inactive' . $counter));

                    $templateBtnInActive->parse();

                    $customClassesItem['InActive'] = $templateBtnInActive->get();

                }

                $statusListContent[] = $customClassesItem;
            }

        }

        $template->assign('ModulTable_TableData',
                          $statusListContent);

        /** @var ContainerExtensionTemplateParseHelperDialog $templateDialog */
        $templateDialog = Container::get('ContainerExtensionTemplateParseHelperDialog',
                                         'ApplicationAdministrationModul_dialog_cgui_messages');
        $templateDialog->setBody($templateCache->get()['dialog']);
        $templateDialog->setFooter();

        $templateDialog->create();

        $generatedSecureKey = password_hash((string)Config::get('/environment/secret/cgui'),
                                            PASSWORD_DEFAULT);

        $template->assign('generatedSecureKey',
                          $generatedSecureKey);

        $template->parse();
        return $template->get();

    }

    protected function pageData(): void
    {
        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');

        $page->setPageTitle(ContainerFactoryLanguage::get('/ApplicationAdministrationModul/breadcrumb'));

        $breadcrumb = $page->getBreadcrumb();
        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministration/breadcrumb'),
                                       'index.php?application=administration');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/ApplicationAdministrationModul/breadcrumb'),
                                       'index.php?application=ApplicationAdministrationModul');

    }

}
