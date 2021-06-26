<?php

class ContainerFactoryRouter_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
      $this->importQueryDatabaseFromCrud('ContainerFactoryRouter_crud');
    }
}
