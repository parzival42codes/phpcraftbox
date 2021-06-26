<?php

/**
 * Class ApplicationUserEmailcheck_crud
 *
 * @database dataVariableCreated
 *
 */

class ApplicationUserEmailcheck_crud extends Base_abstract_crud
{

    protected static string $table   = 'user_emailcheck';
    protected static string $tableId = 'crudId';

    /**
     * @var string
     * @database type varchar;250
     * @database isPrimary
     */
    protected  $crudId = null;
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudClass = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudMethod = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudParameter = '';

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
    public function getCrudMethod(): string
    {
        return $this->crudMethod;
    }

    /**
     * @param string $crudMethod
     */
    public function setCrudMethod(string $crudMethod): void
    {
        $this->crudMethod = $crudMethod;
    }

    /**
     * @return string
     */
    public function getCrudParameter(): string
    {
        return $this->crudParameter;
    }

    /**
     * @param string $crudParameter
     */
    public function setCrudParameter(string $crudParameter): void
    {
        $this->crudParameter = $crudParameter;
    }

}
