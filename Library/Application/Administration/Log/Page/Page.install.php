<?php

class ApplicationAdministrationLogPage_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importLanguage();
        $this->importRoute();
        $this->importMenu();
        $this->importMeta();
        $this->readLanguageFromFile('default');
        $this->readLanguageFromFile('install.button.overview');

        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  4
                              ]);

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            'ApplicationAdministrationLogNotification',
                                            'install.button.overview');

            /** @var ContainerExtensionTemplateParseInsertPositions_crud $crud */
            $crud = Container::get('ContainerExtensionTemplateParseInsertPositions_crud');
            $crud->setCrudClass('ApplicationAdministrationLogNotification');
            $crud->setCrudPosition('/administration/dashboard/buttons');
            $crud->setCrudContent($templateCache->get()['install.button.overview']);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

    }

}
