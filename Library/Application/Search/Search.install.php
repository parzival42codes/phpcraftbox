<?php

class ApplicationSearch_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importRoute();
        $this->importMenu();
        $this->importMetaFromModul('_app');
    }



}
