<?php

class ContainerFactoryRequest_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importMetaFromModul();
        $this->importDocumentationCode();
//        $this->readLanguageFromFile('install.documentation');
    }



}
