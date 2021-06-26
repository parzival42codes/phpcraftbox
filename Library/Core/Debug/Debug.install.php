<?php

class CoreDebug_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importLanguage();
        $this->importMeta();

        $this->queryDatabase($this->database());
    }


    protected function database():array
    {
        /** @var ContainerFactoryDatabaseEngineMysqlTable $structure */
        $structureCompare = Container::get('ContainerFactoryDatabaseEngineMysqlTable',
                                            'debug_statistic');

        /** @var ContainerFactoryDatabaseEngineMysqlTable $structure */
        $structure = Container::get('ContainerFactoryDatabaseEngineMysqlTable',
                                     'debug_statistic');

        $structure->setColumn('id',
                              'int;11',
                              false,
                              ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT);

        $structure->setColumn('pageComplete',
                              'varchar;50');
        $structure->setColumn('page',
                              'varchar;50');
        $structure->setColumn('pageDefault',
                              'varchar;50');
        $structure->setColumn('pageDebug',
                              'varchar;50');
        $structure->setColumn('pageApplication',
                              'varchar;50');
        $structure->setColumn('pageApplicationView',
                              'varchar;50');
        $structure->setColumn('memoryPeak',
                              'varchar;50');
        $structure->setColumn('memoryEnd',
                              'varchar;50');
        $structure->setColumn('dataVariableCreated',
                              'datetime');
        $structure->setPrimary(['id']);

        if ($structureCompare->importTable() === true) {
            return $structureCompare->createAlternateQuery($structure);
        }
        else {
            return $structure->createQuery();
        }

    }




}
