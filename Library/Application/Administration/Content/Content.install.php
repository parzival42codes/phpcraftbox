<?php

class ApplicationAdministrationContent_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMeta();
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
        $this->readLanguageFromFile('install.impressum');
        $this->readLanguageFromFile('install.privacy');
        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  4
                              ]);

        $this->importQueryDatabaseFromCrud('ApplicationAdministrationContent_crud');
        $this->importQueryDatabaseFromCrud('ApplicationAdministrationContent_crud_index');
        $this->importQueryDatabaseFromCrud('ApplicationAdministrationContent_crud_history');

//        $this->queryDatabase($this->database());
//        $this->queryDatabase($this->databasePath());

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            'ApplicationAdministrationContent',
                                            'install.impressum');

            /** @var ApplicationAdministrationContent_crud $crud */
            $crud = Container::get('ApplicationAdministrationContent_crud');
            $crud->setCrudIdent('impressum');
            $crud->setCrudRequired('Yes');
            $crud->setCrudData('
            [de_DE]
            title = "Impressum"
            description = "Impressum"
            path = "/impressum"
            [en_US]
            title = "Impressum"
            description = "Impressum"
            path = "/impressum"
            ');
            $crud->setCrudContent($templateCache->getCacheContent()['install.impressum']);

            $progressData['message'] = $crud->insertUpdate();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ApplicationAdministrationContent_crud_index $crud */
            $crud = Container::get('ApplicationAdministrationContent_crud_index');
            $crud->setCrudContentIdent('impressum');
            $crud->createIndexFromContentIdent();

            $progressData['message'] = 'createIndexFromContentIdent, impressum';

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            'ApplicationAdministrationContent',
                                            'install.privacy');

            /** @var ApplicationAdministrationContent_crud $crud */
            $crud = Container::get('ApplicationAdministrationContent_crud');
            $crud->setCrudIdent('privacy');
            $crud->setCrudRequired('Yes');
            $crud->setCrudData('
            [de_DE]
            title = "Datenschutz"
            description = "Datenschutz"
            path = "/datenschutz"
            [en_US]
            title = "Privacy"
            description = "Privacy"
            path = "/privacy"
            ');
            $crud->setCrudContent($templateCache->getCacheContent()['install.privacy']);

            $progressData['message'] = $crud->insertUpdate();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ApplicationAdministrationContent_crud_index $crud */
            $crud = Container::get('ApplicationAdministrationContent_crud_index');
            $crud->setCrudContentIdent('privacy');
            $crud->createIndexFromContentIdent();

            $progressData['message'] = 'createIndexFromContentIdent, privacy';

            /*$after*/
        });
    }
}
