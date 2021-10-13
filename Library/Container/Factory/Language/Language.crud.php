<?php

/**
 * Class Config_crud
 *
 * @database dataVariableCreated
 * @database dataVariableEdited
 *
 */

class ContainerFactoryLanguage_crud extends Base_abstract_crud
{

    protected static string $table   = 'language';
    protected static string $tableId = 'crudId';
    protected static ?array $tableIdMerge
        = [
            'crudClass',
            'crudLanguageKey',
        ];

    /**
     * @var string
     * @database type varchar;250
     * @database isPrimary
     */
    protected string $crudId = '';

    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudClass = '';
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudLanguageKey = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudLanguageValue = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudLanguageValueDefault = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudLanguageLanguage = '';

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
    public function getCrudLanguageKey(): string
    {
        return $this->crudLanguageKey;
    }

    /**
     * @param string $crudLanguageKey
     */
    public function setCrudLanguageKey(string $crudLanguageKey): void
    {
        $this->crudLanguageKey = $crudLanguageKey;
    }

    /**
     * @return string
     */
    public function getCrudLanguageValue(): string
    {
        return $this->crudLanguageValue;
    }

    /**
     * @param string $crudLanguageValue
     */
    public function setCrudLanguageValue(string $crudLanguageValue): void
    {
        $this->crudLanguageValue = $crudLanguageValue;
    }

    /**
     * @return string
     */
    public function getCrudLanguageValueDefault(): string
    {
        return $this->crudLanguageValueDefault;
    }

    /**
     * @param string $crudLanguageValueDefault
     */
    public function setCrudLanguageValueDefault(string $crudLanguageValueDefault): void
    {
        $this->crudLanguageValueDefault = $crudLanguageValueDefault;
    }

    /**
     * @return string
     */
    public function getCrudLanguageLanguage(): string
    {
        return $this->crudLanguageLanguage;
    }

    /**
     * @param string $crudLanguageLanguage
     */
    public function setCrudLanguageLanguage(string $crudLanguageLanguage): void
    {
        $this->crudLanguageLanguage = $crudLanguageLanguage;
    }

    /**
     * @return string
     */
    public function getCrudId(): string
    {
        return $this->crudId;
    }

    /**
     * @param string $crudId
     */
    public function setCrudId(string $crudId): void
    {
        $this->crudId = $crudId;
    }

}
