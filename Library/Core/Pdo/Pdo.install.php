<?php

class CorePdo_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importLanguage();
    }



}
