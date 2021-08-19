<?php

class ApplicationAdministrationReportSend_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importMetaFromModul('_app');
        $this->importRoute();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
    }
}
