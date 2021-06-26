<?php

abstract class ContainerFactoryDatabaseEngine_abstract extends Base
{
    protected static array $databaseTables       = [];
    protected static array $databaseTablesColumn = [];

    abstract static function tableIsInDatabase(string $database, string $table): bool;

    abstract static function tableColumnIsInDatabase(string $database, string $table, string $column): bool;
}
