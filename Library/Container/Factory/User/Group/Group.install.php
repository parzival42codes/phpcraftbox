<?php

class ContainerFactoryUserGroup_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importQueryDatabaseFromCrud('ContainerFactoryUserGroup_crud');
        $this->importQueryDatabaseFromCrud('ContainerFactoryUserGroup_crud_groupaccess');

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryUserGroup_crud $crud */
            $crud = Container::get('ContainerFactoryUserGroup_crud');
            $crud->setCrudData('
            [de_DE]
            name = "Gast"
            description = "Gastzugang"
            ');
            $crud->setCrudProtected(1);
            $crud->setCrudId(1);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryUserGroup_crud $crud */
            $crud = Container::get('ContainerFactoryUserGroup_crud');
            $crud->setCrudData('
            [de_DE]
            name = "User"
            description = "User"
            ');
            $crud->setCrudProtected(1);
            $crud->setCrudId(2);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryUserGroup_crud $crud */
            $crud = Container::get('ContainerFactoryUserGroup_crud');
            $crud->setCrudData('
            [de_DE]
            name = "Moderator"
            description = "Moderator"
            ');
            $crud->setCrudProtected(1);
            $crud->setCrudId(3);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryUserGroup_crud $crud */
            $crud = Container::get('ContainerFactoryUserGroup_crud');
            $crud->setCrudData('
            [de_DE]
            name = "Admin"
            description = "Admin"
            ');
            $crud->setCrudProtected(1);
            $crud->setCrudId(4);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

    }

}
