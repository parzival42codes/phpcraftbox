<?php

/**
 * Class ContainerFactoryGeneralmemory_crud
 *
 * @database dataVariableCreated
 * @database dataVariableEdited
 *
 */
class ContainerFactoryGeneralmemory_crud extends Base_abstract_crud
{
    protected static string $table   = 'general_memory';
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
     * @database isIndex
     */
    protected string $crudPath = '';
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudGroup = '';

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
    public function getCrudGroup(): string
    {
        return $this->crudGroup;
    }

    /**
     * @param string $crudGroup
     */
    public function setCrudGroup(string $crudGroup): void
    {
        $this->crudGroup = $crudGroup;
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
