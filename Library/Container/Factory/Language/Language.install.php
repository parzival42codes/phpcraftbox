<?php

class ContainerFactoryLanguage_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importQueryDatabaseFromCrud('ContainerFactoryLanguage_crud');
        $this->importLanguage();
        $this->importDocumentationCode();
        $this->importMetaFromModul();
    }



}
