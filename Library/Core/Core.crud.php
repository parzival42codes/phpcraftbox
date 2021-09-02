<?php declare(strict_types=1);

class Core_crud extends Base_abstract_crud
{

    protected static string $table   = 'core';
    protected static string $tableId = 'crudId';

    /**
     * @var
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected $crudId = null;
    /**
     * @var
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudKey = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudValue = '';

    /**
     * @return mixed
     */
    public function getCrudId()
    {
        return $this->crudId;
    }

    /**
     * @param mixed $crudId
     */
    public function setCrudId($crudId): void
    {
        $this->crudId = $crudId;
    }

    /**
     * @return string
     */
    public function getCrudValue(): string
    {
        return $this->crudValue;
    }

    /**
     * @param string $crudValue
     */
    public function setCrudValue(string $crudValue): void
    {
        $this->crudValue = $crudValue;
    }

    /**
     * @return mixed
     */
    public function getCrudKey(): string
    {
        return $this->crudKey;
    }

    /**
     * @param mixed $crudKey
     */
    public function setCrudKey(string $crudKey): void
    {
        $this->crudKey = $crudKey;
    }


}
