<?php

/**
 * Class ContainerFactoryLog_crud_notification
 *
 * @database dataVariableCreated
 *
 */

class ContainerFactoryLog_crud_notification extends Base_abstract_crud
{


    protected static string $table   = 'log_notification';
    protected static string $tableId = 'crudUniqueId';

    const DISPLAYED_YES = 'Yes';
    const DISPLAYED_NO  = 'No';

    const SHOW_IN_LOG_YES = 'Yes';
    const SHOW_IN_LOG_NO  = 'No';

    const NOTIFICATION_PAGE_DISPLAY = 'pageDisplay';
    const NOTIFICATION_REQUEST      = 'request';
    const NOTIFICATION_LOG          = 'log';

    /**
     * @var
     * @database type int;11
     * @database isPrimary
     * @database default ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_AUTO_INCREMENT
     */
    protected  $crudId = null;
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudUniqueId = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudMessage = '';
    /**
     * @var
     * @database type text
     * @database isNull
     */
    protected ?string $crudData = '';
    /**
     * @database type enum;"Yes","No"
     * @database default No
     */
    protected string $crudDisplayed = self::DISPLAYED_NO;
    /**
     * @database type enum;"Yes","No"
     * @database default No
     */
    protected string $crudShowInLog = self::SHOW_IN_LOG_YES;
    /**
     * @database type enum;"pageDisplay","request","Log"
     * @database default PageDisplay
     */
    protected string $crudType = ContainerFactoryLog_crud_notification::NOTIFICATION_PAGE_DISPLAY;
    /**
     * @var
     * @database type varchar;250
     */
    protected  $crudClassIdent = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudClass = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudCssClass = '';
    /**
     * @var integer
     * @database type int;11
     */
    protected int $crudUserId = 0;

    /**
     * @return
     */
    public function getCrudId(): ?int
    {
        return $this->crudId;
    }

    /**
     * @param  $crudId
     */
    public function setCrudId(?int $crudId): void
    {
        $this->crudId = $crudId;
    }

    /**
     * @return string
     */
    public function getCrudUniqueId(): string
    {
        return $this->crudUniqueId;
    }

    /**
     * @param string $crudUniqueId
     */
    public function setCrudUniqueId(string $crudUniqueId): void
    {
        $this->crudUniqueId = $crudUniqueId;
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
     * @return
     */
    public function getCrudData(): ?string
    {
        return $this->crudData;
    }

    /**
     * @param  $crudData
     */
    public function setCrudData(?string $crudData = ''): void
    {
        $this->crudData = $crudData;
    }

    /**
     * @return string
     */
    public function getCrudDisplayed(): string
    {
        return $this->crudDisplayed;
    }

    /**
     * @param string $crudDisplayed
     */
    public function setCrudDisplayed(string $crudDisplayed): void
    {
        $this->crudDisplayed = $crudDisplayed;
    }

    /**
     * @return string
     */
    public function getCrudShowInLog(): string
    {
        return $this->crudShowInLog;
    }

    /**
     * @param string $crudShowInLog
     */
    public function setCrudShowInLog(string $crudShowInLog): void
    {
        $this->crudShowInLog = $crudShowInLog;
    }

    /**
     * @return string
     */
    public function getCrudType(): string
    {
        return $this->crudType;
    }

    /**
     * @param string $crudType
     */
    public function setCrudType(string $crudType): void
    {
        $this->crudType = $crudType;
    }

    /**
     * @return mixed
     */
    public function getCrudClassIdent()
    {
        return $this->crudClassIdent;
    }

    /**
     * @param $crudClassIdent
     */
    public function setCrudClassIdent($crudClassIdent): void
    {
        $this->crudClassIdent = $crudClassIdent;
    }

    /**
     * @return string
     */
    public function getCrudClass(): string
    {
        return $this->crudClass;
    }

    /**
     * @param string $crudClass
     */
    public function setCrudClass(string $crudClass): void
    {
        $this->crudClass = $crudClass;
    }

    /**
     * @return string
     */
    public function getCrudCssClass(): string
    {
        return $this->crudCssClass;
    }

    /**
     * @param string $crudCssClass
     */
    public function setCrudCssClass(string $crudCssClass): void
    {
        $this->crudCssClass = $crudCssClass;
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
     * @param ContainerFactoryDatabaseQuery $query
     *
     * @return ContainerFactoryDatabaseQuery
     */
    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->join('user',
                     ['crudUsername'],
                     'user.crudId = ' . self::$table . '.crudUserId');
        return $query;
    }

}
