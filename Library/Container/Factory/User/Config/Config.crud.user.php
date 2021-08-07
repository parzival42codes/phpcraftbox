<?php

class ContainerFactoryUserConfig_crud_user extends Base_abstract_crud
{
    protected static string $table   = 'user_config_user';
    protected static string $tableId = 'crudId';

    /**
     * @var int|null
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId = null;
    /**
     * @var int
     * @database type int;11
     * @database isIndex
     */
    protected int $crudUserId = 0;
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudIdent = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudClass = '';
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudConfigKey = '';
    /**
     * @var string
     * @database type text
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NONE
     */
    protected string $crudConfigValue = '';

    /**
     * @return int|null
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
    public function getCrudClass(): string
    {
        return $this->crudClass;
    }

    /**
     * @param string $crudClass
     */
    public function setCrudClass(string $crudClass): void
    {
        $this->crudClass = $crudClass;
    }

    /**
     * @return string
     */
    public function getCrudConfigKey(): string
    {
        return $this->crudConfigKey;
    }

    /**
     * @param string $crudConfigKey
     */
    public function setCrudConfigKey(string $crudConfigKey): void
    {
        $this->crudConfigKey = $crudConfigKey;
    }

    /**
     * @return string
     */
    public function getCrudConfigValue(): string
    {
        return $this->crudConfigValue;
    }

    /**
     * @param $crudConfigValue
     */
    public function setCrudConfigValue($crudConfigValue): void
    {
        $this->crudConfigValue = $crudConfigValue;
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


}
