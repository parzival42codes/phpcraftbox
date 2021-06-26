<?php declare(strict_types=1);


class ApplicationUserMessage_install extends ContainerFactoryModulInstall_abstract
{
    public function install(): void
    {
        $this->importMetaFromModul("_app");
        $this->importQueryDatabaseFromCrud('ApplicationUserMessage_crud_messages');
        $this->importQueryDatabaseFromCrud('ApplicationUserMessage_crud');
        $this->importRoute();
        $this->importMenu();
        $this->importLanguage();
        $this->importConfig();
        $this->importConfigUser();
    }
}
