<?php

class ContainerFactoryUserConfig_crud extends Base_abstract_crud
{

    protected static string $table   = 'user_config';
    protected static string $tableId = 'crudId';

    /**
     * @var
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected  $crudId = null;
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
     * @var
     * @database type text
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NONE
     */
    protected  $crudConfigValueDefault = '';
    /**
     * @var
     * @database type varchar;250
     * @database isNull
     * @database ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL
     */
    protected  $crudConfigGroup = '';
    /**
     * @var
     * @database type text
     * @database isNull
     * @database ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL
     */
    protected  $crudConfigLanguage = '';
    /**
     * @var
     * @database type text
     * @database isNull
     * @database ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL
     */
    protected  $crudConfigForm = '';

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
     * @return ?string
     */
    public function getCrudConfigValueDefault(): ?string
    {
        return $this->crudConfigValueDefault;
    }

    /**
     * @param ?string $crudConfigValueDefault
     */
    public function setCrudConfigValueDefault(?string $crudConfigValueDefault): void
    {
        $this->crudConfigValueDefault = $crudConfigValueDefault;
    }

    /**
     * @return ?string
     */
    public function getCrudConfigGroup(): ?string
    {
        return $this->crudConfigGroup;
    }

    /**
     * @param  $crudConfigGroup
     */
    public function setCrudConfigGroup( $crudConfigGroup): void
    {
        $this->crudConfigGroup = $crudConfigGroup;
    }

    /**
     * @return
     */
    public function getCrudId()
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
     * @return
     */
    public function getCrudConfigLanguage(): ?string
    {
        return $this->crudConfigLanguage;
    }

    /**
     * @param  $crudConfigLanguage
     */
    public function setCrudConfigLanguage(?string $crudConfigLanguage): void
    {
        $this->crudConfigLanguage = $crudConfigLanguage;
    }

    /**
     * @return
     */
    public function getCrudConfigForm(): ?string
    {
        return $this->crudConfigForm;
    }

    /**
     * @param  $crudConfigForm
     */
    public function setCrudConfigForm(?string $crudConfigForm): void
    {
        $this->crudConfigForm = $crudConfigForm;
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
