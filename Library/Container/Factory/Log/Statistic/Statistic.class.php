<?php

class ContainerFactoryLogStatistic extends Base
{

    protected string $statistic = '';
    protected string $day       = '';
    protected int    $counter   = 0;


    public function __construct(string $statisticId)
    {
        $this->statistic = $statisticId;
        $this->day       = date('Y-m-d');
    }

    /**
     * @return string
     */
    public function getStatistic(): string
    {
        return $this->statistic;
    }

    public function increase(int $value = 1): void
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_INSERT_UPDATE);
        $query->setTable('log_statistic');
        $query->setInsertUpdate('crudId',
                                $this->statistic . '_' . $this->day);
        $query->setInsertUpdate('crudCounter',
                                1,
                                'crudCounter + ' . $value,
                                true);
        $query->setInsertUpdate('crudStatistic',
                                $this->statistic);
        $query->setInsertUpdate('crudDay',
                                $this->day);
        $query->construct();
        $query->execute();
    }

    public function getStatisticDay(string $day): void
    {
        /** @var ContainerFactoryLogStatistic_crud $crud */
        $crud = Container::get('ContainerFactoryLogStatistic_crud');
        d($crud->find([
                          'crudDay' => $day
                      ]));
    }

    /**
     * @return string
     */
    public function getDay(): string
    {
        return $this->day;
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }

}
