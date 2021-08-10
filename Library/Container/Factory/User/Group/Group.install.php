<?php

class ContainerFactoryUserGroup_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importLanguage();
        $this->importQueryDatabaseFromCrud('ContainerFactoryUserGroup_crud');
        $this->importQueryDatabaseFromCrud('ContainerFactoryUserGroup_crud_groupaccess');

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryUserGroup_crud $crud */
            $crud = Container::get('ContainerFactoryUserGroup_crud');
            $crud->setCrudLanguage('{insert/language class="ContainerFactoryUserGroup" path="/group/name/guest"}');
            $crud->setCrudProtected(1);
            $crud->setCrudId(1);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryUserGroup_crud $crud */
            $crud = Container::get('ContainerFactoryUserGroup_crud');
            $crud->setCrudLanguage('{insert/language class="ContainerFactoryUserGroup" path="/group/name/user"}');
            $crud->setCrudProtected(1);
            $crud->setCrudId(2);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryUserGroup_crud $crud */
            $crud = Container::get('ContainerFactoryUserGroup_crud');
            $crud->setCrudLanguage('{insert/language class="ContainerFactoryUserGroup" path="/group/name/moderator"}');
            $crud->setCrudProtected(1);
            $crud->setCrudId(3);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryUserGroup_crud $crud */
            $crud = Container::get('ContainerFactoryUserGroup_crud');
            $crud->setCrudLanguage('{insert/language class="ContainerFactoryUserGroup" path="/group/name/admin"}');
            $crud->setCrudProtected(1);
            $crud->setCrudId(4);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

    }

}
