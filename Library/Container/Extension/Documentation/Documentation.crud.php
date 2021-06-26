<?php

class ContainerExtensionDocumentation_crud extends Base_abstract_crud
{

    protected static string $table   = 'documentation';
    protected static string $tableId = 'crudId';

    /**
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId = 0;
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
    protected string $crudType = '';
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudLanguage = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudContent = '';

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


}
