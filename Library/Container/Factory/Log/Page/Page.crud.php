<?php

/**
 * Class ContainerFactoryLogPage_crud
 *
 * @database dataVariableCreated
 *
 */

class ContainerFactoryLogPage_crud extends Base_abstract_crud
{
    protected static string $table   = 'log_page';
    protected static string $tableId = 'crudId';

    const PAGE_NOT_FOUND       = 'pageNotFound';
    const PAGE_ACCESS_DENTITED = 'pageAccessDentited';

    /**
     * @var
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId = null;
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudType = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudUrlPure = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudUrlReadable = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudMessage = '';
    /**
     * @var int
     * @database type int;11
     */
    protected int $crudUserId = 0;
    /**
     * @var string
     * @database type text
     */
    protected string $crudData = '';

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
    public function getCrudUrlPure(): string
    {
        return $this->crudUrlPure;
    }

    /**
     * @param string $crudUrlPure
     */
    public function setCrudUrlPure(string $crudUrlPure): void
    {
        $this->crudUrlPure = $crudUrlPure;
    }

    /**
     * @return string
     */
    public function getCrudUrlReadable(): string
    {
        return $this->crudUrlReadable;
    }

    /**
     * @param string $crudUrlReadable
     */
    public function setCrudUrlReadable(string $crudUrlReadable): void
    {
        $this->crudUrlReadable = $crudUrlReadable;
    }

    /**
     * @return string
     */
    public function getCrudMessage(): string
    {
        return $this->crudMessage;
    }

    /**
     * @param string $crudMessage
     */
    public function setCrudMessage(string $crudMessage): void
    {
        $this->crudMessage = $crudMessage;
    }

    /**
     * @return int
     */
    public function getCrudUserId(): int
    {
        return $this->crudUserId;
    }

    /**
     * @param int $crudUserId
     */
    public function setCrudUserId(int $crudUserId): void
    {
        $this->crudUserId = $crudUserId;
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
     * @param ContainerFactoryDatabaseQuery $query
     *
     * @return ContainerFactoryDatabaseQuery
     */
    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join('user',
                     ['crudUsername'],
                     'user.crudId = ' . self::$table . '.crudUserId');
        return $query;
    }
}
