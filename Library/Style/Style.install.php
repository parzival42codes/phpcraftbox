<?php

/**
 * Style
 *
 * Styles
 *
 * @modul author Stefan Schlombs
 * @modul version 1.0.0
 * @modul versionRequiredSystem 1.0.0
 * @modul hasCSS button
 *
 */

class Style_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importConfig();
        $this->importMetaFromModul('_install');
    }



}
