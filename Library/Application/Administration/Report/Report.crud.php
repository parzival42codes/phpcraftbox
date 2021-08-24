<?php declare(strict_types=1);

/**
 * Class ContainerFactoryUser_crud
 *
 * @database dataVariableCreated
 *
 */
class ApplicationAdministrationReport_crud extends Base_abstract_crud
{
    protected static string $table   = 'report';
    protected static string $tableId = 'crudId';

    const STATUS_COPYRIGHT_PROTECTION_ACT = 'cpa';

    /**
     * @var
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected  $crudId = null;
    /**
     * @var
     * @database type int;11
     * @database isIndex
     */
    protected int $crudUserId = 0;
    /**
     * @var string
     * @database isIndex
     * @database type varchar;250
     */
    protected string $crudModul = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudModulId = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudContent = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudReport = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudStatus = '';


    /**
     * @var string
     * @database type text
     */
    protected string $crudReply = '';

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
     * @return mixed
     */
    public function getCrudUserId(): int
    {
        return $this->crudUserId;
    }

    /**
     * @param mixed $crudUserId
     */
    public function setCrudUserId(int $crudUserId): void
    {
        $this->crudUserId = $crudUserId;
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
    public function getCrudModulId(): string
    {
        return $this->crudModulId;
    }

    /**
     * @param string $crudModulId
     */
    public function setCrudModulId(string $crudModulId): void
    {
        $this->crudModulId = $crudModulId;
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
    public function getCrudStatus(): string
    {
        return $this->crudStatus;
    }

    /**
     * @param string $crudStatus
     */
    public function setCrudStatus(string $crudStatus): void
    {
        $this->crudStatus = $crudStatus;
    }

    /**
     * @return string
     */
    public function getCrudReport(): string
    {
        return $this->crudReport;
    }

    /**
     * @param string $crudReport
     */
    public function setCrudReport(string $crudReport): void
    {
        $this->crudReport = $crudReport;
    }

    /**
     * @return string
     */
    public function getCrudReply(): string
    {
        return $this->crudReply;
    }

    /**
     * @param string $crudReply
     */
    public function setCrudReply(string $crudReply): void
    {
        $this->crudReply = $crudReply;
    }

}
