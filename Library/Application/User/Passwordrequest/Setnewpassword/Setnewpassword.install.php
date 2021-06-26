<?php

class ApplicationUserPasswordrequestSetnewpassword_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMetaFromModul('_app');
        $this->importLanguage();
        $this->importRoute();
    }


}
