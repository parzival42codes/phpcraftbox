<?php

class ContainerExtensionTemplateParseCreateForm_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importLanguage();
        $this->readLanguageFromFile('parsley_translate');
        $this->importMetaFromModul();
        $this->importDocumentationCode();
    }



}
