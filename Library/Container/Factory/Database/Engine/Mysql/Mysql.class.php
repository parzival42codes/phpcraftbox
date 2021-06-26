<?php

class ContainerFactoryDatabaseEngineMysql extends ContainerFactoryDatabaseEngine_abstract
{

    public static function init(string $databaseConnection, string $table): void
    {

        if (isset(self::$databaseTables[$databaseConnection]) === false) {

            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__,
                                    $databaseConnection,
                                    ContainerFactoryDatabaseQuery::MODE_OTHER,
                                    false);

            $dbName        = \Config::get('/environment/database/' . $databaseConnection . '/dbname');
            $dbNameTableIn = 'Tables_in_' . $dbName;

            $query->query('SHOW TABLES FROM ' . $dbName);

            $query->construct();
            $smtp = $query->execute();

            self::$databaseTables[$databaseConnection] = [];
            while ($smtpData = $smtp->fetch()) {
                self::$databaseTables[$databaseConnection][] = $smtpData[$dbNameTableIn];
            }
        }

        if (isset(self::$databaseTablesColumn[$databaseConnection][$table]) === false) {
            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__,
                                    true,
                                    ContainerFactoryDatabaseQuery::MODE_OTHER,
                                    false);

            $query->query('SHOW FULL COLUMNS FROM `' . $table . '`');
            $query->construct();
            $smtp = $query->execute();

            self::$databaseTablesColumn[$databaseConnection][$table] = [];

            while ($smtpData = $smtp->fetch()) {
                self::$databaseTablesColumn[$databaseConnection][$table][] = $smtpData['Field'];
            }
        }

    }

    public static function tableIsInDatabase(string $database, string $table): bool
    {
        self::init($database,
                   $table);
        return in_array($table,
                        self::$databaseTables[$database]);
    }

    public static function tableColumnIsInDatabase(string $database, string $table, string $column): bool
    {
        self::init($database,
                   $table);
        return in_array($column,
                        self::$databaseTablesColumn[$database][$table]);
    }

}

