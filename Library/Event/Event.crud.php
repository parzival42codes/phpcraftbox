<?php

class Event_crud extends Base_abstract_crud
{

    protected static string $table   = 'event_attach';
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
    protected string $crudPath ='';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudTriggerClass = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudTriggerMethod ='';

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
    public function getCrudTriggerClass(): string
    {
        return $this->crudTriggerClass;
    }

    /**
     * @param string $crudTriggerClass
     */
    public function setCrudTriggerClass(string $crudTriggerClass): void
    {
        $this->crudTriggerClass = $crudTriggerClass;
    }

    /**
     * @return string
     */
    public function getCrudTriggerMethod(): string
    {
        return $this->crudTriggerMethod;
    }

    /**
     * @param string $crudTriggerMethod
     */
    public function setCrudTriggerMethod(string $crudTriggerMethod): void
    {
        $this->crudTriggerMethod = $crudTriggerMethod;
    }


}
