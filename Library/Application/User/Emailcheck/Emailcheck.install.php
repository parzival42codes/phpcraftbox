<?php

class ApplicationUserEmailcheck_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importQueryDatabaseFromCrud('ApplicationUserEmailcheck_crud');
        $this->importMetaFromModul();
        $this->importLanguage();
        $this->importRoute();
    }



}
