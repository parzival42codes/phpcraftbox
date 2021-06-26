<?php

class CoreAutoload_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importQueryDatabaseFromCrud('CoreAutoload_crud');
        $this->importLanguage();
    }



}
