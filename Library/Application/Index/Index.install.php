<?php

class ApplicationIndex_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importRoute();
        $this->importMenu();

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
            $crud->setCrudFlex(1);
            $crud->setCrudPosition(1);
            $crud->setCrudDescription('Foo Bar');
            $crud->setCrudContent($templateCache->getCacheContent()['install.main']);
            $crud->setCrudAssignment('index');
            $crud->setCrudActive(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  1,
                                  2,
                                  3,
                                  4
                              ]);
    }



}
