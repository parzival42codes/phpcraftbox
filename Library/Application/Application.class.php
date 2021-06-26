<?php

class Application
{

    private string   $applicationName = '';
    protected string $content         = '';
    private string   $title           = '';
    private string   $contentLeft     = '';
    private string   $javascript      = '';
    private string   $debugBar        = '';
    private array    $breadcrumb      = [];
    private int      $header          = 200;

    /**
     * Construct the Application.
     *
     * @CMSprofilerSet          action construct
     * @CMSprofilerOption       isFunction true
     * @CMSprofilerOption       deph 1
     * @CMSprofilerSetFromScope app
     *
     * @param string $application
     *
     * @throws DetailedException
     */
    public function __construct(string $application)
    {
        $this->applicationName = $application . '_app';

        /** @var Application_abstract $applicationCurrent */
        $applicationCurrent = Container::get($this->applicationName);

        $this->content = $applicationCurrent->getContent();
        $this->header  = $applicationCurrent->getHeader();

        $this->contentLeft = $applicationCurrent->getContentLeft();
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getHeader(): int
    {
        return $this->header;
    }

    public function getBreadcrumb(): array
    {
        return $this->breadcrumb;
    }

    public function getContentLeft(): string
    {
        return $this->contentLeft;
    }

    /**
     * @return string
     */
    public function getApplicationName(): string
    {
        return $this->applicationName;
    }

    protected function catchException(Exception $e): void
    {
        $this->content = CoreErrorhandler::doExceptionView($e);
    }

}
