<?php

class ApplicationAdministrationReport_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMetaFromModul('_app');
        $this->importQueryDatabaseFromCrud('ApplicationAdministrationReport_crud');
        $this->importMenu();
        $this->importRoute();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
    }
}
