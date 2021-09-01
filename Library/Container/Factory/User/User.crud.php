<?php

/**
 * Class ContainerFactoryUser_crud
 *
 * @database dataVariableCreated
 *
 */
class ContainerFactoryUser_crud extends Base_abstract_crud
{

    protected static string $table   = 'user';
    protected static string $tableId = 'crudId';

    /**
     * @var
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected $crudId = null;
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudUsername = '';
    /**
     * @var integer
     * @database type int;11
     */
    protected int $crudUserGroupId = 4;
    /**
     * @var string
     * @database isIndex
     * @database type varchar;250
     */
    protected string $crudEmail = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudPassword = '';
    /**
     * @var int
     * @database type tinyint;1
     * @database default 0
     */
    protected int $crudActivated = 0;
    /**
     * @var int
     * @database type tinyint;1
     * @database default 0
     */
    protected int $crudEmailCheck = 0;
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudPasswordRequest = '';

    /**
     * @return
     */
    public function getCrudId(): ?int
    {
        return $this->crudId;
    }

    /**
     * @param ?int $crudId
     */
    public function setCrudId(?int $crudId): void
    {
        $this->crudId = $crudId;
    }

    /**
     * @return string
     */
    public function getCrudEmail(): string
    {
        return $this->crudEmail;
    }

    /**
     * @param string $crudEmail
     */
    public function setCrudEmail(string $crudEmail): void
    {
        $this->crudEmail = $crudEmail;
    }

    /**
     * @return string
     */
    public function getCrudPassword(): string
    {
        return $this->crudPassword;
    }

    /**
     * @param string $crudPassword
     */
    public function setCrudPassword(string $crudPassword): void
    {
        $this->crudPassword = $crudPassword;
    }


    /**
     * @return string
     */
    public function getCrudUsername(): string
    {
        return $this->crudUsername;
    }

    /**
     * @param string $crudUsername
     */
    public function setCrudUsername(string $crudUsername): void
    {
        $this->crudUsername = $crudUsername;
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
     * @return bool
     */
    public function isCrudActivated(): bool
    {
        return (bool)$this->crudActivated;
    }

    /**
     * @param bool $crudActivated
     */
    public function setCrudActivated(bool $crudActivated): void
    {
        $this->crudActivated = (int)$crudActivated;
    }

    /**
     * @return bool
     */
    public function isCrudEmailCheck(): bool
    {
        return (bool)$this->crudEmailCheck;
    }

    /**
     * @param bool $crudEmailCheck
     */
    public function setCrudEmailCheck(bool $crudEmailCheck): void
    {
        $this->crudEmailCheck = (int)$crudEmailCheck;
    }

    /**
     * @return string
     */
    public function getCrudPasswordRequest(): string
    {
        return $this->crudPasswordRequest;
    }

    /**
     * @param string $crudPasswordRequest
     */
    public function setCrudPasswordRequest(string $crudPasswordRequest): void
    {
        $this->crudPasswordRequest = $crudPasswordRequest;
    }

    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join('user_group',
                     [
                         'crudLanguage'
                     ],
                     'user_group.crudId = ' . self::$table . '.crudUserGroupId');
        return $query;
    }

}
