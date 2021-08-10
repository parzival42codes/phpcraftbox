<?php

class ContainerFactoryUserGroup_crud extends Base_abstract_crud
{

    protected static string $table   = 'user_group';
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
     * @database type varchar;250
     */
    protected string $crudLanguage = '';
    /**
     * @var int
     * @database type int;11
     */
    protected int $crudProtected = 0;

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

    /**
     * @return string
     */
    public function getCrudLanguage(): string
    {
        return $this->crudLanguage;
    }

    /**
     * @param string $crudLanguage
     */
    public function setCrudLanguage(string $crudLanguage): void
    {
        $this->crudLanguage = $crudLanguage;
    }

}
