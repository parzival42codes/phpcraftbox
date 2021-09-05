<?php

class ApplicationAdministrationReport_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMetaFromModul('_app');
        $this->importQueryDatabaseFromCrud('ApplicationAdministrationReport_crud');
        $this->importQueryDatabaseFromCrud('ApplicationAdministrationReport_crud_type');
        $this->importMenu();
        $this->importRoute();
        $this->importLanguage();
        $this->readLanguageFromFile('default');

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            $crud = new ApplicationAdministrationReport_crud_type();
            $crud->truncate();
            $progressData['message'] = 'ApplicationAdministrationReport_crud_type Truncate';

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            $crud = new ApplicationAdministrationReport_crud_type();
            $crud->setCrudAbbreviation('cpa');
            $crud->setCrudContent(json_encode([
                                                  'de_DE' => 'Verstoß gegen das Urheberrechtsschutzgesetz',
                                                  'en_US' => 'Violation of the Copyright Protection Act',
                                              ]));

            $progressData['message'] = $crud->insertUpdate();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            $crud = new ApplicationAdministrationReport_crud_type();
            $crud->setCrudAbbreviation('spam');
            $crud->setCrudContent(json_encode([
                                                  'de_DE' => 'Illegaler Inhalt',
                                                  'en_US' => 'Illegal Content',
                                              ]));

            $progressData['message'] = $crud->insertUpdate();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            $crud = new ApplicationAdministrationReport_crud_type();
            $crud->setCrudAbbreviation('spam');
            $crud->setCrudContent(json_encode([
                                                  'de_DE' => 'Volksverletzung, Hetze, Verleumdung, Mobbing',
                                                  'en_US' => 'SPAM',
                                              ]));

            $progressData['message'] = $crud->insertUpdate();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            $crud = new ApplicationAdministrationReport_crud_type();
            $crud->setCrudAbbreviation('spam');
            $crud->setCrudContent(json_encode([
                                                  'de_DE' => 'SPAM',
                                                  'en_US' => 'SPAM',
                                              ]));

            $progressData['message'] = $crud->insertUpdate();

            /*$after*/
        });

    }
}
