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
            $crud->setCrudUsername('admin');
            $crud->setCrudEmail('admin@phpcraftbox.loc');
            $crud->setCrudPassword(password_hash('admin',
                                                 PASSWORD_DEFAULT));
            $crud->setCrudUserGroupId(4);
            $crud->setCrudActivated(true);
            $crud->setCrudEmailCheck(true);

            $progressData['message'] = $crud->insert();

            /*$after*/
        });


    }



}
