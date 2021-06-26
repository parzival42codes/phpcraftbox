<?php declare(strict_types=1);


class ApplicationUserMessageNew_install extends ContainerFactoryModulInstall_abstract
{


    public function install(): void
    {
        $this->importMetaFromModul("_app");
        $this->importRoute();
//        $this->importMenu();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
        $this->readLanguageFromFile('mail');
    }
}
