<?php

class ContainerFactoryComment_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMetaFromModul();
        $this->importQueryDatabaseFromCrud('ContainerFactoryComment_crud');
        $this->importLanguage();
        $this->readLanguageFromFile('default');
        $this->readLanguageFromFile('item');
        $this->readLanguageFromFile('item.report');
        $this->readLanguageFromFile('item.send');

        $this->setGroupAccess(Core::getRootClass(__CLASS__) . '/permitted',
                              [
                                  3,
                                  4
                              ]);
    }


}
