<?php

/**
 * Class ApplicationAdministrationContent_crud
 *
 */
class Crud_crud_additional extends Crud_abstract
{
    protected static string $table   = 'crud_additional';
    protected static string $tableId = 'crudIdent';

    /**
     * @var int|null
     * @database type varchar;250
     * @database isPrimary
     */
    protected ?int $crudIdent = null;

    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudAdditionalCrud = '';

    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudAdditionalIdent = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudAdditionalValue = '';

    /**
     * @return int|null
     */
    public function getCrudIdent(): ?int
    {
        return $this->crudIdent;
    }

    /**
     * @param int|null $crudIdent
     */
    public function setCrudIdent(?int $crudIdent): void
    {
        $this->crudIdent = $crudIdent;
    }

    /**
     * @return string
     */
    public function getCrudAdditionalCrud(): string
    {
        return $this->crudAdditionalCrud;
    }

    /**
     * @param string $crudAdditionalCrud
     */
    public function setCrudAdditionalCrud(string $crudAdditionalCrud): void
    {
        $this->crudAdditionalCrud = $crudAdditionalCrud;
    }

    /**
     * @return string
     */
    public function getCrudAdditionalIdent(): string
    {
        return $this->crudAdditionalIdent;
    }

    /**
     * @param string $crudAdditionalIdent
     */
    public function setCrudAdditionalIdent(string $crudAdditionalIdent): void
    {
        $this->crudAdditionalIdent = $crudAdditionalIdent;
    }

    /**
     * @return string
     */
    public function getCrudAdditionalValue(): string
    {
        return $this->crudAdditionalValue;
    }

    /**
     * @param string $crudAdditionalValue
     */
    public function setCrudAdditionalValue(string $crudAdditionalValue): void
    {
        $this->crudAdditionalValue = $crudAdditionalValue;
    }

}
