<?php

class ContainerExtensionTemplateParseCreateFormResponse_crud extends Base_abstract_crud
{

    protected static string $table   = 'form_response';
    protected static string $tableId = 'crudUniqid';

    protected string $crudUniqid = '';
    protected string $crudData   = '';
    protected string $crudModify = '';

    /**
     * @return string
     */
    public function getCrudUniqid(): string
    {
        return $this->crudUniqid;
    }

    /**
     * @param string $crudUniqid
     */
    public function setCrudUniqid(string $crudUniqid): void
    {
        $this->crudUniqid = $crudUniqid;
    }

    /**
     * @return ?string
     */
    public function getCrudData(): ?string
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

    /**
     * @return string
     */
    public function getCrudModify(): string
    {
        return $this->crudModify;
    }

    /**
     * @param string $crudModify
     */
    public function setCrudModify(string $crudModify): void
    {
        $this->crudModify = $crudModify;
    }


}
