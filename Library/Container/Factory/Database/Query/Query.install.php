<?php

class ContainerFactoryDatabaseQuery_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
          $this->importLanguage();
          $this->readLanguageFromFile('debug');

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryGeneralmemory_crud $crud */
            $crud = Container::get('ContainerFactoryGeneralmemory_crud');
            $crud->setCrudPath('/database/query');
            $crud->setCrudGroup('documentation');
            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            'ApplicationAdministrationContent',
                                            'generalmemory.documentation');

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

    }



}
