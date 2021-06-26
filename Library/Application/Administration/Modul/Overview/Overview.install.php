<?php

class ApplicationAdministrationModulOverview_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importMeta();
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
        $this->readLanguageFromFile('tooltip');
        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  4
                              ]);

    }




}
