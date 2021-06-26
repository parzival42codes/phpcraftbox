<?php

/**
 * Class ApplicationAdministrationContent_crud
 *
 * @database dataVariableCreated
 * @database dataVariableEdited
 *
 */
class Custom_crud extends Base_abstract_crud
{
    protected static string $table   = 'custom';
    protected static string $tableId = 'crudIdent';

    /**
     * @var
     * @database type varchar;250
     * @database isPrimary
     */
    protected string $crudIdent = '';
    /**
     * @var string
     * @database type enum;"Install","Active","InActive","UnInstall"
     */
    protected string $crudStatus = '';
    /**
     * @var string
     * @database type enum;"Local","Repository"
     */
    protected string $crudSource = '';
    /**
     * @var string
     * @database type enum;"Application","Plugin","Style"
     */
    protected string $crudType = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudLog = '';

    /**
     * @return string
     */
    public function getCrudIdent(): string
    {
        return $this->crudIdent;
    }

    /**
     * @param string $crudIdent
     */
    public function setCrudIdent(string $crudIdent): void
    {
        $this->crudIdent = $crudIdent;
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
    public function getCrudSource(): string
    {
        return $this->crudSource;
    }

    /**
     * @param string $crudSource
     */
    public function setCrudSource(string $crudSource): void
    {
        $this->crudSource = $crudSource;
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
     * @return string
     */
    public function getCrudLog(): string
    {
        return $this->crudLog;
    }

    /**
     * @param string $crudLog
     */
    public function setCrudLog(string $crudLog): void
    {
        $this->crudLog = $crudLog;
    }


}
