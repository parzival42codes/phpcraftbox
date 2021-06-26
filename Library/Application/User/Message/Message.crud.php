<?php

/**
 * Class ApplicationDeveloperSkeleton_crud
 *
 * @database dataVariableCreated
 *
 */
class ApplicationUserMessage_crud extends Base_abstract_crud
{
    protected static string $table   = 'user_messages';
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
     * @database type varchar;250
     */
    protected string $crudTitle = '';

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
    protected int $crudSource = 0;

    /**
     * @var integer
     * @database type int;11
     * @database isIndex
     */
    protected int $crudTarget = 0;

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
    public function getCrudSource(): int
    {
        return $this->crudSource;
    }

    /**
     * @param int $crudSource
     */
    public function setCrudSource(int $crudSource): void
    {
        $this->crudSource = $crudSource;
    }

    /**
     * @return int
     */
    public function getCrudTarget(): int
    {
        return $this->crudTarget;
    }

    /**
     * @param int $crudTarget
     */
    public function setCrudTarget(int $crudTarget): void
    {
        $this->crudTarget = $crudTarget;
    }

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
     * @return string
     */
    public function getCrudTitle(): string
    {
        return $this->crudTitle;
    }

    /**
     * @param string $crudTitle
     */
    public function setCrudTitle(string $crudTitle): void
    {
        $this->crudTitle = $crudTitle;
    }

    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join([
                         'user',
                         'userSource',
                     ],
                     [
                         'crudUsername',
                         'crudEmail',
                     ],
                     'userSource.crudId = ' . self::$table . '.crudSource');

        $query->join([
                         'user',
                         'userTarget',
                     ],
                     [
                         'crudUsername',
                         'crudEmail',
                     ],
                     'userTarget.crudId = ' . self::$table . '.crudTarget');

        return $query;
    }

}
