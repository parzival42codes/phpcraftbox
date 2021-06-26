<?php

class ContainerExtensionTemplateTagTableTableYesno_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importMeta();
        $this->importLanguage();
    }



}
