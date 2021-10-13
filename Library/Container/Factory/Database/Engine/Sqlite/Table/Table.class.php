<?php

class ContainerFactoryDatabaseEngineSqliteTable extends Base
{
    const DEFAULT_NONE           = 'NONE';
    const DEFAULT_NULL           = 'NULL';
    const DEFAULT_BOOLEAN_TRUE   = 'true';
    const DEFAULT_BOOLEAN_FALSE  = 'false';
    const DEFAULT_AUTO_INCREMENT = 'AUTO_INCREMENT';
    protected static array $tableIndex        = [];
    protected static bool  $tableIndexCache   = true;
    protected array        $structure         = [];
    protected array        $structureKey      = [];
    protected array        $structureFulltext = [];
    protected array        $structurePrimary  = [];
    protected array        $structureUnique   = [];
    protected bool         $deletePrimary     = false;
    protected string       $table             = '';
    protected array        $column            = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * @return bool
     */
    public static function isTableIndexCache(): bool
    {
        return self::$tableIndexCache;
    }

    /**
     * @param bool $tableIndexCache
     */
    public static function setTableIndexCache(bool $tableIndexCache): void
    {
        self::$tableIndexCache = $tableIndexCache;
    }

    public function importTable(): bool
    {
        if (empty(self::$tableIndex) || self::$tableIndexCache === false) {
            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__,
                                    true,
                                    ContainerFactoryDatabaseQuery::MODE_OTHER,
                                    false);

            $query->query('SHOW TABLE STATUS FROM ' . $query->getDbname());
            $query->construct();
            $smtp               = $query->execute();
            $databaseTablesData = $smtp->fetchAll();

            if ($databaseTablesData == false) {
                throw new DetailedException('tableStatusReadError',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    $databaseTablesData
                                                ]
                                            ]);
            }

            $arrayColumn = array_column($databaseTablesData,
                                        'Name');

            if ($arrayColumn == false) {
                throw new DetailedException('columnError',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    $databaseTablesData
                                                ]
                                            ]);
            }


            self::$tableIndex = array_flip($arrayColumn);
        }

        if (!isset(self::$tableIndex[$this->table])) {
            return false;
        }

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__,
                                true,
                                ContainerFactoryDatabaseQuery::MODE_OTHER,
                                false);

        $query->query('SHOW FULL COLUMNS FROM `' . $this->table . '`');
        $query->construct();
        $smtp      = $query->execute();
        $tableData = $smtp->fetchAll();

        if ($tableData == false) {
            throw new DetailedException('columnsShowError',
                                        0,
                                        null,
                                        [
                                            'debug' => []
                                        ]);
        }

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__,
                                true,
                                ContainerFactoryDatabaseQuery::MODE_OTHER,
                                false);
//         d($tableData);


        foreach ($tableData as $tableDataItem) {
            preg_match('@(.*?)\((.*?)\)@si',
                       $tableDataItem['Type'],
                       $typeDateTemp);

            $null = (($tableDataItem['Null'] === 'NO') ? false : true);

            $default = self::DEFAULT_NONE;

            if ($tableDataItem['Default'] === NULL && $tableDataItem['Null'] === 'YES') {
                $default = self::DEFAULT_NULL;
            }
            elseif ($tableDataItem['Extra'] === 'auto_increment') {
                $default = self::DEFAULT_AUTO_INCREMENT;
            }
            else {
                if ($tableDataItem['Default'] === NULL) {
                    $default = self::DEFAULT_NONE;
                }
                else {
                    $default = $tableDataItem['Default'];
                }
            }

            if (isset($typeDateTemp[2]) !== false) {
                $typeDataValue = strtr($typeDateTemp[2],
                                       [
                                           '\'' => '"'
                                       ]);

                $this->setColumn($tableDataItem['Field'],
                                 implode(';',
                                         [
                                             $typeDateTemp[1],
                                             $typeDataValue
                                         ]),
                                 $null,
                                 $default,
                                 $tableDataItem['Comment']);
            }
            else {
                $this->setColumn($tableDataItem['Field'],
                                 $tableDataItem['Type'],
                                 $null,
                                 $default,
                                 $tableDataItem['Comment']);
            }

        }

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__,
                                true,
                                ContainerFactoryDatabaseQuery::MODE_OTHER,
                                false);

        $query->query('SHOW INDEXES FROM ' . $this->table);
        $query->construct();
        $smtp           = $query->execute();
        $tableIndexData = $smtp->fetchAll();

        if ($tableIndexData == false) {
            throw new DetailedException('indexesError',
                                        0,
                                        null,
                                        [
                                            'debug' => []
                                        ]);
        }

        $tableIndexDataCollect = [];
        foreach ($tableIndexData as $tableIndexDataItem) {
            $tableIndexDataCollect[$tableIndexDataItem['Key_name']][] = $tableIndexDataItem;
        }

        //simpleDebugDump($tableIndexDataCollect);

        $structureIndex = [];
        $keyMode        = '';
        foreach ($tableIndexDataCollect as $tableIndexDataCollectItemKey => $tableIndexDataCollectItemItem) {
            foreach ($tableIndexDataCollectItemItem as $tableIndexDataCollectItemItemItem) {
                if ($tableIndexDataCollectItemKey === 'PRIMARY') {
                    $keyMode = 'primary';
                }
                elseif ($tableIndexDataCollectItemItemItem['Index_type'] === 'FULLTEXT') {
                    $keyMode = 'fulltext';
                }
                elseif ($tableIndexDataCollectItemItemItem['Non_unique'] === '0') {
                    $keyMode = 'unique';
                }
                elseif ($tableIndexDataCollectItemItemItem['Non_unique'] === '1') {
                    $keyMode = 'key';
                }

                $structureIndex[$keyMode][$tableIndexDataCollectItemItemItem['Key_name']][$tableIndexDataCollectItemItemItem['Seq_in_index']] = $tableIndexDataCollectItemItemItem['Column_name'];


            }
        }

        if (isset($structureIndex['primary']) && is_array($structureIndex['primary'])) {
            foreach ($structureIndex['primary'] as $structureIndexPrimaryItem) {
                $this->setPrimary($structureIndexPrimaryItem);
            }
        }

        if (isset($structureIndex['unique']) && is_array($structureIndex['unique'])) {
            foreach ($structureIndex['unique'] as $structureIndexUniqueKey => $structureIndexUniqueItem) {
                $this->setUnique((string)$structureIndexUniqueKey,
                                 $structureIndexUniqueItem);
            }
        }

        if (isset($structureIndex['key']) && is_array($structureIndex['key'])) {
            foreach ($structureIndex['key'] as $structureIndexKeyKey => $structureIndexKeyItem) {
                $this->setKey((string)$structureIndexKeyKey,
                              $structureIndexKeyItem);
            }
        }

        if (isset($structureIndex['fulltext']) && is_array($structureIndex['fulltext'])) {
            foreach ($structureIndex['fulltext'] as $structureIndexKeyKey => $structureIndexKeyItem) {
                $this->setFulltext((string)$structureIndexKeyKey);
            }
        }

        return true;
    }

    /**
     * Set Primary
     *
     * @param array $keys Keys Name of the Primary
     */
    public function setPrimary($keys): void
    {
        if (is_string($keys)) {
            $keys = [$keys];
        }

        $this->deletePrimary = true;
        $this->structurePrimary[implode('_',
                                        $keys)]
                             = $keys;
    }

    public function setUnique(string $name, array $keys = []): void
    {
        if (empty($keys)) {
            $keys = [$name];
        }

        $this->structureUnique[$name] = $keys;
    }

    public function setKey(string $name, array $keys = []): void
    {
        if (empty($keys)) {
            $keys = [$name];
        }

        $this->structureKey[$name] = $keys;
    }

    public function setFulltext(string $name): void
    {
        $this->structureFulltext[$name] = $name;
    }

    public function getFulltext(): array
    {
        return $this->structureFulltext;
    }

    /**
     * Get Primary
     *
     * @param string $name Name of the Primary
     *
     * @return array Keys
     */
    public function getPrimary(string $name): array
    {
        return $this->structurePrimary[$name];
    }

    /**
     * Remove Primary
     *
     * @param string $name Name of the Primary
     */
    public function removePrimary(string $name): void
    {
        unset($this->structurePrimary[$name]);
    }

    /**
     * Get Key
     *
     * @param string $name Name of the Key
     *
     * @return  Keys
     */
    public function getKey(string $name): ?array
    {
        return ($this->structureKey[$name] ?? null);
    }

    /**
     * Remove Key
     *
     * @param string $name Name of the Key
     *
     */
    public function removeKey(string $name): void
    {
        unset($this->structureKey[$name]);
    }

    /**
     * Get Unique
     *
     * @param string $name Name of the Unique
     *
     * @return string Keys
     */
    public function getUnique(string $name): string
    {
        return $this->structureUnique[$name];
    }

    /*
     * @param string $name Name of the Key
     * @param array  $keys Keys
     */

    /**
     * Remove Unique
     *
     * @param string $name Name of the Unique
     *
     */
    public function removeUnique(string $name): void
    {
        unset($this->structureUnique[$name]);
    }

    public function createQuery(): array
    {

        $return       = 'CREATE TABLE `' . $this->table . "` (\n";
        $returnAppend = [];

        $createTable = [];

        foreach ($this->column as $tableStructureTableKey => $tableStructureTableItem) {
            $createTable[] = '`' . $tableStructureTableKey . '` ' . $this->createColumn($tableStructureTableKey,
                                                                                        implode(';',
                                                                                                $tableStructureTableItem['type']),
                                                                                        $tableStructureTableItem['null'],
                                                                                        $tableStructureTableItem['default'],
                                                                                        $tableStructureTableItem['comment']);;
        }

        foreach ($this->structurePrimary as $structurePrimaryItem) {
            $createTable[] = 'PRIMARY KEY (`' . implode('`,`',
                                                        $structurePrimaryItem) . '`)';
        }

        foreach ($this->structureUnique as $structureUniqueKey => $structureUniqueItem) {
            $createTable[] = 'UNIQUE INDEX  `' . $structureUniqueKey . '` (`' . implode('`,`',
                                                                                        $structureUniqueItem) . '`)';
        }

        foreach ($this->structureFulltext as $structureUniqueItem) {
            $returnAppend[] = 'ALTER TABLE `' . $this->table . '` ADD FULLTEXT KEY `' . $structureUniqueItem . '` (`' . $structureUniqueItem . '`)';
        }

        foreach ($this->structureKey as $structureKeyKey => $structureKeyItem) {
            $createTable[] = 'KEY  `' . $structureKeyKey . '` (`' . implode('`,`',
                                                                            $structureKeyItem) . '`)';
        }

        $return .= implode(",\n",
                           $createTable) . "\n";


        $return .= ');';

        if (empty($returnAppend)) {
            return [
                $return,
            ];
        }
        else {
            return array_merge([$return],
                               $returnAppend);;
        }


    }

    protected function createColumn(string $name, string $type, bool $null = false, string $default = self::DEFAULT_NONE, string $comment = '', string $columnLastName = ''): string
    {
        $return   = '';
        $dataType = explode(';',
                            $type);
        switch (strtolower($dataType[0])) {
            case 'varchar':
                $return .= 'VARCHAR (' . (int)$dataType[1] . ') ';
                break;
            case 'text':
                $return .= 'TEXT ';
                break;
            case 'longtext':
                $return .= 'LONGTEXT ';
                break;
            case 'integer':
            case 'int':
                $return .= 'int(' . (int)$dataType[1] . ') ';
                break;
            case 'tinyint':
                $return .= 'tinyint (' . (int)$dataType[1] . ') ';
                break;
            case 'float':
                $return .= 'float (' . (int)$dataType[1] . ') ';
                break;
            case 'decimal':
                $return .= 'decimal (' . (int)$dataType[1] . ') ';
                break;
            case 'double':
                $return .= 'double (' . (int)$dataType[1] . ') ';
                break;
            case 'datetime':
                $return .= 'datetime ';
                break;
            case 'boolean':
                $return .= 'BOOLEAN ';
                break;
            case 'enum':
                $return .= 'ENUM (' . $dataType[1] . ')';
                break;
            default:
                throw new DetailedException('tableTypeFail',
                                            0,
                                            null,
                                            [
                                                'type' => $dataType,
                                            ]);

        }

        $parameter = [];

        $parameter[] = (($null === true) ? ' NULL' : ' NOT NULL');

        if ($default !== self::DEFAULT_NONE) {
            if ($default === self::DEFAULT_AUTO_INCREMENT) {
                $parameter[] = ' AUTO_INCREMENT';
            }
            elseif ($default === self::DEFAULT_NULL) {
                $parameter[] = ' DEFAULT NULL';
            }
            else {
                $parameter[] = ' DEFAULT "' . $default . '"';
            }
        }

        $parameter[] = (empty($comment) ? '' : ' COMMENT "' . $comment . '"');

        if ($columnLastName !== '') {
            $parameter[] = ' AFTER ' . $columnLastName;
        }

        $return .= implode(' ',
                           $parameter);

        return $return;
    }

    public function createAlternateQuery(ContainerFactoryDatabaseEngineMysqlTable $structureTable): array
    {

        $columnsSource     = $this->getColumns();
        $columnsTarget     = $structureTable->getColumns();
        $columnsDiffAdd    = array_diff_key($columnsTarget,
                                            $columnsSource);
        $columnsDiffRemove = array_keys(array_diff_key($columnsSource,
                                                       $columnsTarget));
        $columnsIntersect  = array_keys(array_intersect_key($columnsTarget,
                                                            $columnsSource));

        $reordering = false;
        if (array_keys($this->getColumns()) !== array_keys($structureTable->getColumns())) {
            $reordering = true;
        }

        $alterTable = [];

        foreach ($columnsDiffAdd as $columnsDiffAddKey => $columnsDiffAddItem) {
            $this->setColumn($columnsDiffAddKey,
                             implode(';',
                                     $columnsDiffAddItem['type']),
                             $columnsDiffAddItem['null'],
                             $columnsDiffAddItem['default'],
                             $columnsDiffAddItem['comment']);
            $alterTable[] = 'ALTER TABLE `' . $this->table . '` ADD COLUMN `' . $columnsDiffAddKey . '` ' . $this->createColumn($columnsDiffAddKey,
                                                                                                                                implode(';',
                                                                                                                                        $columnsDiffAddItem['type']),
                                                                                                                                $columnsDiffAddItem['null'],
                                                                                                                                $columnsDiffAddItem['default'],
                                                                                                                                $columnsDiffAddItem['comment']);
        }

        foreach ($columnsDiffRemove as $columnsDiffRemoveItem) {
            $this->removeColumn($columnsDiffRemoveItem);
            $alterTable[] = 'ALTER TABLE ' . $this->table . ' DROP COLUMN `' . $columnsDiffRemoveItem . '`';
        }

        // simpleDebugDump($columnsIntersect);

        $columnLastName = '';
        foreach ($columnsIntersect as $columnsIntersectItem) {
            $columnsIntersectItemCheckSource = var_export($this->getColumn($columnsIntersectItem),
                                                          true);
            $columnsIntersectItemCheckTarget = var_export($structureTable->getColumn($columnsIntersectItem),
                                                          true);

//            d($columnsIntersectItem);
//            d($columnsIntersectItemCheckSource);
//            d($columnsIntersectItemCheckTarget);

            if ($columnsIntersectItemCheckSource !== $columnsIntersectItemCheckTarget || $reordering === true) {

                $columnsIntersectItemCheckTarget = $structureTable->getColumn($columnsIntersectItem);

                $alterTable[] = 'ALTER TABLE `' . $this->table . '` CHANGE COLUMN `' . $columnsIntersectItem . '` `' . $columnsIntersectItem . '` ' . $this->createColumn($columnsIntersectItem,
                                                                                                                                                                          implode(';',
                                                                                                                                                                                  $columnsIntersectItemCheckTarget['type']),
                                                                                                                                                                          $columnsIntersectItemCheckTarget['null'],
                                                                                                                                                                          $columnsIntersectItemCheckTarget['default'],
                                                                                                                                                                          $columnsIntersectItemCheckTarget['comment'],
                                                                                                                                                                          $columnLastName);
            };
            $columnLastName = $columnsIntersectItem;
        }

        $primarySource       = $this->getPrimarys();
        $primaryTarget       = $structureTable->getPrimarys();
        $primaryTargetAdd    = array_diff_key($primaryTarget,
                                              $primarySource);
        $primaryTargetRemove = array_keys(array_diff_key($primarySource,
                                                         $primaryTarget));

        if (!empty($primaryTargetRemove)) {
            $alterTable[] = 'ALTER TABLE `' . $this->table . '` DROP PRIMARY KEY';
        }

        foreach ($primaryTargetAdd as $primaryTargetAddItem) {
            $alterTable[] = 'ALTER TABLE `' . $this->table . '` ADD PRIMARY KEY (`' . implode('`, `',
                                                                                              $primaryTargetAddItem) . '`)';
        }

        $fulltextSource = $this->getFulltext();
        $fulltextTarget = $structureTable->getFulltext();

        $fulltextAdd    = array_diff_key($fulltextTarget,
                                         $fulltextSource);
        $fulltextRemove = array_keys(array_diff_key($fulltextSource,
                                                    $fulltextTarget));

        foreach ($fulltextAdd as $fulltextAddKey => $fulltextAddItem) {
            $alterTable[] = 'ALTER TABLE `' . $this->table . '` ADD KEY ' . $fulltextAddKey . ' (`' . implode('`, `',
                                                                                                              $fulltextAddItem) . '`)';
        }

        foreach ($fulltextRemove as $fulltextRemoveItem) {
            if ($this->getKey($fulltextRemoveItem)) {
                $alterTable[] = 'ALTER TABLE `' . $this->table . '` DROP INDEX `' . $fulltextRemoveItem . '`';
            }
        }

        $uniquesSource       = $this->getUniques();
        $uniquesTarget       = $structureTable->getUniques();
        $uniquesTargetAdd    = array_diff_key($uniquesTarget,
                                              $uniquesSource);
        $uniquesTargetRemove = array_keys(array_diff_key($uniquesSource,
                                                         $uniquesTarget));

        foreach ($uniquesTargetAdd as $uniquesTargetAddKey => $uniquesTargetAddItem) {
            $alterTable[] = 'ALTER TABLE `' . $this->table . '` ADD UNIQUE INDEX ' . $uniquesTargetAddKey . ' (`' . implode('`, `',
                                                                                                                            $uniquesTargetAddItem) . '`)';
        }

        foreach ($uniquesTargetRemove as $uniquesTargetRemoveItem) {
            if ($this->getKey($uniquesTargetRemoveItem)) {
                $alterTable[] = 'ALTER TABLE `' . $this->table . '` DROP INDEX `' . $uniquesTargetRemoveItem . '`';
            }
        }

        $keysSource       = $this->getKeys();
        $keysTarget       = $structureTable->getKeys();
        $keysTargetAdd    = array_diff_key($keysTarget,
                                           $keysSource);
        $keysTargetRemove = array_keys(array_diff_key($keysSource,
                                                      $keysTarget));

        foreach ($keysTargetAdd as $keysTargetAddKey => $keysTargetAddItem) {
            $alterTable[] = 'ALTER TABLE `' . $this->table . '` ADD KEY ' . $keysTargetAddKey . ' (`' . implode('`, `',
                                                                                                                $keysTargetAddItem) . '`)';
        }

        foreach ($keysTargetRemove as $keysTargetRemoveItem) {
            if ($this->getKey($keysTargetRemoveItem)) {
                $alterTable[] = 'ALTER TABLE `' . $this->table . '` DROP INDEX `' . $keysTargetRemoveItem . '`';
            }
        }

        return $alterTable;

    }

    public function getColumns(): array
    {
        return $this->column;
    }

    /*
     * @param string $name Name of the Unique
     * @param array  $keys Keys
     */

    public function removeColumn(string $name): void
    {
        unset($this->column[$name]);
    }

    public function getColumn(string $name): array
    {
        return $this->column[$name];
    }

    public function setColumn(string $name, string $type, bool $null = false, $default = self::DEFAULT_NONE, string $comment = ''): void
    {


        $this->column[$name] = [
            'type'    => explode(';',
                                 $type),
            'null'    => $null,
            'default' => $default,
            'comment' => $comment
        ];

    }

    /**
     * Get All  Keys
     *
     * @return array Keys Get All Keys
     */
    public function getPrimarys()
    {
        return $this->structurePrimary;
    }

    /**
     * Get All  Unique
     *
     * @return array Get All Unique
     */
    public function getUniques(): array
    {
        return $this->structureUnique;
    }

    /**
     * Get All  Keys
     *
     * @return array Keys Get All Keys
     */
    public function getKeys(): array
    {
        return $this->structureKey;
    }


}
