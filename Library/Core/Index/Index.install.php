<?php

class CoreIndex_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importConfig();
    }



}
