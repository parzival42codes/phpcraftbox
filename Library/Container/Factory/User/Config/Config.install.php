<?php

class ContainerFactoryUserConfig_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importQueryDatabaseFromCrud('ContainerFactoryUserConfig_crud');
        $this->importQueryDatabaseFromCrud('ContainerFactoryUserConfig_crud_user');
    }



}
