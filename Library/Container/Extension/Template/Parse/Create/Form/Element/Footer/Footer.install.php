<?php

class ContainerExtensionTemplateParseCreateFormElementFooter_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->readLanguageFromFile('footer');
    }



}
