<?php

class ContainerExtensionDocumentation_crud extends Base_abstract_crud
{

    protected static string $table   = 'documentation';
    protected static string $tableId = 'crudId';
    protected static ?array $tableIdMerge
        = [
            'crudClass',
        ];

    /**
     * @var string
     * @database type varchar;250
     * @database isPrimary
     */
    protected string $crudId = '';
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudClass = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudContent = '';

    /**
     * @return string
     */
    public function getCrudId(): string
    {
        return $this->crudId;
    }

    /**
     * @param string $crudId
     */
    public function setCrudId(string $crudId): void
    {
        $this->crudId = $crudId;
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


}
