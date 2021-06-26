<?php

class ContainerFactoryDatabaseEngineSqlite extends ContainerFactoryDatabaseEngine_abstract
{
    protected static bool $reInit = false;

    protected static function init(string $database): void
    {

        if (isset(self::$databaseTables[$database]) === false || self::$reInit === true) {
            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__ . '#select',
                                    $database,
                                    ContainerFactoryDatabaseQuery::MODE_SELECT);
            $query->setTable('sqlite_master');
            $query->selectRaw('name');
            $query->selectRaw('sql');
            $query->setAutomaticSetData(false);

            $query->construct();
            $smtp = $query->execute();

            self::$databaseTables[$database] = [];
            while ($smtpData = $smtp->fetch()) {

                preg_match_all('@`(.*?)`@i',
                               $smtpData['sql'],
                               $sqlColumn);
                $sqlColumnFields = $sqlColumn[1];
                array_shift($sqlColumnFields);

                self::$databaseTables[$database][]                        = $smtpData['name'];
                self::$databaseTablesColumn[$database][$smtpData['name']] = $sqlColumnFields;
            }
        }

    }

    public static function tableIsInDatabase(string $database, string $table): bool
    {
        self::init($database);
        return in_array($table,
            (self::$databaseTables[$database] ?? []));
    }

    public static function tableColumnIsInDatabase(string $database, string $table, string $column): bool
    {
        self::init($database);
        return in_array($column,
            (self::$databaseTablesColumn[$database][$table] ?? []));
    }

    public static function addTableDatabase(string $database, string $table): void
    {
        self::$databaseTables[$database][] = $table;
    }

    public static function reInit(): void
    {
        self::$reInit = true;
    }

}

