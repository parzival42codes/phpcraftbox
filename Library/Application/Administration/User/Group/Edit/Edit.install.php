<?php

class ApplicationAdministrationUserGroupEdit_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importMeta();
        $this->importRoute();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  4
                              ]);
    }



}
