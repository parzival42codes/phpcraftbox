<?php

/**
 * Class ApplicationAdministrationContent_crud
 *
 * @database dataVariableCreated
 *
 */
class ApplicationAdministrationContent_crud_history extends Base_abstract_crud
{
    protected static string $table   = 'content_history';
    protected static string $tableId = 'crudId';

    /**
     * @var int|null
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId = null;
    /**
     * @var string
     * @database type text
     */
    protected string $crudContent = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudData = '';
    /**
     * @var string
     * @database type varchar;100
     * @database isIndex
     */
    protected string $crudIdent = '';
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
    public function getCrudData(): string
    {
        return $this->crudData;
    }

    /**
     * @param string $crudData
     */
    public function setCrudData(string $crudData): void
    {
        $this->crudData = $crudData;
    }

    /**
     * @return int|null
     */
    public function getCrudId(): ?int
    {
        return $this->crudId;
    }

    /**
     * @param int|null $crudId
     */
    public function setCrudId(?int $crudId): void
    {
        $this->crudId = $crudId;
    }

    /**
     * @return string
     */
    public function getCrudIdent(): string
    {
        return $this->crudIdent;
    }

    /**
     * @param string $crudIdent
     */
    public function setCrudIdent(string $crudIdent): void
    {
        $this->crudIdent = $crudIdent;
    }

    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join('content_index',
                     ['crudContentIdent'],
                     'content_index.crudContentIdent = ' . self::$table . '.crudIdent');
        return $query;
    }

}
