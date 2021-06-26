<?php

class ContainerFactoryGeneralmemory_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importQueryDatabaseFromCrud('ContainerFactoryGeneralmemory_crud');
    }




}
