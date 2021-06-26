<?php

class ApplicationAdministrationBox_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
        $this->readLanguageFromFile('item');
        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  4
                              ]);
    }
}
