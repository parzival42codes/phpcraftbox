<?php

class ContainerExtensionTemplateParse_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importLanguage();
        $this->readLanguageFromFile('debug');
    }



}
