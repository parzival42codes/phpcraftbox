<?php declare(strict_types=1);


class ApplicationAdministrationUser_install extends ContainerFactoryModulInstall_abstract
{


    public function install(): void
    {
        $this->importMetaFromModul("_app");
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
        $this->readLanguageFromFile('default');
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
