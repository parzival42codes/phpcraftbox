<?php

class ContainerExtensionTemplate_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importLanguage();
    }



}
