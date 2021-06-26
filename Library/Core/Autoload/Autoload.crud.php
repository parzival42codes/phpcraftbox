<?php

class CoreAutoload_crud extends Base_abstract_crud
{

    protected static $database = 'cache';
    protected static string      $table    = 'autoload';
    protected static string      $tableId  = 'crudClass';

    /**
     * @var string
     * @database type varchar;250
     * @database isPrimary
     */
    protected string $crudClass = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudPath = '';

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


}
