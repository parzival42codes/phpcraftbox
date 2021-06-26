<?php

class ContainerFactoryUserGroup_crud_groupaccess extends Base_abstract_crud
{

    protected static string $table   = 'user_group_to_access';
    protected static string $tableId = 'crudId';

    /**
     * @var int|null
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId = null;
    /**
     * @var integer
     * @database type int;11
     */
    protected int $crudUserGroupId =0;
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudAccess ='';

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

    /**
     * @return int
     */
    public function getCrudUserGroupId(): int
    {
        return $this->crudUserGroupId;
    }

    /**
     * @param int $crudUserGroupId
     */
    public function setCrudUserGroupId(int $crudUserGroupId): void
    {
        $this->crudUserGroupId = $crudUserGroupId;
    }

    /**
     * @return string
     */
    public function getCrudAccess(): string
    {
        return $this->crudAccess;
    }

    /**
     * @param string $crudAccess
     */
    public function setCrudAccess(string $crudAccess): void
    {
        $this->crudAccess = $crudAccess;
    }

}
