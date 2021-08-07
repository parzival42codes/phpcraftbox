<?php

class ContainerFactoryModulInstall_console extends Console_abstract
{

    protected array $module
        = [
            'Config',
            'Event',
            'ContainerExtensionDocumentation',
            'ContainerFactoryModul',
            'ContainerFactoryLanguage',
            'CoreAutoload',
            //  'Data',
            'ContainerFactoryFile',
            'Core',
            'CoreErrorhandler',
            'CorePdo',
            'CoreDebug',
            'CoreDebugDump',
            'CoreDebugDumpObject',
            'ContainerFactoryRouter',
            'Style',
            'ResourcesIcons',
            'Custom',
            'Container',
            'ContainerFactoryGeneralmemory',
            'ContainerFactoryUser',
            'ContainerFactoryUserConfig',
            'ContainerFactoryUserGroup',
            'ContainerFactoryUserGroupAccess',
            'ContainerFactoryCrypt',
            'ContainerFactoryToken',
            'ContainerFactoryZip',
            'CoreDebugProfiler',
            //            'CoreDebugAssert',
            'CoreIndex',
            'ContainerExtensionCache',
            'ContainerIndexPage',
            'ContainerIndexPageBox',
            'ContainerIndexPageBreadcrumb',
            'ContainerIndexTab',
            'ContainerIndexJavascriptCopytoclipboard',
            'ContainerExtensionTemplate',
            'ContainerExtensionTemplateLoad',
            'ContainerExtensionTemplateParse',
            'ContainerExtensionTemplateParseCreateForm',
            'ContainerExtensionTemplateParseCreateFormResponse',
            'ContainerExtensionTemplateParseCreateFormElement',
            'ContainerExtensionTemplateParseCreateFormElementFooter',
            'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
            'ContainerExtensionTemplateParseCreateFormModifyValidatorEmail',
            'ContainerExtensionTemplateParseCreateFormModifyOptional',
            'ContainerExtensionTemplateParseCreateFormModifyValidatorEqual',
            'ContainerExtensionTemplateParseCreateFormModifyValidatorPassword',
            'ContainerExtensionTemplateParseCreateTableTable',
            'ContainerExtensionTemplateParseCreateTableTableYesno',
            'ContainerExtensionTemplateParseCreatePaginationHelper',
            'ContainerExtensionTemplateParseCreateFilterHelper',
            'ContainerExtensionTemplateParseInsertPositions',
            'ContainerExtensionTemplateTagTableTable',
            'ContainerExtensionTemplateTagTableTableYesno',
            'ContainerExtensionAjax',
            'ContainerExtensionTemplateParseHelperDialog',
            'ContainerExtensionTemplateParseHelperTooltip',
            'ContainerFactoryRequest',
            'ContainerFactoryMenu',
            'ContainerFactoryLog',
            'ContainerFactoryCgui',
            'ContainerFactoryDatabaseQuery',
            'ContainerHelperViewDifference',
            'Application',
            'ApplicationIndex',
            'ApplicationSearch',
            'ApplicationUser',
            'ApplicationUserProfil',
            'ApplicationUserMessage',
            'ApplicationUserMessageReply',
            'ApplicationUserMessageNew',
            'ApplicationUserLogin',
            'ApplicationUserLogout',
            'ApplicationUserConfig',
            'ApplicationUserEmailcheck',
            'ApplicationUserPasswordrequest',
            'ApplicationUserPasswordrequestSetnewpassword',
            'ApplicationUserRegister',
            'ApplicationAdministrationModul',
            'ApplicationAdministrationModulOverview',
            'ApplicationAdministrationUser',
            'ApplicationAdministrationUserEdit',
            'ApplicationAdministrationUserGroup',
            'ApplicationAdministrationUserGroupEdit',
            'ApplicationAdministrationBox',
            'ApplicationAdministrationBoxEdit',
            'ApplicationAdministrationLogError',
            'ApplicationAdministrationLogNotification',
            'ApplicationAdministrationLogPage',
            'ApplicationAdministrationContent',
            'ApplicationAdministrationContentEdit',
            'ApplicationAdministrationContentView',
            'ApplicationAdministrationConfig',
            'ApplicationAdministrationLanguage',
            'ApplicationIndexErrorNotfound',
            'ApplicationAdministration',
            'ContainerFactoryComment',
            'ContainerExternResources',
            'ThrirdpartyJavascriptJquery',
            'ThrirdpartyJavascriptParsley',
            'ContainerFactoryModulInstall',
        ];

    protected array $cliParameter
        = [
            '--install' => 'Modul installieren',
        ];


    public function prepareInstall()
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__,
                                true,
                                ContainerFactoryDatabaseQuery::MODE_OTHER,
                                false);

        $dropTables = [
            'index_router',
            'config',
            'custom',
            'debug_statistic',
            'event_attach',
            'event_trigger',
            'user',
            'user_emailcheck',
            'user_config',
            'user_config_user',
            'user_group',
            'user_group_access',
            'user_group_to_access',
            'form_response',
            'index_module',
            'language',
            'menu',
            'page_box',
            //            'storage',
            'task_index',
            'documentation',
            'token',
            'log_notification',
            'template_positions',
            'content',
            'content_index',
            'content_history',
            'general_memory',
            'custom_developer_skeleton',
        ];

        $queryString = 'DROP TABLE IF EXISTS `' . implode('`,`',
                                                          $dropTables) . '`;';
        $query->query($queryString);
        $query->construct();

        $query->execute();

//   eol(true);

        $dropTables = [
            'cache' => [
                [
                    'cache'
                ]
            ],
        ];

        foreach ($dropTables as $dropTablesName => $table) {
            foreach ($table as $dropTablesList) {
                foreach ($dropTablesList as $dropTablesItem) {
                    if (
                        ContainerFactoryDatabaseEngineSqlite::tableIsInDatabase($dropTablesName,
                                                                                $dropTablesName) === true
                    ) {
                        /** @var ContainerFactoryDatabaseQuery $query */
                        $query       = Container::get('ContainerFactoryDatabaseQuery',
                                                      __METHOD__,
                                                      $dropTablesName,
                                                      ContainerFactoryDatabaseQuery::MODE_OTHER);
                        $queryString = ' DELETE FROM "' . $dropTablesItem . '";';
                        $query->query($queryString);
                        $query->construct();
                        $query->execute();

                    }
                }
            }
        }

        foreach ($this->module as $parameterItem) {
            /** @var ContainerFactoryModulInstall_abstract $installModule */

            $installModule = Container::get($parameterItem . '_install',
                                            $this);
            $this->setProgressIdentify($parameterItem);
            $installModule->install();

//            d($this->getClassConstructor());
        }

        $iniCustomFile = CMS_ROOT . 'Custom/Custom.load.ini';
        if (is_file($iniCustomFile)) {
            $iniData = parse_ini_file($iniCustomFile);
        }

        if (isset($iniData['Custom']) && is_array($iniData['Custom'])) {

            foreach ($iniData['Custom'] as $toInstallItem) {

                $requestClassContainer = Container::get($toInstallItem . '_custom');

                $dependencies   = $requestClassContainer->getDependencies();
                $dependencies[] = 'ContainerFactoryModulInstall';

                foreach ($dependencies as $dependency) {

                    /** @var ContainerFactoryModulInstall_abstract $installModule */
                    $installModule = Container::get($dependency . '_install',
                                                    $this);
                    $this->setProgressIdentify($dependency);
                    $installModule->install();
                    $installModule->activate();
                }
            }

            /** @var Custom_install $installModule */
            $installModule = Container::get('Custom_install',
                                            $this);

            $installModule->collectCustomClasses();

            foreach ($iniData['Custom'] as $toInstallItem) {
                $this->setProgressIdentify('isCustom_' . $toInstallItem);
                $installModule->isCustom($toInstallItem);
            }
        }

        $installModule = new ConsoleCustom_install($this);
        $installModule->install();
    }

}
