<?php

/**
 * Class ContainerFactoryDatabaseQuery
 * @method ePDOStatement execute()
 */

class ContainerFactoryDatabaseQuery extends Base
{

    const MODE_SELECT        = 'select';
    const MODE_REPLACE       = 'replace';
    const MODE_INSERT        = 'insert';
    const MODE_DELETE        = 'delete';
    const MODE_TRUNCATE      = 'truncate';
    const MODE_UPDATE        = 'update';
    const MODE_INSERT_UPDATE = 'insert_update';
    const MODE_OTHER         = 'other';
    const SELECT_JOIN        = 'JOIN';
    const SELECT_LEFT_JOIN   = 'LEFT JOIN';
    const SELECT_RIGHT_JOIN  = 'RIGHT JOIN';
    const SELECT_OUTER_JOIN  = 'OUTER JOIN';
    const SELECT_INNER_JOIN  = 'INNER JOIN';

    protected static array $tableStructure            = [];
    protected string       $name                      = '';
    protected              $smtp;
    protected string       $mode                      = '';
    protected string       $databaseConnection        = 'primary';
    protected              $databaseConnectionHandler = null;
    protected string       $table                     = '';
    protected ?string      $tableAlias                = null;
    protected ?int         $lastId                    = null;
    protected string       $query                     = '';
    protected string       $queryAdd                  = '';
    protected array        $select                    = [];
    protected array        $selectFunction            = [];
    protected float        $selectExplain             = 0;
    protected array        $update                    = [];
    protected array        $insert                    = [];
    protected array        $insertUpdate              = [];
    protected array        $replace                   = [];
    protected array        $where                     = [];
    protected string       $limit                     = '';
    protected array        $orderBy                   = [];
    protected array        $groupBy                   = [];
    protected array        $parameter                 = [];
    protected int          $counter                   = 0;
    protected int          $rowCount                  = 0;
    protected int          $parameterCount            = 0;
    protected string       $tableKey                  = '';
    protected bool         $automaticSetData          = true;
    protected bool         $optionInsertUpdateIgnore  = false;
    protected bool         $constructed               = false;

    public function __construct(string $name, $databaseConnection, string $mode = self::MODE_OTHER)
    {
        $this->name = $name;
        $this->mode = $mode;

        if ($databaseConnection !== true) {
            $this->databaseConnection = $databaseConnection;
        }

    }

    public function setParameterWhereIn(string $key, array $value, string $table = null): void
    {
        $in = [];
        foreach ($value as $valueKey => $valueItem) {
            $keyCounter = ':in_' . ($this->parameterCount++) . '_' . $valueKey;
            $in[]       = $keyCounter;

            $this->parameter[$keyCounter] = [
                'value' => $valueItem,
                'type'  => $this->setParameterType($value),
            ];
        }

        if (count($in) > 0) {
            $this->setWhere($key . ' IN (' . implode(', ',
                                                     $in) . ')');
        }

        unset($in);
    }

    protected function setParameterType($parameterItem, ?string $type = null)
    {
        if ($type === null) {
            switch (gettype($parameterItem)) {
                case 'string':
                default:
                    $type = PDO::PARAM_STR;
                    break;
                case 'integer':
                case 'double':
                    $type = PDO::PARAM_INT;
                    break;
                case 'boolean':
                    $type = PDO::PARAM_BOOL;
                    break;
                case 'NULL':
                    $type = PDO::PARAM_NULL;
                    break;
            }

        }

        return $type;
    }

    public function setWhere(string $where): void
    {
        $this->where[] = [
            'where' => $where,
            'type'  => 0,
        ];
    }

    public function setParameterWhereLike(string $key, string $value, ?string $type = null, ?string $table = null): void
    {
        $type       = $this->setParameterType($value,
                                              $type);
        $keyCounter = strtr($key,
                            [
                                '.' => '_',
                            ]) . '_' . (++$this->parameterCount);

        $this->whereLike($key . ' LIKE :' . $keyCounter,
                         $table);

        $this->parameter[$keyCounter] = [
            'value' => $value,
            'type'  => $type,
        ];

    }

    public function whereLike(string $where, ?string $table = null): void
    {
        $tableChange = (($this->tableAlias === null ?? null) ? '`' . $this->table . '`' : $this->tableAlias);

        $wherePointPos = strpos($where,
                                '.');
        if ($wherePointPos === false) {
            $this->where[] = [
                'where' => (($table === null) ? $tableChange : '`' . $table . '`') . '.' . $where,
                'type'  => 0,
            ];
        }
        else {
            if ($wherePointPos > 0) {
                $this->where[] = [
                    'where' => $where,
                    'type'  => 0,
                ];
            }
            else {
                $this->where[] = [
                    'where' => substr($where,
                                      1),
                    'type'  => 0,
                ];
            }
        }
    }

    public function setMode(string $mode): void
    {
        $this->mode = $mode;
    }

    public function getDbname(?string $dbConstant = null): string
    {
        /** @var ContainerFactoryDatabase $database */
        $database = Container::getInstance('ContainerFactoryDatabase');

        return $database->getDbname($dbConstant);
    }

    public function query(string $query): void
    {
        $this->query .= $query;
    }

    public function select(...$select): void
    {
        foreach ($select as $selectItem) {
            $this->select[] = '`' . $this->table . '`' . '.' . $selectItem;
        }
    }

    public function getSelect(): array
    {
        return $this->select;
    }

    public function selectRaw(string $select): void
    {
        $this->select[] = $select;
    }

    /**
     * @param string $select
     * @param        $table
     *
     * @deprecated
     */
    public function setSelect(string $select, ?string $table): void
    {
        if ($table === null) {
            $this->select[] = $select;
        }
        else {
            $this->select[] = '`' . $table . '`' . '.' . $select;
        }
    }

    public function addSelect(...$select): void
    {
        $this->select = array_merge($this->select,
                                    $select);
    }

    /**
     * @return array
     */
    public function getSelectFunction(): array
    {
        return $this->selectFunction;
    }

    public function orderBy(string $orderBy, bool $addTableInfo = true): void
    {
        $this->orderBy[] = (($addTableInfo === true && strpos($orderBy,
                                                              '.') === false) ? '`' . $this->table . '`' . '.' : '') . $orderBy;
    }

    public function orderByRand(): void
    {
        $this->orderBy[] = ' RAND()';
    }

    public function groupBy(string $groupBy, bool $addTableInfo = true): void
    {
        $this->groupBy[] = (($addTableInfo === true && strpos($groupBy,
                                                              '.') === false) ? '`' . $this->table . '`' . '.' : '') . $groupBy;
    }

    public function join($table, array $select, string $join, string $type = 'LEFT JOIN'): void
    {
        if (!is_array($table)) {
            $tableName  = $table;
            $tableJoin  = '`' . $table . '` AS ' . $table;
            $tableAlias = $table;
        }
        else {
            $tableName  = $table[1];
            $tableJoin  = '`' . $table[0] . '` AS ' . $table[1];
            $tableAlias = $table[1];
        }

        foreach ($select as $selectItem) {
            $this->selectRaw($tableAlias . '.' . $selectItem . ' AS ' . $tableName . '_' . $selectItem);
        }

        $this->queryAdd .= ' ' . $type . ' ' . $tableJoin . ' ON ' . $join;
    }

    public function whereModify(string $value): void
    {
        $value = strtoupper(trim($value));
        switch ($value) {
            case '(':
                $this->where[] = [
                    'where' => ' ( ',
                    'type'  => 1,
                ];
                break;
            case 'AND':
            case 'OR':
                $this->where[] = [
                    'where' => ' ' . $value . ' ',
                    'type'  => 2,
                ];
                break;
            case ')':
                $this->where[] = [
                    'where' => ' ) ',
                    'type'  => 3,
                ];
                break;
        }
    }

    public function getQuery(): string
    {
        if ($this->constructed === false) {
            $this->construct();
        }
        return $this->query;
    }

    public function construct(): void
    {
        if ($this->mode !== self::MODE_OTHER && empty($this->table)) {
            throw new DetailedException('noTable');
        }

        switch ($this->mode) {
            case self::MODE_SELECT:
                $this->constructSelect();
                break;
            case self::MODE_INSERT:
                $this->constructInsertInto();
                break;
            case self::MODE_REPLACE:
                $this->constructReplaceInto();
                break;
            case self::MODE_DELETE:
                $this->constructdeleteFrom();
                break;
            case self::MODE_TRUNCATE:
                $this->constructTruncate();
                break;
            case self::MODE_UPDATE:
                $this->constructUpdate();
                break;
            case self::MODE_INSERT_UPDATE:
                $this->constructInsertUpdate();
                break;
        }

        $this->constructed = true;
    }

    protected function constructSelect(): void
    {

        $alias = (($this->tableAlias === null) ? '`' . $this->table . '`' : $this->tableAlias);

        $tableData = [];
        if ($this->automaticSetData === true) {
            $tableData = $this->getTableStructure($this->table);
            if ($tableData['dataVariableCreated'] === true) {
                $this->select[] = '`' . $this->table . '`' . '.dataVariableCreated';
            }
            if ($tableData['dataVariableEdited'] === true) {
                $this->select[] = '`' . $this->table . '`' . '.dataVariableEdited';
            }
            if ($tableData['dataVariableEditedCounter'] === true) {
                $this->select[] = '`' . $this->table . '`' . '.dataVariableEditedCounter';
            }
            if ($tableData['dataVariableReport'] === true) {
                $this->select[] = '`' . $this->table . '`' . '.dataVariableReport';
            }

            if ($tableData['dataVariableDeleted'] === true) {
                $this->setParameterWhere('dataVariableDeleted',
                                         '0');
            }
        }

        $selectFunction = '';
        if (!empty($this->selectFunction)) {
            $selectFunction = ((!empty($this->select)) ? ', ' : '') . implode(',',
                                                                              $this->selectFunction);
        }

        $this->query = 'SELECT ' . implode(', ',
                                           $this->select) . $selectFunction . ' FROM `' . $this->table . '` AS ' . $alias . ' ' . $this->queryAdd;
        if (count($this->where) > 0) {
            $this->query .= ' WHERE ' . $this->whereConstruct();
        }
        if (count($this->groupBy) > 0) {
            $this->query .= ' GROUP BY ' . implode(', ',
                                                   $this->groupBy);
        }
        if (count($this->orderBy) > 0) {
            $this->query .= ' ORDER BY ' . implode(', ',
                                                   $this->orderBy);
        }

        $this->query .= $this->limit;

        $this->constructed = true;
    }

    protected function getTableStructure(string $table): array
    {
        $configConnection = (string)Config::get('/environment/database/' . $this->databaseConnection . '/type',
                                                false);

        if ($configConnection == false) {
            throw new DetailedException('configTypeNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'name' => $this->databaseConnection,
                                            ]
                                        ]);
        }

        $connectionEngine = 'ContainerFactoryDatabaseEngine' . ucfirst($configConnection);
        /** @var ContainerFactoryDatabaseEngine_abstract $connectionEngine */
        return [
            'dataVariableCreated'       => $connectionEngine::tableColumnIsInDatabase($this->databaseConnection,
                                                                                      $table,
                                                                                      'dataVariableCreated'),
            'dataVariableEdited'        => $connectionEngine::tableColumnIsInDatabase($this->databaseConnection,
                                                                                      $table,
                                                                                      'dataVariableEdited'),
            'dataVariableUpdated'       => $connectionEngine::tableColumnIsInDatabase($this->databaseConnection,
                                                                                      $table,
                                                                                      'dataVariableUpdated'),
            'dataVariableEditedCounter' => $connectionEngine::tableColumnIsInDatabase($this->databaseConnection,
                                                                                      $table,
                                                                                      'dataVariableEditedCounter'),
            'dataVariableDeleted'       => $connectionEngine::tableColumnIsInDatabase($this->databaseConnection,
                                                                                      $table,
                                                                                      'dataVariableDeleted'),
            'dataVariableReport'        => $connectionEngine::tableColumnIsInDatabase($this->databaseConnection,
                                                                                      $table,
                                                                                      'dataVariableReport'),
        ];


    }

    protected function getConnection(): ?ePDO
    {
        if ($this->databaseConnectionHandler === null) {
            /** @var ContainerFactoryDatabase $database */
            $database                        = Container::getInstance('ContainerFactoryDatabase');
            $this->databaseConnectionHandler = $database->get($this->databaseConnection);
        }
        return $this->databaseConnectionHandler;
    }

    public function setParameterWhere(string $key, $value, ?string $type = null, string $set = '='): void
    {
        $type     = $this->setParameterType($value,
                                            $type);
        $pointpos = strpos($key,
                           '.');

        if ($pointpos === false) {
            $alias      = (($this->tableAlias === null) ? $this->table : $this->tableAlias);
            $keyCounter = $alias . '.' . $key . '_' . (++$this->parameterCount);
        }
        else {
            $keyCounter = $key . '_' . (++$this->parameterCount);
        }

        if ($value === null) {
            if ($set === '=') {
                $set = ' IS ';
            }
            elseif ($set === '!=') {
                $set = ' IS NOT ';
            }
        }

        $keyCounter = strtr($keyCounter,
                            [
                                '.' => '_',
                            ]);

        $this->setWhere($key . ' ' . $set . ' :' . $keyCounter);

        $this->parameter[$keyCounter] = [
            'value' => $value,
            'type'  => $type,
        ];
    }

    protected function whereConstruct(): string
    {
        $where       = '';
        $whereLastId = (count($this->where) - 1);

        foreach ($this->where as $whereKey => $whereValue) {
            $where .= ' ' . $whereValue['where'] . ' ';
            if (isset($this->where[($whereKey + 1)]) && $this->where[($whereKey + 1)]['type'] < 2 && $whereValue['type'] === 0 && $whereKey !== $whereLastId) {
                $where .= ' AND ';
            }
        }

        return $where;
    }


    protected function constructInsertInto(): void
    {
        $insertKeys = array_keys(reset($this->insert));

        $tableData = $this->getTableStructure($this->table);

        if ($this->automaticSetData === true) {

            if ($tableData['dataVariableCreated'] === true) {
                $insertKeys[] = 'dataVariableCreated';
            }

            if ($tableData['dataVariableEdited'] === true) {
                $insertKeys[] = 'dataVariableEdited';
            }

        }

        $this->query = 'INSERT ' . (($this->optionInsertUpdateIgnore === false) ? '' : 'IGNORE') . ' INTO ' . '`' . $this->table . '`' . ' (' . implode(',',
                                                                                                                                                        $insertKeys) . ') VALUES ';
        $parameter   = [];

        foreach ($this->insert as $insertValue) {
            if ($tableData['dataVariableCreated'] === true) {
                $dbaseDate                          = '"' . \Config::get('/cms/date/parsed/dbase') . '"';
                $insertValue['dataVariableCreated'] = $dbaseDate;
            }

            if ($tableData['dataVariableEdited'] === true) {
                $dbaseDate                         = '"' . \Config::get('/cms/date/parsed/dbase') . '"';
                $insertValue['dataVariableEdited'] = $dbaseDate;
            }

            $parameter[] = '(' . implode(',',
                                         $insertValue) . ')' . PHP_EOL;
        }

        $this->query .= implode(', ',
                                $parameter);
    }

    protected function constructReplaceInto(): void
    {
        $dbaseDate   = '"' . \Config::get('/cms/date/parsed/dbase') . '"';#
        $replaceKeys = array_keys(reset($this->replace));
        $tableData   = $this->getTableStructure($this->table);

        if ($this->automaticSetData === true) {

            if ($tableData['dataVariableCreated'] === true) {
                $replaceKeys[] = 'dataVariableCreated';
            }
            if ($tableData['dataVariableEdited'] === true) {
                $replaceKeys[] = 'dataVariableEdited';
            }
        }

        $this->query = 'REPLACE INTO `' . $this->table . '` (' . implode(',',
                                                                         $replaceKeys) . ') VALUES ';
        $parameter   = [];
        foreach ($this->replace as $insertValue) {
            if ($tableData['dataVariableCreated'] === true) {
                $insertValue['dataVariableCreated'] = $dbaseDate;
            }
            if ($tableData['dataVariableEdited'] === true) {
                $insertValue['dataVariableEdited'] = $dbaseDate;
            }

            $parameter[] = '(' . implode(',',
                                         $insertValue) . ')' . PHP_EOL;
        }

        $this->query .= implode(', ',
                                $parameter);
    }

    protected function constructDeleteFrom(): void
    {
        $this->query = 'DELETE FROM `' . $this->table . '`';
        if (count($this->where) > 0) {
            $this->query .= ' WHERE ' . $this->whereConstruct();
        }
    }

    protected function constructTruncate(): void
    {
        $this->query = 'TRUNCATE `' . $this->table . '`';
    }

    protected function constructUpdate(): void
    {
        $tableData = $this->getTableStructure($this->table);
        if ($this->automaticSetData === true) {
            if ($tableData['dataVariableEdited'] === true) {
                $this->setUpdate('dataVariableEdited',
                                 \Config::get('/cms/date/parsed/dbase'));
            }

            if ($tableData['dataVariableUpdated'] === true) {
                $this->setUpdate('dataVariableUpdated',
                                 \Config::get('/cms/date/parsed/dbase'));
            }
            if ($tableData['dataVariableEditedCounter'] === true) {
                $this->setUpdate('dataVariableEditedCounter',
                                 'dataVariableEditedCounter + 1',
                                 true);
            }
        }

        $this->query = 'UPDATE  `' . $this->table . '` SET ' . implode(', ',
                                                                       $this->update);
        if (count($this->where) > 0) {
            $this->query .= ' WHERE ' . $this->whereConstruct();
        }

        $this->query .= $this->limit;
    }

    protected function constructInsertUpdate(): void
    {
        $tableData = $this->getTableStructure($this->table);

        if ($this->automaticSetData === true) {
            if ($tableData['dataVariableCreated'] === true) {
                $this->setInsertUpdate('dataVariableCreated',
                                       \Config::get('/cms/date/parsed/dbase'));
            }
            if ($tableData['dataVariableEdited'] === true) {
                $this->setInsertUpdate('dataVariableEdited',
                                       \Config::get('/cms/date/parsed/dbase'),
                                       \Config::get('/cms/date/parsed/dbase'));
            }

            if ($tableData['dataVariableUpdated'] === true) {
                $this->setInsertUpdate('dataVariableUpdated',
                                       \Config::get('/cms/date/parsed/dbase'),
                                       \Config::get('/cms/date/parsed/dbase'));
            }
            if ($tableData['dataVariableEditedCounter'] === true) {
                $this->setInsertUpdate('dataVariableEditedCounter',
                                       0,
                                       'dataVariableEditedCounter + 1',
                                       true);
            }
        }


        //

        $insertKeys  = implode(', ',
                               array_keys($this->insertUpdate));
        $queryInsert = [];
        $queryUpdate = [];

        foreach ($this->insertUpdate as $insertUpdateKey => $insertUpdateItem) {

            $queryInsert[] = $this->getSetParameter($insertUpdateKey,
                                                    $insertUpdateItem['insert']);

            if ($insertUpdateItem['update'] !== false) {
                if ($insertUpdateItem['spezial'] === false) {
                    $queryUpdate[$insertUpdateKey] = $this->getSetParameter($insertUpdateKey,
                                                                            $insertUpdateItem['update']);
                }
                else {
                    $queryUpdate[$insertUpdateKey] = $insertUpdateItem['update'];
                }
            }
        }

        $this->query = 'INSERT INTO `' . $this->table . '` (' . $insertKeys . ') VALUES ';
        $this->query .= '(' . implode(', ',
                                      $queryInsert) . ')';

        $configConnection = \Config::get('/environment/database/' . $this->databaseConnection . '/type',
                                         false);

        if ($configConnection === false) {
            throw new DetailedException('configTypeNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'name' => $this->databaseConnection,
                                            ]
                                        ]);
        }

        if ($configConnection === 'mysql') {
            $this->query .= ' ON DUPLICATE KEY UPDATE ';
        }
        elseif ($configConnection === 'sqlite') {
            $this->query .= ' ON CONFLICT(' . $this->tableKey . ') DO UPDATE SET ';
        }

        $queryUpdateToImplode = [];

        foreach ($queryUpdate as $queryUpdateKey => $queryUpdateItem) {
            $queryUpdateToImplode[] = $queryUpdateKey . ' = ' . $queryUpdateItem;
        }
        $this->query .= implode(',',
                                $queryUpdateToImplode);
    }

    public function setInsertUpdate(string $key, $insert, $update = false, bool $spezial = false): void
    {
        if ($update === true) {
            $update = $insert;
        }

        $this->insertUpdate[$key] = [
            'insert'  => $insert,
            'update'  => $update,
            'spezial' => $spezial,
        ];
    }

    public function getSetParameter(string $key, $value, int $counter = 0, ?string $type = null): string
    {
        $this->setParameter($key,
                            $value);
        return ':' . $key . '_' . $this->getlastParameterCount();
    }

    public function setParameter(string $key, $value, int $counter = 0, ?string $type = null): void
    {
        $type = $this->setParameterType($value,
                                        $type);

        $pointpos = strpos($key,
                           '.');
        if ($pointpos === false) {
            $keyCounter = $key . '_' . (++$this->parameterCount);
        }
        else {
            $keyCounter = substr($key,
                    ($pointpos + 1)) . '_' . (++$this->parameterCount);
        }

        $this->parameter[$keyCounter] = [
            'value' => $value,
            'type'  => $type,
        ];
    }

    public function getlastParameterCount(): int
    {
        return $this->parameterCount;
    }

    public function getQueryParsed(): string
    {
        if ($this->constructed === false) {
            $this->construct();
        }

        $query = '';
        foreach ($this->parameter as $parameterKey => $parameterValue) {
            $this->query = str_replace(':' . $parameterKey,
                                       '"' . $parameterValue['value'] . '"',
                                       $this->query);
        }

        return $this->query;
    }

    public function getParameter(): array
    {
        return $this->parameter;
    }

    public function setReplaceInto(string $key, $value, int $row = 0): void
    {
        $this->setParameter($key,
                            $value);
        $this->replace[$row][$key] = ':' . $key . '_' . $this->getlastParameterCount();
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function setLimit(int $count, ?int $offset = null): void
    {
        if ($offset === null) {
            $this->limit = ' LIMIT ' . $count;
        }
        else {
            $this->limit = ' LIMIT ' . $offset . ', ' . $count;
        }
    }

    //    protected function constructReplaceInto() {
    //        $this->query = 'REPLACE INTO ' . $this->table . ' (' . implode(',', array_keys($this->replace)) . ') VALUES (' . implode(',', $this->replace) . ')';
    //        return $this;
    //    }

    public function insertUpdateByArray(string $idKey, array $dataInsertUpdate, array $spezial = []): void
    {
        foreach ($dataInsertUpdate as $dataInsertKey => $dataInsertValue) {
            $this->setInsertInto($dataInsertKey,
                                 $dataInsertValue);
        }
        unset($dataInsertKey, $dataInsertValue);

        unset($dataInsertUpdate[$idKey]);
        foreach ($dataInsertUpdate as $dataUpdateKey => $dataUpdateValue) {
            $this->setUpdate($dataUpdateKey,
                             $dataUpdateValue,
                ($spezial[$dataUpdateKey] ?? false));
        }
        unset($dataUpdateKey, $dataUpdateValue);
    }

    public function setInsertInto(string $key, $value, int $row = 0): void
    {
        $this->setParameter($key,
                            $value);
        $this->insert[$row][$key] = ':' . $key . '_' . $this->getlastParameterCount();
    }

    public function setUpdate(string $key, $value, bool $spezial = false): void
    {
        if ($spezial === false) {
            $this->setParameter($key,
                                $value);
            $this->update[] = $key . ' = :' . $key . '_' . $this->getlastParameterCount();
        }
        else {
            $this->update[] = $key . ' = ' . $value;
        }

    }

    public function setInsertIntoMultiple(array $array): void
    {
//        $arrayChunk = array_chunk($array, \Config::get('/environment/database/master/collect/insert_replace'));

        foreach ($array as $arrayChunkItemValueRowNr => $arrayChunkItemValue) {
            foreach ($arrayChunkItemValue as $arrayChunkItemValueToInsertKey => $arrayChunkItemValueToInsert) {
                $this->setInsertUpdate($arrayChunkItemValueToInsertKey,
                                       $arrayChunkItemValueToInsert,
                                       $arrayChunkItemValueRowNr);
            }
        }
    }

    public function setInsertUpdateMultiple(array $array): void
    {
//        $arrayChunk = array_chunk($array, \Config::get('/environment/database/master/collect/insert_replace'));

        foreach ($array as $arrayChunkItemValueRowNr => $arrayChunkItemValue) {
            foreach ($arrayChunkItemValue as $arrayChunkItemValueToInsertKey => $arrayChunkItemValueToInsert) {
                $this->setInsertUpdate($arrayChunkItemValueToInsertKey,
                                       $arrayChunkItemValueToInsert,
                                       $arrayChunkItemValueRowNr);
            }
        }
    }

    public function getLastID(): ?int
    {
        return $this->lastId ?? null;
    }

    public function count(): int
    {
        $query = clone $this;

        $query->resetLimit();
        $query->resetSelect();
        $query->selectFunction(' COUNT(*) AS count ');
        $query->construct();
        $smtp  = $query->execute();
        $count = $smtp->fetch();

        return (int)$count['count'];

    }

    /**
     * @param string $get
     */
    public function selectFunction(string $get): void
    {
        $this->selectFunction[] = $get;
    }

    public function resetSelect(): void
    {
        $this->selectFunction = [];
        $this->select         = [];
    }

    public function resetLimit(): void
    {
        $this->limit = '';
    }


    /**
     * Execute the Query
     *
     * Execute the constructed Query
     *
     * @CMSprofilerSetFromScope table
     * @CMSprofilerSetFromScope databaseConnection
     * @CMSprofilerSetFromScope query
     * @CMSprofilerSetFromScope selectExplainData
     * @CMSprofilerSetFromScope parameter
     * @CMSprofilerSetFromScope lastId
     * @CMSprofilerSetFromScope rowCount
     * @CMSprofilerSetFromScope backtrace
     * @CMSprofilerOption       isFunction true
     * @CMSprofilerOption       deph 2
     *
     * @param array $scope
     *
     * @return mixed
     * @throws DetailedException
     */
    public function _execute(array &$scope)
    {
        try {
            $selectExplainData = '';
            if (
                $this->mode === self::MODE_SELECT && Config::get('/environment/debug/sqlExplain',
                                                                 0) == 1
            ) {
                $selectExplainData = $this->executeSelectExplain($this->getConnection());
                //            debugDump($selectExplainData);
            }

            $scope['table']              = $this->table;
            $scope['databaseConnection'] = $this->databaseConnection;
            $scope['query']              = $this->query;
            $scope['parameter']          = $this->parameter;
            $scope['selectExplainData']  = $selectExplainData;

            $connection = $this->getConnection();
            $smtp       = $connection->prepare($this->query);

            foreach ($this->parameter as $queryParameterKey => $queryParameterQueryCount) {
                $smtp->bindValue($queryParameterKey,
                                 $queryParameterQueryCount['value'],
                                 $queryParameterQueryCount['type']);
            }

            $smtp->execute();

            $this->rowCount    = $smtp->rowCount();
            $scope['rowCount'] = $this->rowCount;

            switch ($this->mode) {
                case self::MODE_INSERT:
                case self::MODE_INSERT_UPDATE:
                case self::MODE_REPLACE:

                    $this->lastId = $connection->lastInsertId();
                    break;
            }
            $scope['lastId']    = $this->lastId;
            $scope['backtrace'] = ((\Config::get('/debug/status',
                                                 CMS_DEBUG_ACTIVE) === false) ? '' : debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

            $this->smtp = $smtp;

        } catch (DetailedException $exception) {
            debugDump($exception);
            throw $exception;
        } catch (Throwable $exception) {
            d($exception);
            die();

            throw new DetailedException('queryExecuteError',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                $exception->getMessage(),
                                                $exception->getMessage(),
                                                $this->query,
                                                $this->parameter
                                            ]
                                        ]);
        }

        return $smtp;
    }

    protected function executeSelectExplain(ePDO $connection): array
    {

        $timeQuery = microtime(true);
        if (
            strpos(Config::get('/environment/database/' . $this->databaseConnection . '/dsn'),
                   'mysql') !== false
        ) {
            $smtp = $connection->prepare('EXPLAIN EXTENDED ' . $this->query);
        }
        else {
            $smtp = $connection->prepare('EXPLAIN ' . $this->query);

        }

        foreach ($this->parameter as $queryParameterKey => $queryParameterQueryCount) {
            $smtp->bindValue($queryParameterKey,
                             $queryParameterQueryCount['value'],
                             $queryParameterQueryCount['type']);
        }

        $smtp->execute();
        $this->selectExplain = microtime(true) - $timeQuery;

        return $smtp->fetchAll();
    }

    public static function massInsertInto(string $name, $connection, string $table, array $data): void
    {

        $keyData = array_keys($data[0]);

        $dataSplit = array_chunk($data,
                                 100);

        foreach ($dataSplit as $dataSplitItem) {

            $query = new self($name,
                              $connection);

            $queryValues = [];
            foreach ($dataSplitItem as $dataSplitItemValues) {
                $queryValuesItemCollect = [];
                foreach ($dataSplitItemValues as $dataSplitItemValuesItem) {
                    $queryValuesItemCollect[] = $query->getSetParameter('massInsertInto',
                                                                        $dataSplitItemValuesItem);
                }

                $queryValues[] = '(' . implode(',',
                                               $queryValuesItemCollect) . ')';
            }


            $query->query('INSERT INTO ' . $table . ' (' . implode(',',
                                                                   $keyData) . ') VALUES ' . implode(',',
                                                                                                     $queryValues));;

            $query->execute();

//            d($query->getQuery());
//            d($query->getP());

//            $queryValues = '`' . implode('`,`',
//                                         $dataSplitItemValues) . '`';

        }
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function setTable(string $table, ?string $alias = null): void
    {
        $this->table      = $table;
        $this->tableAlias = $alias;
    }

    public function getFetch(): Generator
    {
        while ($row = $this->smtp->fetch()) {
            yield $row;
        }
    }

    public function getFetchAll(): array
    {
        return $this->smtp->fetchAll();
    }

    /**
     * @return string
     */
    public function getTableKey(): string
    {
        return $this->tableKey;
    }

    /**
     * @param string $tableKey
     */
    public function setTableKey(string $tableKey): void
    {
        $this->tableKey = $tableKey;
    }

    /**
     * @return bool
     */
    public function getAutomaticSetData(): bool
    {
        return $this->automaticSetData;
    }

    /**
     * @param bool $automaticSetData
     */
    public function setAutomaticSetData(bool $automaticSetData): void
    {
        $this->automaticSetData = $automaticSetData;
    }

    /**
     * @return bool
     */
    public function isOptionInsertUpdateIgnore(): bool
    {
        return $this->optionInsertUpdateIgnore;
    }

    /**
     * @param bool $optionInsertUpdateIgnore
     */
    public function setOptionInsertUpdateIgnore(bool $optionInsertUpdateIgnore = false): void
    {
        $this->optionInsertUpdateIgnore = $optionInsertUpdateIgnore;
    }

    /**
     * @return int
     */
    public function getParameterCount(): int
    {
        return $this->parameterCount;
    }
}
