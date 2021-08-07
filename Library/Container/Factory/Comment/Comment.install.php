<?php

class ContainerFactoryComment_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importQueryDatabaseFromCrud('ContainerFactoryComment_crud');
        $this->importLanguage();

        $this->setGroupAccess(Core::getRootClass(__CLASS__).'/permitted',
                              [ ]);
    }


}
