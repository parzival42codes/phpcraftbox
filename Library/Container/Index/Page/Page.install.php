<?php

class ContainerIndexPage_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importLanguage();
        $this->importMeta();
        $this->importRoute();
    }



}
