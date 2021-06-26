<?php

class ApplicationUserPasswordrequest_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMetaFromModul('_app');
        $this->importMenu();
        $this->importLanguage();
        $this->importRoute();
        $this->readLanguageFromFile('request.mail');
    }


}
