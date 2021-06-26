<?php

class ContainerFactoryMenu_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importMeta();
        $this->importConfig();

        $this->importQueryDatabaseFromCrud('ContainerFactoryMenu_crud');
    }




}
