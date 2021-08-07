<?php

/**
 * Class ContainerFactoryModul_crud
 *
 * @database dataVariableCreated
 * @database dataVariableEdited
 *
 */

class ContainerFactoryModul_crud extends Base_abstract_crud
{

    protected static string $table   = 'index_module';
    protected static string $tableId = 'crudModul';

    /**
     * @var string
     * @database type varchar;250
     * @database isPrimary
     */
    protected string $crudModul = '';
    /**
     * @var string
     * @database type varchar;250
     * @database isNull
     * @database isIndex
     * @database ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL
     */
    protected string $crudParentModul = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudDescription = '';
    /**
     * @var string
     * @database type text
     */
    protected string $crudMeta = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudName = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudAuthor = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudVersion = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudVersionRequiredSystem = '';
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudDependency = '';
    /**
     * @var integer
     * @database type tinyint;4
     * @database isIndex
     */
    protected int $crudHasJavascript = 0;
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudHasJavascriptFiles = '';
    /**
     * @var integer
     * @database type tinyint;4
     * @database isIndex
     */
    protected int $crudHasCss = 0;
    /**
     * @var string
     * @database type varchar;250
     */
    protected string $crudHasCssFiles = '';
    /**
     * @var integer
     * @database type tinyint;4
     */
    protected int $crudHasContent = 0;
    /**
     * @var integer
     * @database type tinyint;4
     */
    protected int $crudHasSearch = 0;
    /**
     * @var integer
     * @database type tinyint;4
     */
    protected int $crudActive = 0;

    /**
     * @return string
     */
    public function getCrudModul(): string
    {
        return $this->crudModul;
    }

    /**
     * @param string $crudModul
     */
    public function setCrudModul(string $crudModul): void
    {
        $this->crudModul = $crudModul;
    }

    /**
     * @return string
     */
    public function getCrudParentModul(): string
    {
        return $this->crudParentModul;
    }

    /**
     * @param string $crudParentModul
     */
    public function setCrudParentModul(string $crudParentModul): void
    {
        $this->crudParentModul = $crudParentModul;
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
    public function getCrudMeta(): string
    {
        return $this->crudMeta;
    }

    /**
     * @param string $crudMeta
     */
    public function setCrudMeta(string $crudMeta): void
    {
        $this->crudMeta = $crudMeta;
    }

    /**
     * @return string
     */
    public function getCrudName(): string
    {
        return $this->crudName;
    }

    /**
     * @param string $crudName
     */
    public function setCrudName(string $crudName): void
    {
        $this->crudName = $crudName;
    }

    /**
     * @return string
     */
    public function getCrudAuthor(): string
    {
        return $this->crudAuthor;
    }

    /**
     * @param string $crudAuthor
     */
    public function setCrudAuthor(string $crudAuthor): void
    {
        $this->crudAuthor = $crudAuthor;
    }

    /**
     * @return string
     */
    public function getCrudVersion(): string
    {
        return $this->crudVersion;
    }

    /**
     * @param string $crudVersion
     */
    public function setCrudVersion(string $crudVersion): void
    {
        $this->crudVersion = $crudVersion;
    }

    /**
     * @return string
     */
    public function getCrudVersionRequiredSystem(): string
    {
        return $this->crudVersionRequiredSystem;
    }

    /**
     * @param string $crudVersionRequiredSystem
     */
    public function setCrudVersionRequiredSystem(string $crudVersionRequiredSystem): void
    {
        $this->crudVersionRequiredSystem = $crudVersionRequiredSystem;
    }

    /**
     * @return string
     */
    public function getCrudDependency(): string
    {
        return $this->crudDependency;
    }

    /**
     * @param string $crudDependency
     */
    public function setCrudDependency(string $crudDependency): void
    {
        $this->crudDependency = $crudDependency;
    }

    /**
     * @return int
     */
    public function getCrudHasJavascript(): int
    {
        return $this->crudHasJavascript;
    }

    /**
     * @param int $crudHasJavascript
     */
    public function setCrudHasJavascript(int $crudHasJavascript): void
    {
        $this->crudHasJavascript = $crudHasJavascript;
    }

    /**
     * @return string
     */
    public function getCrudHasJavascriptFiles(): string
    {
        return $this->crudHasJavascriptFiles;
    }

    /**
     * @param string $crudHasJavascriptFiles
     */
    public function setCrudHasJavascriptFiles(string $crudHasJavascriptFiles): void
    {
        $this->crudHasJavascriptFiles = $crudHasJavascriptFiles;
    }

    /**
     * @return int
     */
    public function getCrudHasCss(): int
    {
        return $this->crudHasCss;
    }

    /**
     * @param int $crudHasCss
     */
    public function setCrudHasCss(int $crudHasCss): void
    {
        $this->crudHasCss = $crudHasCss;
    }

    /**
     * @return string
     */
    public function getCrudHasCssFiles(): string
    {
        return $this->crudHasCssFiles;
    }

    /**
     * @param string $crudHasCssFiles
     */
    public function setCrudHasCssFiles(string $crudHasCssFiles): void
    {
        $this->crudHasCssFiles = $crudHasCssFiles;
    }

    /**
     * @return int
     */
    public function getCrudHasContent(): int
    {
        return $this->crudHasContent;
    }

    /**
     * @param int $crudHasContent
     */
    public function setCrudHasContent(int $crudHasContent): void
    {
        $this->crudHasContent = $crudHasContent;
    }

    /**
     * @return int
     */
    public function getCrudActive(): int
    {
        return $this->crudActive;
    }

    /**
     * @param int $crudActive
     */
    public function setCrudActive(int $crudActive): void
    {
        $this->crudActive = $crudActive;
    }

    /**
     * @return int
     */
    public function getCrudHasSearch(): int
    {
        return $this->crudHasSearch;
    }

    /**
     * @param int $crudHasSearch
     */
    public function setCrudHasSearch(int $crudHasSearch): void
    {
        $this->crudHasSearch = $crudHasSearch;
    }


}
