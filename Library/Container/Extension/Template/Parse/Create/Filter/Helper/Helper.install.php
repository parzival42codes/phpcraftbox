<?php

class ContainerExtensionTemplateParseCreateFilterHelper_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importMetaFromModul();
        $this->readLanguageFromFile('default');
        $this->importDocumentationCode();
    }



}
