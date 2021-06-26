<?php

class ContainerFactorySessionSqlite implements SessionHandlerInterface
{
    private string $savePath = '';

    function open($savePath, $sessionName): bool
    {
        if (
            ContainerFactoryDatabaseEngineSqlite::tableIsInDatabase('cache',
                                                                    'session') === false
        ) {

            /** @var ContainerFactoryDatabaseEngineSqliteTable $queryStructure */
            $queryStructure = Container::get('ContainerFactoryDatabaseEngineSqliteTable',
                                             'session');
            $queryStructure->setColumn('ident',
                                       'varchar;100');
            $queryStructure->setColumn('content',
                                       'text',
                                       true,
                                       ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL);
            $queryStructure->setColumn('dataVariableUpdated',
                                       'datetime');
            $queryStructure->setPrimary([
                                            'ident'
                                        ]);

            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __CLASS__ . '.create.console',
                                    'cache',
                                    ContainerFactoryDatabaseQuery::MODE_OTHER);

            foreach ($queryStructure->createQuery() as $queryItem) {
                $query->query($queryItem);
                $query->execute();
            }

            ContainerFactoryDatabaseEngineSqlite::reInit();

            ContainerFactoryDatabaseEngineSqlite::addTableDatabase('cache',
                                                                   'session');
        }

        return true;
    }

    function close(): bool
    {
        return true;
    }

    function read($id): string
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                'cache',
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('session');
        $query->selectRaw('content');
        $query->setParameterWhere('ident',
                                  $id);

        $query->construct();
        $smtp   = $query->execute();
        $return = $smtp->fetch();

        if (empty($return['content'] ?? '')) {
            $this->destroy($id);
//            return null;
        }

        return ($return['content'] ?? '');
    }

    function write($id, $data): bool
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#insertUpdate',
                                'cache',
                                \ContainerFactoryDatabaseQuery::MODE_INSERT_UPDATE);

        $query->setTable('session');
        $query->setTableKey('ident');
        $query->setInsertUpdate('ident',
                                $id,
                                true);
        $query->setInsertUpdate('content',
                                $data,
                                true);
        $query->construct();
        $query->execute();

        return true;
    }

    function destroy($id): bool
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#insertUpdate',
                                'cache',
                                \ContainerFactoryDatabaseQuery::MODE_OTHER);

        $query->setTable('session');
        $query->query('DELETE FROM session WHERE ident = "' . $id . '"');

        $query->construct();
        $query->execute();

        return true;
    }

    function gc($max_lifetime): bool
    {
        $dateTime = new DateTime();
        $dateTime->modify('-' . $max_lifetime . ' second');

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#insertUpdate',
                                'cache',
                                \ContainerFactoryDatabaseQuery::MODE_OTHER);

        $query->setTable('session');
        $query->query('DELETE FROM session WHERE dataVariableUpdated < "' . $dateTime->format((string)Config::get('/cms/date/dbase')) . '"');

        $query->construct();
        $query->execute();
        return true;
    }

    protected function deleteSession(): void
    {

    }

}
