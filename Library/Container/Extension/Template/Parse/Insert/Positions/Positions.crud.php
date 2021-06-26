<?php

class ContainerExtensionTemplateParseInsertPositions_crud extends Base_abstract_crud
{

    protected static string $table   = 'template_positions';
    protected static string $tableId = 'crudId';

    /**
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId = null;
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudClass = '';
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudPosition = '';
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

    /**
     * @return string
     */
    public function getCrudPosition(): string
    {
        return $this->crudPosition;
    }

    /**
     * @param string $crudPosition
     */
    public function setCrudPosition(string $crudPosition): void
    {
        $this->crudPosition = $crudPosition;
    }

}
