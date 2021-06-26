<?php

class ApplicationUserLogin_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMetaFromModul('_app');
        $this->importConfig();
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
        $this->readLanguageFromFile('default');

        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  1,
                                  2,
                                  3,
                                  4
                              ]);

        $this->setGroupAccess(Core::getRootClass(__CLASS__) . '/Menu',
                              [
                                  1,
                              ]);
    }


}
