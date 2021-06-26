<?php

class CoreDebugDump_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importMeta();
        $this->importLanguage();
    }



}
