<?php declare(strict_types=1);


class ApplicationUserMessageReply_install extends ContainerFactoryModulInstall_abstract
{


    public function install(): void
    {
        $this->importMetaFromModul("_app");
        $this->importRoute();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
        $this->readLanguageFromFile('form');
        $this->readLanguageFromFile('mail');
    }
}
