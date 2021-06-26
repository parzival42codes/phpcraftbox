<?php

class ApplicationAdministrationUserGroup_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importMetaFromModul('_app');
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
        $this->readLanguageFromFile('item');
    }



}
