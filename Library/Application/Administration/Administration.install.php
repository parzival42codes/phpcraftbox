<?php

class ApplicationAdministration_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importLanguage();
        $this->importRoute();
        $this->importMenu();
        $this->readLanguageFromFile('install.left');
        $this->readLanguageFromFile('install.main');
        $this->readLanguageFromFile('install.right');

        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  4
                              ]);

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            'ApplicationAdministration',
                                            'install.left');

            /** @var ContainerIndexPageBox_crud $crud */
            $crud = Container::get('ContainerIndexPageBox_crud');
            $crud->setCrudClass('ApplicationAdministration');
            $crud->setCrudRow(1);
            $crud->setCrudFlex(1);
            $crud->setCrudPosition(1);
            $crud->setCrudDescription('Left');
            $crud->setCrudContent($templateCache->getCacheContent()['install.left']);
            $crud->setCrudAssignment('administration');
            $crud->setCrudActive(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            'ApplicationAdministration',
                                            'install.main');

            /** @var ContainerIndexPageBox_crud $crud */
            $crud = Container::get('ContainerIndexPageBox_crud');
            $crud->setCrudClass('ApplicationAdministration');
            $crud->setCrudRow(1);
            $crud->setCrudFlex(4);
            $crud->setCrudPosition(1);
            $crud->setCrudDescription('main');
            $crud->setCrudContent($templateCache->getCacheContent()['install.main']);
            $crud->setCrudAssignment('administration');
            $crud->setCrudActive(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            'ApplicationAdministration',
                                            'install.right');

            /** @var ContainerIndexPageBox_crud $crud */
            $crud = Container::get('ContainerIndexPageBox_crud');
            $crud->setCrudClass('ApplicationAdministration');
            $crud->setCrudRow(1);
            $crud->setCrudFlex(2);
            $crud->setCrudPosition(1);
            $crud->setCrudDescription('Right');
            $crud->setCrudContent($templateCache->getCacheContent()['install.right']);
            $crud->setCrudAssignment('administration');
            $crud->setCrudActive(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });


    }

}
