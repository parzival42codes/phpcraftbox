<?php

class ApplicationAdministrationBoxEdit_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importRoute();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
        $this->readLanguageFromFile('item');
        $this->readLanguageFromFile('widgets');
        $this->importMeta();
        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  4
                              ]);
    }

}
