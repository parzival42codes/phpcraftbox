<?php declare(strict_types=1);

class ApplicationAdministrationUserEdit_install extends ContainerFactoryModulInstall_abstract
{


    public function install(): void
    {
        $this->importRoute();
        $this->importLanguage();
        $this->importMetaFromModul("_app");
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

        $this->removeStdEntitiesDeactivate();

    }

    public function repair(): void
    {

    }
}
