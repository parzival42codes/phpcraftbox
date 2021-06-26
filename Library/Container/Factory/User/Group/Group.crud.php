<?php

class ContainerFactoryUserGroup_crud extends Base_abstract_crud
{

    protected static string $table   = 'user_group';
    protected static string $tableId = 'crudId';

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
     */
    protected string $crudData = '';
    /**
     * @var int
     * @database type int;11
     */
    protected int $crudProtected = 0;

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
     * @return int
     */
    public function getCrudProtected(): int
    {
        return $this->crudProtected;
    }

    /**
     * @param int $crudProtected
     */
    public function setCrudProtected(int $crudProtected): void
    {
        $this->crudProtected = $crudProtected;
    }

}
