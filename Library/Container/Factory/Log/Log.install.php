<?php

class ContainerFactoryLog_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importQueryDatabaseFromCrud('ContainerFactoryLog_crud_notification');
        $this->importQueryDatabaseFromCrud('ContainerFactoryLogPage_crud');
        $this->importQueryDatabaseFromCrud('ContainerFactoryLogStatistic_crud');
    }

}
