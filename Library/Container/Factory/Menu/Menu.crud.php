<?php

class ContainerFactoryMenu_crud extends Base_abstract_crud
{

    protected static string $table   = 'menu';
    protected static string $tableId = 'crudClass';

    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudClass = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudData = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudMenuIcon = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudMenuLink = '';
    /**
     * @var ?string
     * @database type varchar;250
     * @database isNull
     * @database ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL
     */
    protected ?string $crudMenuAccess = '';

    /**
     * @return string
     */
    public function getCrudMenuIcon(): string
    {
        return $this->crudMenuIcon;
    }

    /**
     * @param string $crudMenuIcon
     */
    public function setCrudMenuIcon(string $crudMenuIcon): void
    {
        $this->crudMenuIcon = $crudMenuIcon;
    }

    /**
     * @return string
     */
    public function getCrudMenuLink(): string
    {
        return $this->crudMenuLink;
    }

    /**
     * @param string $crudMenuLink
     */
    public function setCrudMenuLink(string $crudMenuLink): void
    {
        $this->crudMenuLink = $crudMenuLink;
    }

    /**
     * @return string
     */
    public function getCrudMenuAccess(): string
    {
        return $this->crudMenuAccess;
    }

    /**
     * @param ?string $crudMenuAccess
     */
    public function setCrudMenuAccess(?string $crudMenuAccess): void
    {
        $this->crudMenuAccess = $crudMenuAccess;
    }

    /**fd
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

}
