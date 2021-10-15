<?php

/**
 * Cache
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    language_path_de_DE /PHPToolBox
 * @modul    language_name_de_DE Cache
 * @modul    language_path_en_US /PHPToolBox
 * @modul    language_name_en_US Cache
 */

class ContainerExtensionCache_install extends ContainerFactoryModulInstall_abstract
{
    public function install(): void
    {
        $this->importMetaFromModul('_install');
        $this->importLanguage();
        $this->readLanguageFromFile('debug');
        $this->importConfig();
    }


}
