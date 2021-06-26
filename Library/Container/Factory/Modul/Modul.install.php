<?php

class ContainerFactoryModul_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importQueryDatabaseFromCrud('ContainerFactoryModul_crud');
    }



}
