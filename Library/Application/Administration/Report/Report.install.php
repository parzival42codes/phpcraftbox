<?php

class ApplicationBlogView_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importMetaFromModul('_app');
    }

    public function activate(): void
    {
        $this->importRoute();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
    }

}
