<?php declare(strict_types=1);


class ApplicationUserProfil_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMetaFromModul("_app");

        $this->importRoute();
        $this->importLanguage();

    }
}
