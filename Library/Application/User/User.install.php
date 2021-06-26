<?php

class ApplicationUser_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
        $this->readLanguageFromFile('widget.link.login');
        $this->readLanguageFromFile('widget.link.session');
        $this->importDocumentationCode(ContainerFactoryModulInstall_abstract::DOCUMENTATION_TYPE_WIDGET);
        $this->importMetaFromModul('_app');
//        $this->importDocumentationWidget();
    }



}
