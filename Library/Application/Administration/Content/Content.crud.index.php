<?php

class ApplicationAdministrationContent_crud_index extends Base_abstract_crud
{
    protected static string $table   = 'content_index';
    protected static string $tableId = 'crudIdent';

    /**
     * @var string
     * @database type varchar;250
     * @database isPrimary
     */
    protected string $crudIdent;
    /**
     * @var string
     * @database type varchar;250
     * @database isIndex
     */
    protected string $crudPath = '';
    /**
     * @var string
     * @database type varchar;100
     * @database isIndex
     */
    protected string $crudTitle = '';
    /**
     * @var string
     * @database type varchar;100
     * @database isIndex
     */
    protected string $crudDescription = '';
    /**
     * @var string
     * @database type varchar;100
     * @database isIndex
     */
    protected string $crudDomain = '';
    /**
     * @var string
     * @database type varchar;100
     * @database isIndex
     */
    protected string $crudLanguage = '';
    /**
     * @var string|null
     * @database type varchar;100
     * @database isIndex
     * @database isNull
     */
    protected ?string $crudContentIdent = '';

    public function createIndexFromContentIdent(): void
    {
        /** @var ApplicationAdministrationContent_crud $crud */
        $crud = Container::get('ApplicationAdministrationContent_crud');
        $crud->setCrudIdent($this->getCrudContentIdent());
        $crud->findById(true);

        /** @var ContainerFactoryLanguageParseIni $ini */
        $ini = Container::get('ContainerFactoryLanguageParseIni',
                              $crud->getCrudData());

        foreach ($ini->getLanguages() as $languageKey => $languageItem) {
            /** @var ApplicationAdministrationContent_crud_index $crudIndex */
            $crudIndex = Container::get(__CLASS__);

            $crudIndex->setCrudTitle($languageItem['title']);
            $crudIndex->setCrudDescription($languageItem['description']);
            $crudIndex->setCrudPath($languageItem['path']);
            $crudIndex->setCrudDomain(($languageItem['domain'] ?? ''));
            $crudIndex->setCrudLanguage($languageKey);
            $crudIndex->setCrudContentIdent($this->getCrudContentIdent());
            $crudIndex->setCrudIdent($this->getCrudContentIdent() . $crudIndex->getCrudPath() . $crudIndex->getCrudDomain() . $crudIndex->getCrudLanguage());

            $crudIndex->insertUpdate();
        }

    }

    /**
     * @return string
     */
    public function getCrudContentIdent(): string
    {
        return $this->crudContentIdent;
    }

    /**
     * @param string $crudContentIdent
     */
    public function setCrudContentIdent(string $crudContentIdent): void
    {
        $this->crudContentIdent = $crudContentIdent;
    }

    /**
     * @return string
     */
    public function getCrudIdent()
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
    public function getCrudDescription(): string
    {
        return $this->crudDescription;
    }

    /**
     * @param string $crudDescription
     */
    public function setCrudDescription(string $crudDescription): void
    {
        $this->crudDescription = $crudDescription;
    }

    /**
     * @return string
     */
    public function getCrudDomain(): string
    {
        return $this->crudDomain;
    }

    /**
     * @param string $crudDomain
     */
    public function setCrudDomain(string $crudDomain): void
    {
        $this->crudDomain = $crudDomain;
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

    /**
     * @return string
     */
    public function getCrudLanguage(): string
    {
        return $this->crudLanguage;
    }

    /**
     * @param string $crudLanguage
     */
    public function setCrudLanguage(string $crudLanguage): void
    {
        $this->crudLanguage = $crudLanguage;
    }


}
