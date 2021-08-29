<?php declare(strict_types=1);

/**
 * Class ContainerFactoryUser_crud
 *
 * @database dataVariableCreated
 *
 */
class ApplicationAdministrationReport_crud_type extends Base_abstract_crud
{
    protected static string $table   = 'report_type';
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
     * @database type varchar;50
     */
    protected string $crudAbbreviation = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudContent = '';

    /**
     * @return mixed
     */
    public function getCrudId(): ?int
    {
        return $this->crudId;
    }

    /**
     * @param mixed $crudId
     */
    public function setCrudId(?int $crudId): void
    {
        $this->crudId = $crudId;
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
    public function getCrudAbbreviation(): string
    {
        return $this->crudAbbreviation;
    }

    /**
     * @param string $crudAbbreviation
     */
    public function setCrudAbbreviation(string $crudAbbreviation): void
    {
        $this->crudAbbreviation = $crudAbbreviation;
    }

}
