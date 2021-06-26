<?php
declare(strict_types=1);

/**
 * Custom
 *
 * Manage the Custom Module
 *
 * @modul author Stefan Schlombs
 * @modul version 1.0.0
 * @modul versionRequiredSystem 1.0.0
 *
 */
class Custom_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importQueryDatabaseFromCrud('Custom_crud');
    }

    public function collectCustomClasses()
    {
        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            Custom::getCustomCLasses();

            $progressData["message"] = "Custom::getCustomCLasses()";

            /*$after*/
        });
    }

}
