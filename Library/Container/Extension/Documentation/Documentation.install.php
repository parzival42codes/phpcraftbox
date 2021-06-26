<?php

class ContainerExtensionDocumentation_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importQueryDatabaseFromCrud('ContainerExtensionDocumentation_crud');
    }

}
