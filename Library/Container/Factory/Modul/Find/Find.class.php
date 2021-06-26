<?php

class ContainerFactoryModulFind extends Base
{

    /**
     * @var bool
     */
    protected bool $hasJavascript = false;
    /**
     * @var bool
     */
    protected bool $hasCSS = false;

    public function __construct()
    {

    }

    public function find(): array
    {

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);

        $query->setTable('index_module');
        $query->select('modul');

        if ($this->hasJavascript === true) {
            $query->setParameterWhere('hasJavascript',
                                      1);
        }
        if ($this->hasCSS === true) {
            $query->setParameterWhere('hasCSS',
                                      1);
        }

        $query->construct();
        $query->execute();

        return $query->getFetchAll();
    }


    /**
     * @param bool $hasJavascript
     */
    public function setHasJavascript(bool $hasJavascript): void
    {
        $this->hasJavascript = $hasJavascript;
    }

    /**
     * @param bool $hasCSS
     */
    public function setHasCSS(bool $hasCSS): void
    {
        $this->hasCSS = $hasCSS;
    }

}
