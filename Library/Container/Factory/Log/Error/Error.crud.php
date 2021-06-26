<?php

class ContainerFactoryLogError_crud extends Base_abstract_crud
{

    const LOG_TYPE_TRIGGER   = 'trigger';
    const LOG_TYPE_INFO      = 'info';
    const LOG_TYPE_WARNING   = 'warning';
    const LOG_TYPE_EXCEPTION = 'exception';

    protected static string      $table    = 'log_error';
    protected static string      $tableId  = 'crudId';
    protected static  $database = 'cache';

    /**
     * @var
     */
    protected ?int $crudId = null;
    /**
     * @var string
     */
    protected string $crudType = '';
    /**
     * @var string
     */
    protected string $crudPath = '';
    /**
     * @var string
     */
    protected string $crudTitle = '';
    /**
     * @var string
     */
    protected string $crudContent = '';
    /**
     * @var string
     */
    protected string $crudBacktrace = '';

    public function __construct(array $data = [])
    {
        if (
            ContainerFactoryDatabaseEngineSqlite::tableIsInDatabase('cache',
                                                                    'log_error') === false
        ) {


            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__ . '#showTables',
                                    'cache',
                                    ContainerFactoryDatabaseQuery::MODE_OTHER);
            $query->query('
            CREATE TABLE log_error (
                `crudId` INTEGER PRIMARY KEY AUTOINCREMENT,
                `crudType` VARCHAR(100) NOT NULL,
                `crudPath` VARCHAR(100) NOT NULL,
                `crudTitle` VARCHAR(100) NOT NULL,
                `crudContent` TEXT NOT NULL,
                `crudBacktrace` TEXT NOT NULL,
                `dataVariableCreated` DATETIME NULL
            )');
            $query->construct();
            $query->execute();

            ContainerFactoryDatabaseEngineSqlite::addTableDatabase('cache',
                                                                  'log_error');
        }

        parent::__construct($data);
    }

    /**
     * @return
     */
    public function getCrudId(): ?int
    {
        return $this->crudId;
    }

    /**
     * @param  $crudId
     */
    public function setCrudId(?int $crudId): void
    {
        $this->crudId = $crudId;
    }

    /**
     * @return string
     */
    public function getCrudType(): string
    {
        return $this->crudType;
    }

    /**
     * @param string $crudType
     */
    public function setCrudType(string $crudType): void
    {
        $this->crudType = $crudType;
    }

    /**
     * @return string
     */
    public function getCrudPath(): string
    {
        return $this->crudPath;
    }

    /**
     * @param string $crudPath
     */
    public function setCrudPath(string $crudPath): void
    {
        $this->crudPath = $crudPath;
    }

    /**
     * @return string
     */
    public function getCrudTitle(): string
    {
        return $this->crudTitle;
    }

    /**
     * @param string $crudTitle
     */
    public function setCrudTitle(string $crudTitle): void
    {
        $this->crudTitle = $crudTitle;
    }

    /**
     * @return string
     */
    public function getCrudContent(): string
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

    /**
     * @return string
     */
    public function getCrudBacktrace(): string
    {
        return $this->crudBacktrace;
    }

    /**
     * @param string $crudBacktrace
     */
    public function setCrudBacktrace(string $crudBacktrace): void
    {
        $this->crudBacktrace = $crudBacktrace;
    }

}
