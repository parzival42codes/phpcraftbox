<?php

abstract class Base_abstract_crud
{
    protected static string $table                     = '';
    protected static        $database                  = true;
    protected static array  $reflectionProperty        = [];
    protected static string $tableId                   = '';
    private string          $dataVariableCreated       = '';
    private string          $dataVariableEdited        = '';
    private int             $dataVariableEditedCounter = 0;
    private string          $dataVariableDeleted       = '';
    /**
     * @var array
     */
    private array $additionalQuerySelect = [];

    public function __construct(array $data = [])
    {
        if (empty(static::$table) || empty(static::$tableId)) {
            throw new DetailedException('tableValueOrTableIdValueIsEmpty',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                get_called_class(),
                                                static::$table,
                                                static::$tableId,
                                            ]
                                        ]);
        }

        foreach ($data as $dataItem) {
            if (
            property_exists(get_called_class(),
                            $dataItem)
            ) {
                $this->$dataItem = $data[$dataItem];
            }
        }

        if (empty(static::$reflectionProperty) || empty(static::$reflectionProperty[get_called_class()])) {
            $reflection = new \ReflectionClass(get_called_class());

            foreach ($reflection->getProperties() as $reflectionProperty) {
                if (
                    strpos($reflectionProperty->getName(),
                           'crud') === 0
                ) {
                    static::$reflectionProperty[get_called_class()][] = $reflectionProperty->getName();
                }
            }
        }
    }

    /**
     * @return string
     */
    public static function getTableId(): string
    {
        return static::$tableId;
    }

    public function getProperties(): array
    {
        return static::$reflectionProperty[get_called_class()];
    }

    public function update(): string
    {
        /** @var ContainerFactoryDatabaseQuery $queryItem */
        $queryItem = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__ . '#insertUpdate',
                                    static::$database,
                                    \ContainerFactoryDatabaseQuery::MODE_UPDATE);

        $queryItem->setTable(static::$table);

        $crudActionData = [];
        foreach (static::$reflectionProperty[get_called_class()] as $property) {
            $crudActionData[] = $this->$property;
            $queryItem->setUpdate($property,
                                  $this->$property);
        }

        $id = call_user_func([
                                 $this,
                                 'get' . ucfirst(static::$tableId)
                             ]);
        $queryItem->setParameterWhere(static::$tableId,
                                      $id);

        $queryItem->construct();
        $queryItem->execute();

        return 'CRUD: ' . get_called_class() . ': Update: ' . implode(' | ',
                                                                      $crudActionData);

    }

    public function insert(bool $ignore = false): string
    {
        /** @var ContainerFactoryDatabaseQuery $queryItem */
        $queryItem = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__ . '#insertUpdate',
                                    static::$database,
                                    \ContainerFactoryDatabaseQuery::MODE_INSERT);

        $queryItem->setTable(static::$table);

        $crudActionData = [];
        foreach (static::$reflectionProperty[get_called_class()] as $property) {
            $crudActionData[] = $this->$property;
            $queryItem->setInsertInto($property,
                                      $this->$property,
                                      0);
        }

        $queryItem->setOptionInsertUpdateIgnore($ignore);
        $queryItem->construct();
        $queryItem->execute();

        $tableId = static::$tableId;
        if ($this->$tableId === null) {
            call_user_func([
                               $this,
                               'set' . ucfirst(static::$tableId)
                           ],
                           $queryItem->getLastID());
        }

        return 'CRUD: ' . get_called_class() . ': Insert: ' . implode(' | ',
                                                                      $crudActionData);

    }

    public function insertUpdate(): string
    {
        /** @var ContainerFactoryDatabaseQuery $queryItem */
        $queryItem = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__ . '#insertUpdate',
                                    static::$database,
                                    \ContainerFactoryDatabaseQuery::MODE_INSERT_UPDATE);

        $queryItem->setTable(static::$table);

        $crudActionData = [];

        foreach (static::$reflectionProperty[get_called_class()] as $property) {
            $crudActionData[] = $this->$property;
            $queryItem->setInsertUpdate($property,
                                        $this->$property,
                                        true);
        }

        $queryItem->construct();
        $queryItem->execute();

        return 'CRUD: ' . get_called_class() . ': Insert - Update: ' . implode(' | ',
                                                                               $crudActionData);

    }

    public function findById(bool $exception = false): bool
    {
        return $this->findByColumn(static::$tableId);
    }


    public function findByColumn($columnList, bool $exception = false): bool
    {

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                static::$database,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);

        $query->setTable(static::$table);

        foreach (static::$reflectionProperty[get_called_class()] as $crudItem) {
            $query->select($crudItem);
        }

        if (is_string($columnList)) {
            $columnList = [
                $columnList
            ];
        }

        foreach ($columnList as $column) {
            $id = call_user_func([
                                     $this,
                                     'get' . ucfirst($column)
                                 ]);

            $query->setParameterWhere(static::$table . '.' . $column,
                                      $id);
        }

        $query = $this->modifyFindQuery($query);

        $query->setLimit(1);
        $query->construct();
        $smtp = $query->execute();

        $dbData = $smtp->fetch();

        if (empty($dbData)) {

            if ($exception === false) {
                return false;
            }
            else {
                $id = call_user_func([
                                         $this,
                                         'get' . ucfirst(static::$tableId)
                                     ]);

                throw new DetailedException('idNotFound',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    'id' => $id,
                                                ]
                                            ]);
            }
        }

        if (is_array($dbData)) {
            foreach ($dbData as $key => $value) {
                if (
                    strpos($key,
                           'crud') === 0 || $key === 'dataVariableCreated' || $key === 'dataVariableEdited' || $key === 'dataVariableEditedCounter' || $key === 'dataVariableDeleted'
                ) {

                    call_user_func([
                                       $this,
                                       'set' . ucfirst($key)
                                   ],
                                   $value);
                }
                else {
                    $this->setAdditionalQuerySelect((string)$key,
                                                    $value);
                }
            }
        }

        return true;
    }

    public function find(array $where = [], array $group = [], array $order = [], int $limit = null, int $offset = null): array
    {

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                static::$database,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);

        $query->setTable(static::$table);

        foreach (static::$reflectionProperty[get_called_class()] as $crudItem) {
            $query->select($crudItem);
        }

        foreach ($where as $whereKey => $whereItem) {
            if (
                str_contains($whereItem,
                             '%') === false
            ) {
                $query->setParameterWhere($whereKey,
                                          $whereItem);
            }
            else {
                $query->setParameterWhereLike($whereKey,
                                              $whereItem);
            }
        }

        foreach ($group as $groupItem) {
            $query->groupBy($groupItem);
        }

        foreach ($order as $orderItem) {
            $query->orderBy($orderItem);
        }

        if ($limit !== null) {
            $query->setLimit($limit,
                             $offset);
        }

        $query = $this->modifyFindQuery($query);

        $query->construct();
        $smtp = $query->execute();;

        $cruds = [];

        while ($smtpData = $smtp->fetch()) {
            /** @var Base_abstract_crud $crudItem */
            $crudItem = Container::get(get_called_class());

            foreach ($smtpData as $key => $value) {
                if (
                    strpos($key,
                           'crud') === 0 || $key === 'dataVariableCreated' || $key === 'dataVariableEdited' || $key === 'dataVariableEditedCounter' || $key === 'dataVariableDeleted'
                ) {
                    call_user_func([
                                       $crudItem,
                                       'set' . ucfirst($key)
                                   ],
                                   $value);
                }
                else {
                    $crudItem->setAdditionalQuerySelect($key,
                                                        $value);
                }

            }

            $cruds[] = $crudItem;
        }

        return $cruds;
    }

    /**
     * @param ContainerFactoryDatabaseQuery $query
     *
     * @return ContainerFactoryDatabaseQuery
     */
    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        return $query;
    }

    public function count(array $where = [], array $group = []): int
    {

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                static::$database,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);

        $query->setTable(static::$table);

        $query->selectRaw('count(*) as c');

        foreach ($where as $whereKey => $whereItem) {
            $query->setParameterWhere($whereKey,
                                      $whereItem);
        }

        foreach ($group as $groupItem) {
            $query->groupBy($groupItem);
        }

        $query->construct();
        $smtp = $query->execute();
        return ($smtp->fetch()['c'] ?? 0);
    }

    public function delete(): void
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                static::$database,
                                ContainerFactoryDatabaseQuery::MODE_DELETE);

        $query->setTable(static::$table);

        $id = call_user_func([
                                 $this,
                                 'get' . ucfirst(static::$tableId)
                             ]);
        $query->setParameterWhere(static::$tableId,
                                  $id);

        $query->construct();
        $query->execute();
    }

    public function deleteFrom(array $where = []): void
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                static::$database,
                                ContainerFactoryDatabaseQuery::MODE_DELETE);

        $query->setTable(static::$table);

        foreach ($where as $whereKey => $whereItem) {
            $query->setParameterWhere($whereKey,
                                      $whereItem);
        }

        $query->construct();
        $query->execute();
    }

    /**
     * @return string
     */
    public function getDataVariableCreated(): string
    {
        return $this->dataVariableCreated;
    }

    /**
     * @param string $dataVariableCreated
     */
    public function setDataVariableCreated(string $dataVariableCreated): void
    {
        $this->dataVariableCreated = $dataVariableCreated;
    }

    /**
     * @return string
     */
    public function getDataVariableEdited(): string
    {
        return $this->dataVariableEdited;
    }

    /**
     * @param string $dataVariableEdited
     */
    public function setDataVariableEdited(string $dataVariableEdited): void
    {
        $this->dataVariableEdited = $dataVariableEdited;
    }

    /**
     * @return string
     */
    public function getDataVariableDeleted(): string
    {
        return $this->dataVariableDeleted;
    }

    /**
     * @param string $dataVariableDeleted
     */
    public function setDataVariableDeleted(string $dataVariableDeleted): void
    {
        $this->dataVariableDeleted = $dataVariableDeleted;
    }

    /**
     * @return array
     */
    public function getDataAsArray(): array
    {
        $data = [];
        foreach (static::$reflectionProperty[get_called_class()] as $property) {
            $data[$property] = $this->$property;
        }
        return $data;
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function getAdditionalQuerySelect(string $key): ?string
    {
        return ($this->additionalQuerySelect[$key] ?? null);
    }

    /**
     * @param string      $key
     * @param string|null $value
     */
    public function setAdditionalQuerySelect(string $key, ?string $value): void
    {
        $this->additionalQuerySelect[$key] = $value;
    }

    /**
     * @return array
     * @throws DetailedException
     */
    public function getInstallUpdateQuery(): array
    {
        /** @var ContainerFactoryReflection $commentThis */
        $commentThis     = Container::get('ContainerFactoryReflection',
                                          get_called_class());
        $dataBaseCollect = [];

        $classComment = $commentThis->getReflectionClassComment();
        $properties   = $commentThis->getProperties();

//        $dataBaseCollect = $properties['paramData']['@database'];

        /** @var ContainerFactoryDatabaseEngineMysqlTable $structureCompare */
        $structureCompare = Container::get('ContainerFactoryDatabaseEngineMysqlTable',
                                           static::$table);

        /** @var ContainerFactoryDatabaseEngineMysqlTable $structure */
        $structure = Container::get('ContainerFactoryDatabaseEngineMysqlTable',
                                    static::$table);

        foreach ($properties as $dataBaseCollectKey => $dataBaseCollectItem) {
            if (!isset($dataBaseCollectItem['paramData']['@database'])) {
                continue;
            }

            $dataBaseCollectItemParameter = $dataBaseCollectItem['paramData']['@database'];
            $dataBaseCollectIndex         = $dataBaseCollectItem['paramData']['@database'];

            $columnNull = false;
            if (
            isset($dataBaseCollectIndex['isNull'])
            ) {
                $columnNull = true;
            }

//            simpleDebugLog($columnNull);

            $columnDefault = ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NONE;
            if (isset($dataBaseCollectItemParameter['default'])) {
                switch ($dataBaseCollectItemParameter['default']) {
                    case 'ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NONE';
                        $columnDefault = ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NONE;
                        break;
                    case 'ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL';
                        $columnDefault = ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL;
                        break;
                    case 'ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT';
                        $columnDefault = ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT;
                        $columnNull    = false;
                        break;
                    default:
                        $columnDefault = $dataBaseCollectItemParameter['default'];
                        break;
                }
            }

            $structure->setColumn($dataBaseCollectKey,
                                  $dataBaseCollectItemParameter['type'],
                                  $columnNull,
                                  $columnDefault,
                                  ($dataBaseCollectItemParameter['comment']) ?? '');

            if (
            isset($dataBaseCollectIndex['isPrimary'])
            ) {
                $structure->setPrimary($dataBaseCollectKey);

            }

            if (
            isset($dataBaseCollectIndex['isUnique'])
            ) {
                $structure->setUnique($dataBaseCollectKey);
            }

            if (isset($dataBaseCollectIndex['isIndex']) || isset($dataBaseCollectIndex['isKey'])) {
                $structure->setKey($dataBaseCollectKey);
            }

            if (
            isset($dataBaseCollectIndex['isFulltext'])
            ) {
                $structure->setFulltext($dataBaseCollectKey);
            }
        }

        if (isset($classComment['paramData']['@database'])) {
            if (
            isset($classComment['paramData']['@database']['dataVariableCreated'])
            ) {
                $structure->setColumn('dataVariableCreated',
                                      'datetime');
            }
            if (
            isset($classComment['paramData']['@database']['dataVariableEdited'])
            ) {
                $structure->setColumn('dataVariableEdited',
                                      'datetime');
            }
            if (
            isset($classComment['paramData']['@database']['dataVariableEditedCounter'])
            ) {
                $structure->setColumn('dataVariableEditedCounter',
                                      'int;11',
                                      true,
                                      0);
            }
            if (
            isset($classComment['paramData']['@database']['dataVariableDeleted'])
            ) {
                $structure->setColumn('dataVariableDeleted',
                                      'datetime');
            }
        }

        if ($structureCompare->importTable() === true) {
            return $structureCompare->createAlternateQuery($structure);
        }
        else {
            return $structure->createQuery();
        }
    }

    /**
     * @return int
     */
    public function getDataVariableEditedCounter(): int
    {
        return $this->dataVariableEditedCounter;
    }

    /**
     * @param int $dataVariableEditedCounter
     */
    public function setDataVariableEditedCounter(int $dataVariableEditedCounter): void
    {
        $this->dataVariableEditedCounter = $dataVariableEditedCounter;
    }

    public function truncate()
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__,
                                true,
                                ContainerFactoryDatabaseQuery::MODE_OTHER,
                                false);

        $query->query('TRUNCATE ' . static::$table);
        $query->construct();
        $smtp = $query->execute();
    }

}
