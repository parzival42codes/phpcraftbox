<?php

/**
 * Class ApplicationDeveloperSkeleton_crud
 *
 * @database dataVariableCreated
 *
 */
class ApplicationUserMessage_crud_messages extends Base_abstract_crud
{
    protected static string $table   = 'user_messages_messages';
    protected static string $tableId = 'crudId';

    /**
     * @var int|null
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected ?int $crudId = null;

    /**
     * @var string
     * @database type text
     */
    protected string $crudMessage = '';

    /**
     * @var integer
     * @database type int;11
     * @database isIndex
     */
    protected int $crudUser = 0;

    /**
     * @var integer
     * @database type int;11
     * @database isIndex
     */
    protected int $crudMessageId = 0;

    /**
     * @return string
     */
    public function getCrudMessage(): string
    {
        return $this->crudMessage;
    }

    /**
     * @param string $crudMessage
     */
    public function setCrudMessage(string $crudMessage): void
    {
        $this->crudMessage = $crudMessage;
    }

    /**
     * @return int
     */
    public function getCrudUser(): int
    {
        return $this->crudUser;
    }

    /**
     * @param int $crudUser
     */
    public function setCrudUser(int $crudUser): void
    {
        $this->crudUser = $crudUser;
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

    /**
     * @return int
     */
    public function getCrudMessageId(): int
    {
        return $this->crudMessageId;
    }

    /**
     * @param int $crudMessageId
     */
    public function setCrudMessageId(int $crudMessageId): void
    {
        $this->crudMessageId = $crudMessageId;
    }

    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join('user',
                     ['crudUsername'],
                     'user.crudId = ' . self::$table . '.crudUser');

        return $query;
    }

}
