<?php

class ContainerExtensionTemplateParseCreateFormResponse_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->queryDatabase($this->database());
    }

    protected function database():array
    {
        /** @var ContainerFactoryDatabaseEngineMysqlTable $structureCompare */
        $structureCompare = Container::get('ContainerFactoryDatabaseEngineMysqlTable',
                                           'form_response');

        /** @var ContainerFactoryDatabaseEngineMysqlTable $structure */
        $structure = Container::get('ContainerFactoryDatabaseEngineMysqlTable',
                                    'form_response');

        $structure->setColumn('crudUniqid',
                              'varchar;100');
        $structure->setColumn('crudData',
                              'text');
        $structure->setColumn('crudModify',
                              'text');
        $structure->setColumn('dataVariableCreated',
                              'datetime');
        $structure->setColumn('dataVariableEdited',
                              'datetime');
        $structure->setPrimary([
                                   'crudUniqid',
                               ]);
        $structure->setKey('dataVariableCreated',
                           [
                               'dataVariableCreated'
                           ]);

        if ($structureCompare->importTable() === true) {
            return $structureCompare->createAlternateQuery($structure);
        }
        else {
            return $structure->createQuery();
        }

    }

}
