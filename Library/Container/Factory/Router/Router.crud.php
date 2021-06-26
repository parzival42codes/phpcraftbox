<?php

class ContainerFactoryRouter_crud extends Base_abstract_crud
{

    protected static string $table   = 'index_router';
    protected static string $tableId = 'crudId';

    /**
     * @var
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId =null;
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudClass ='';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudType ='';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudPath ='';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudRoute ='';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudTarget ='';

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
    public function getCrudPath(): string
    {
        return $this->crudPath;
    }

    /**
     * @param string $crudPath
     */
    public function setCrudPath(string $crudPath): void
    {
        $this->crudPath = $crudPath;
    }

    /**
     * @return string
     */
    public function getCrudRoute(): string
    {
        return $this->crudRoute;
    }

    /**
     * @param string $crudRoute
     */
    public function setCrudRoute(string $crudRoute): void
    {
        $this->crudRoute = $crudRoute;
    }

    /**
     * @return string
     */
    public function getCrudTarget(): string
    {
        return $this->crudTarget;
    }

    /**
     * @param string $crudTarget
     */
    public function setCrudTarget(string $crudTarget): void
    {
        $this->crudTarget = $crudTarget;
    }

}
