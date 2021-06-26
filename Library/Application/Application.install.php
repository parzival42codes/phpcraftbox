<?php

class Application_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
        $this->importMeta();
        $this->importLanguage();
    }

}
