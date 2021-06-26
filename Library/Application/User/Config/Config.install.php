<?php declare(strict_types=1);

/**
 * Config User
 *
 * Config User
 *
 * @author  Stefan Schlombs
 * @version 1.0.0
 * @modul   versionRequiredSystem 1.0.0
 * @modul   hasCSS
 */
class ApplicationUserConfig_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMetaFromModul('_app');
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
    }

    public function uninstall(): void
    {

        $this->removeStdEntities();

    }

    public function update(): void
    {

    }

    public function refresh(): void
    {

    }

    public function activate(): void
    {
    }

    public function deactivate(): void
    {
    }

    public function repair(): void
    {

    }
}
