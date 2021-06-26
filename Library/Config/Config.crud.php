<?php

/**
 * Class Config_crud
 *
 * @database dataVariableCreated
 * @database dataVariableEdited
 *
 */


class Config_crud extends Base_abstract_crud
{

    protected static string $table   = 'config';
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
    protected string $crudClass = '';
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudConfigKey = '';
    /**
     * @var string|null
     * @database type text
     * @database isNull
     * @database ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL
     */
    protected ?string $crudConfigValue = '';
    /**
     * @var string|null
     * @database type text
     * @database isNull
     * @database ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL
     */
    protected ?string $crudConfigValueDefault = '';
    /**
     * @var string|null
     * @database type varchar;250
     * @database isNull
     * @database ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL
     */
    protected ?string $crudConfigGroup = '';
    /**
     * @var string|null
     * @database type text
     * @database isNull
     * @database ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL
     */
    protected ?string $crudConfigLanguage = '';
    /**
     * @var string|null
     * @database type text
     * @database isNull
     * @database ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL
     */
    protected ?string $crudConfigForm = '';

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
    public function getCrudConfigValue(): ?string
    {
        return $this->crudConfigValue;
    }

    /**
     * @param ?string $crudConfigValue
     */
    public function setCrudConfigValue(?string $crudConfigValue): void
    {
        $this->crudConfigValue = $crudConfigValue;
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
     * @return string|null
     */
    public function getCrudConfigLanguage(): ?string
    {
        return $this->crudConfigLanguage;
    }

    /**
     * @param string|null $crudConfigLanguage
     */
    public function setCrudConfigLanguage(?string $crudConfigLanguage): void
    {
        $this->crudConfigLanguage = $crudConfigLanguage;
    }

    /**
     * @return string|null
     */
    public function getCrudConfigForm(): ?string
    {
        return $this->crudConfigForm;
    }

    /**
     * @param string|null $crudConfigForm
     */
    public function setCrudConfigForm(?string $crudConfigForm): void
    {
        $this->crudConfigForm = $crudConfigForm;
    }
}
