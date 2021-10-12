<?php

class Event_crud extends Base_abstract_crud
{

    protected static string $table   = 'event_attach';
    protected static string $tableId = 'crudId';
    protected static ?array $tableIdMerge
                                     = [
            'crudModul',
            'crudPath',
        ];

    /**
     * @var string
     * @database type varchar;250
     * @database isPrimary
     */
    protected ?string $crudId = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudPath = '';
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudModul = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudTriggerClass = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudTriggerMethod = '';

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

    /**
     * @return string
     */
    public function getCrudModul(): string
    {
        return $this->crudModul;
    }

    /**
     * @param string $crudModul
     */
    public function setCrudModul(string $crudModul): void
    {
        $this->crudModul = $crudModul;
    }

    /**
     * @return string
     */
    public function getCrudId(): ?string
    {
        return $this->crudId;
    }

    /**
     * @param string $crudId
     */
    public function setCrudId(?string $crudId): void
    {
        $this->crudId = $crudId;
    }


}
