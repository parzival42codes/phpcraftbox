<?php

abstract class  CoreDebugDump_abstract_api extends Base
{
    protected array  $language   = [];
    protected        $dump;
    protected        $title      = '';
    protected        $content    = '';
    protected string $additional = '';


    public function __construct($dump, string $title)
    {
        $this->dump  = $dump;
        $this->title = $title;
        $this->execute();
    }

    abstract function execute(): void;

    function setLanguage(array $language): void
    {
        $this->language = $language;
    }

    function getTitle(): string
    {
        return $this->title;
    }

    function getContent(): string
    {
        return $this->content;
    }

    function getAdditional(): string
    {
        return $this->additional;
    }


}
