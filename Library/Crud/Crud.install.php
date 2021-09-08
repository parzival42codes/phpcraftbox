<?php declare(strict_types=1);

class Crud_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importQueryDatabaseFromCrud('Crud_crud_additional');
    }

}
