<?php

class ContainerFactoryUser_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importQueryDatabaseFromCrud('ContainerFactoryUser_crud');
        $this->importLanguage();
        $this->importConfig();

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryUser_crud $crud */
            $crud = Container::get('ContainerFactoryUser_crud');
            $crud->setCrudUsername(\Config::get('/environment/install/user/username'));
            $crud->setCrudEmail(\Config::get('/environment/install/user/email'));
            $crud->setCrudPassword(password_hash(\Config::get('/environment/install/user/password'),
                                                 PASSWORD_DEFAULT));
            $crud->setCrudUserGroupId(4);
            $crud->setCrudActivated(true);
            $crud->setCrudEmailCheck(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });


    }



}
