<?php

class ContainerExternResources_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importRoute();
        $this->importMeta();
    }



}
