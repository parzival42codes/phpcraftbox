<?php

class ApplicationUserLogout_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importRoute();
        $this->importLanguage();
        $this->importMeta();
        $this->importMenu();

        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  1,
                                  2,
                                  3,
                                  4
                              ]);

        $this->setGroupAccess(Core::getRootClass(__CLASS__) . '/Menu',
                              [
                                  2,
                                  3,
                                  4
                              ]);
    }


}
