<?php

class ContainerFactoryHistory_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMetaFromModul();
        $this->importQueryDatabaseFromCrud('ContainerFactoryHistory_crud');
    }


}
