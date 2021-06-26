<?php

class ContainerFactoryLogDebug_crud extends Base_abstract_crud
{
    protected static string      $table    = 'debug';
    protected static string      $tableId  = 'crudId';
    protected static string  $database = 'cache';

    /**
     * @var int|null
     */
    protected ?int $crudId = null;
    /**
     * @var string|null
     */
    protected ?string $crudContent;

    public function __construct(array $data = [])
    {
        if (
            ContainerFactoryDatabaseEngineSqlite::tableIsInDatabase('cache',
                                                                    'debug') === false
        ) {


            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__ . '#showTables',
                                    'cache',
                                    ContainerFactoryDatabaseQuery::MODE_OTHER);
            $query->query('
            CREATE TABLE debug (
                `crudId` INTEGER PRIMARY KEY AUTOINCREMENT,
                `crudContent` TEXT NOT NULL,
                `dataVariableCreated` DATETIME NULL
            )');
            $query->construct();
            $query->execute();

            ContainerFactoryDatabaseEngineSqlite::addTableDatabase('cache',
                                                                  'debug');
        }

        parent::__construct($data);
    }

    /**
     * @return
     */
    public function getCrudId():?int
    {
        return $this->crudId;
    }

    /**
     * @param ?int $crudId
     */
    public function setCrudId(?int $crudId): void
    {
        $this->crudId = $crudId;
    }

    /**
     * @return string
     */
    public function getCrudContent():string
    {
        return $this->crudContent;
    }

    /**
     * @param string $crudContent
     */
    public function setCrudContent(string $crudContent): void
    {
        $this->crudContent = $crudContent;
    }

}
