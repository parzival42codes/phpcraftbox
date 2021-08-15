<?php

class ApplicationAdministrationReportSend_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
    }
}
