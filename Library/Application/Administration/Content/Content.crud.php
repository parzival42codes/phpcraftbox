<?php

/**
 * Class ApplicationAdministrationContent_crud
 *
 * @database dataVariableCreated
 * @database dataVariableEdited
 * @database dataVariableEditedCounter
 *
 */
class ApplicationAdministrationContent_crud extends Base_abstract_crud
{
    protected static string $table   = 'content';
    protected static string $tableId = 'crudIdent';

    /**
     * @var string
     * @database type varchar;50
     * @database isPrimary
     */
    protected string $crudIdent = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudContent = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudData = '';
    /**
     * @var string
     * @database type enum;"Yes","No"
     * @database default No
     */
    protected string $crudRequired = 'No';

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
    public function getCrudRequired(): string
    {
        return $this->crudRequired;
    }

    /**
     * @param string $crudRequired
     */
    public function setCrudRequired(string $crudRequired): void
    {
        $this->crudRequired = $crudRequired;
    }

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
    public function getCrudData(): string
    {
        return $this->crudData;
    }

    /**
     * @param string $crudData
     */
    public function setCrudData(string $crudData): void
    {
        $this->crudData = $crudData;
    }

    protected function modifyFindQuery(ContainerFactoryDatabaseQuery $query): ContainerFactoryDatabaseQuery
    {
        $query->selectRaw('count(*) as countIndex');
        $query->join('content_index',
                     [],
                     'content_index.crudContentIdent = ' . self::$table . '.crudIdent');
        $query->orderBy('crudIdent');

        $query->construct();
        return $query;
    }

}
