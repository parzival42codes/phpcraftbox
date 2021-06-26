<?php

class ContainerFactoryToken_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->queryDatabase($this->database());
    }


    protected function database(): array
    {
        /** @var ContainerFactoryDatabaseEngineMysqlTable $structureCompare */
        $structureCompare = Container::get('ContainerFactoryDatabaseEngineMysqlTable',
                                           'token');

        /** @var ContainerFactoryDatabaseEngineMysqlTable $structure */
        $structure = Container::get('ContainerFactoryDatabaseEngineMysqlTable',
                                    'token');

        $structure->setColumn('id',
                              'int;11',
                              false,
                              ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT);
        $structure->setColumn('uuid',
                              'varchar;50');
        $structure->setColumn('used',
                              'tinyint;1',
                              true,
                              '0');
        $structure->setPrimary([
                                   'id',
                               ]);
        $structure->setKey('uuid',
                           [
                               'uuid'
                           ]);
        $structure->setKey('used',
                           [
                               'used'
                           ]);

        if ($structureCompare->importTable() === true) {
            return $structureCompare->createAlternateQuery($structure);
        }
        else {
            return $structure->createQuery();
        }

    }

}
