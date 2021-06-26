<?php

class ContainerFactoryUserGroupAccess_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
       $this->importQueryDatabaseFromCrud('ContainerFactoryUserGroupAccess_crud');
    }

}
