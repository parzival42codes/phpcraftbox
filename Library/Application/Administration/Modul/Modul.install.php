<?php

class ApplicationAdministrationModul_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importMeta();
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
        $this->readLanguageFromFile('dialog');
        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  4
                              ]);
    }




}
