<?php

class ContainerFactoryLogStatistic_crud extends Base_abstract_crud
{
    protected static string $table   = 'log_statistic';
    protected static string $tableId = 'crudId';

    /**
     * @var ?string
     * @database type varchar;250
     * @database isPrimary
     */
    protected ?string $crudId = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudStatistic = '';
    /**
     * @var int
     * @database type int;11
     */
    protected int $crudCounter = 0;
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudDay = '';

    /**
     * @return int|null
     */
    public function getCrudId(): ?int
    {
        return $this->crudId;
    }

    /**
     * @param string|null $crudId
     */
    public function setCrudId(?string $crudId): void
    {
        $this->crudId = $crudId;
    }

    /**
     * @return string
     */
    public function getCrudStatistic(): string
    {
        return $this->crudStatistic;
    }

    /**
     * @param string $crudStatistic
     */
    public function setCrudStatistic(string $crudStatistic): void
    {
        $this->crudStatistic = $crudStatistic;
    }

    /**
     * @return int
     */
    public function getCrudCounter(): int
    {
        return $this->crudCounter;
    }

    /**
     * @param int $crudCounter
     */
    public function setCrudCounter(int $crudCounter): void
    {
        $this->crudCounter = $crudCounter;
    }

    /**
     * @return string
     */
    public function getCrudDay(): string
    {
        return $this->crudDay;
    }

    /**
     * @param string $crudDay
     */
    public function setCrudDay(string $crudDay): void
    {
        $this->crudDay = $crudDay;
    }


}
