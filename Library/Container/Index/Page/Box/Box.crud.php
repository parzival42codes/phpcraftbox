<?php

class ContainerIndexPageBox_crud extends Base_abstract_crud
{

    protected static string $table   = 'page_box';
    protected static string $tableId = 'crudId';

    /**
     * @var ?string
     * @database type varchar;100
     * @database isPrimary
     */
    protected string $crudId = '';
    /**
     * @var int
     * @database type int;11
     */
    protected int $crudRow = 0;
    /**
     * @var int
     * @database type int;11
     */
    protected int $crudPosition = 0;
    /**
     * @var int
     * @database type int;11
     */
    protected int $crudFlex = 0;
    /**
     * @var string
     * @database isNull
     * @database type varchar;250
     */
    protected string $crudDescription = '';
    /**
     * @var string
     * @database isNull
     * @database type varchar;250
     */
    protected string $crudClass = '';
    /**
     * @var string
     * @database isNull
     * @database type varchar;250
     */
    protected string $crudStyle = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudContent = '';
    /**
     * @var string
     * @database isNull
     * @database type varchar;250
     */
    protected string $crudAssignment = '';
    /**
     * @var int
     * @database type tinyint;1
     */
    protected int $crudActive = 0;

    /**
     * @return int
     */
    public function getCrudRow(): int
    {
        return $this->crudRow;
    }

    /**
     * @param int $crudRow
     */
    public function setCrudRow(int $crudRow): void
    {
        $this->crudRow = $crudRow;
    }

    /**
     * @return int
     */
    public function getCrudPosition(): int
    {
        return $this->crudPosition;
    }

    /**
     * @param int $crudPosition
     */
    public function setCrudPosition(int $crudPosition): void
    {
        $this->crudPosition = $crudPosition;
    }

    /**
     * @return int
     */
    public function getCrudFlex(): int
    {
        return $this->crudFlex;
    }

    /**
     * @param int $crudFlex
     */
    public function setCrudFlex(int $crudFlex): void
    {
        $this->crudFlex = $crudFlex;
    }

    /**
     * @return string
     */
    public function getCrudDescription(): string
    {
        return $this->crudDescription;
    }

    /**
     * @param string $crudDescription
     */
    public function setCrudDescription(string $crudDescription): void
    {
        $this->crudDescription = $crudDescription;
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
    public function getCrudStyle(): string
    {
        return $this->crudStyle;
    }

    /**
     * @param string $crudStyle
     */
    public function setCrudStyle(string $crudStyle): void
    {
        $this->crudStyle = $crudStyle;
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
    public function getCrudAssignment(): string
    {
        return $this->crudAssignment;
    }

    /**
     * @param string $crudAssignment
     */
    public function setCrudAssignment(string $crudAssignment): void
    {
        $this->crudAssignment = $crudAssignment;
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
    public function setCrudId(string $crudId): void
    {
        $this->crudId = $crudId;
    }

    /**
     * @return bool
     */
    public function getCrudActive(): bool
    {
        return (bool)$this->crudActive;
    }

    /**
     * @param bool $crudActive
     */
    public function setCrudActive(bool $crudActive): void
    {
        $this->crudActive = (bool)$crudActive;
    }


}
