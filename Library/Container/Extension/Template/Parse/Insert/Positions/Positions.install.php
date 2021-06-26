<?php

class ContainerExtensionTemplateParseInsertPositions_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importQueryDatabaseFromCrud('ContainerExtensionTemplateParseInsertPositions_crud');
    }

}
