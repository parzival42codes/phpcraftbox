<?php

class Core_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importQueryDatabaseFromCrud('Core_crud');
    }



}
