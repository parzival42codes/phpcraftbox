<?php declare(strict_types=1);

/**
 * Class ContainerFactoryUser_crud
 *
 * @database dataVariableCreated
 *
 */
class ContainerFactoryComment_crud extends Base_abstract_crud
{

    protected static string $table   = 'comment';
    protected static string $tableId = 'crudId';

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
    protected string $crudPath = '';

    /**
     * @var string
     * @database type text
     */
    protected string $crudContent = '';

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

    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join('user',
                     [
                         'crudUsername',
                     ],
                     'user.crudId = comment.crudUserId');

        return $query;
    }


}
