<?php declare(strict_types=1);

/**
 * Class ContainerFactoryUser_crud
 *
 * @database dataVariableReport
 * @database dataVariableCreated
 *
 */
class ContainerFactoryHistory_crud extends Base_abstract_crud
{

    protected static string $table   = 'comments';
    protected static string $tableId = 'crudId';

    /**
     * @var ?int
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId = null;
    /**
     * @var int
     * @database type int;11
     * @database isIndex
     */
    protected int $crudUserId = 0;
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudModul= '';
    /**
     * @var int
     * @database type int;11
     * @database isIndex
     */
    protected int $crudModulId = 0;

    /**
     * @var string
     * @database type text
     */
    protected string $crudContent = '';

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
     * @return int
     */
    public function getCrudUserId(): int
    {
        return $this->crudUserId;
    }

    /**
     * @param int $crudUserId
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
     * @return int
     */
    public function getCrudModulId(): int
    {
        return $this->crudModulId;
    }

    /**
     * @param int $crudModulId
     */
    public function setCrudModulId(int $crudModulId): void
    {
        $this->crudModulId = $crudModulId;
    }

    /**
     * @return int|null
     */
    public function getCrudId(): ?int
    {
        return $this->crudId;
    }

    /**
     * @param int|null $crudId
     */
    public function setCrudId(?int $crudId): void
    {
        $this->crudId = $crudId;
    }

    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join('user',
                     [
                         'crudUsername',
                     ],
                     'user.crudId = comments.crudUserId');

        $query->join('user_group',
                     [
                         'crudLanguage',
                     ],
                     'user_group.crudId = user.crudUserGroupId');
        $query->join('report',
                     [
                         'crudType',
                         'crudContent',
                         'crudStatus',
                     ],
                     'report.crudId = comments.dataVariableReport');
        $query->join('report_type',
                     [
                         'crudAbbreviation',
                         'crudContent'
                     ],
                     'report_type.crudId = report.crudType');

        return $query;
    }


}
