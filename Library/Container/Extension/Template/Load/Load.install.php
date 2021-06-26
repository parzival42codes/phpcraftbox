<?php

/**
 * Template Loader
 *
 * Template Loader
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    language_name_de_DE Laden
 * @modul    language_name_en_US Loader
 * @modul    language_path_de_DE /Template
 * @modul    language_path_en_US /Template
 *
 */

class ContainerExtensionTemplateLoad_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importMetaFromModul('_install');
        $this->importDocumentationCode();
    }



}
