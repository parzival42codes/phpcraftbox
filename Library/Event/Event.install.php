<?php

class Event_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importQueryDatabaseFromCrud('Event_crud');
        $this->queryDatabase($this->databaseTrigger());
    }

    protected function databaseTrigger(): array
    {
        /** @var ContainerFactoryDatabaseEngineMysqlTable $structure */
        $structureCompare = Container::get('ContainerFactoryDatabaseEngineMysqlTable',
                                           'event_trigger');

        /** @var ContainerFactoryDatabaseEngineMysqlTable $structure */
        $structure = Container::get('ContainerFactoryDatabaseEngineMysqlTable',
                                    'event_trigger');


        $structure->setColumn('crudId',
                              'int;11',
                              false,
                              ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT);
        $structure->setColumn('crudPath',
                              'varchar;100');
        $structure->setColumn('crudTriggerClass',
                              'varchar;100');
        $structure->setColumn('crudTriggerMethod',
                              'varchar;100');
        $structure->setPrimary([
                                   'crudId'
                               ]);

        if ($structureCompare->importTable() === true) {
            return $structureCompare->createAlternateQuery($structure);
        }
        else {
            return $structure->createQuery();
        }

    }

}
