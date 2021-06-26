<?php

class ContainerFactoryMenuItem extends Base
{

    protected string $path        = '';
    protected string $title       = '';
    protected string $link        = '';
    protected string $icon        = '';
    protected string $description = '';
    protected array  $data        = [];
    protected string $access      = '';

    public function __construct()
    {

    }


    public function setData(array $value): void
    {
        $this->data = $value;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setLink(string $value): void
    {
        $this->link = $value;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setIcon(string $value): void
    {
        $this->icon = $value;
    }

    public function getIcon(): string
    {
        return $this->icon;
    }

    public function setDescription(string $value): void
    {
        $this->description = $value;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getArray(): array
    {
        return [
            'link'        => $this->getLink(),
            'icon'        => $this->getIcon(),
            'description' => $this->getDescription(),
            'data'        => $this->getData(),
            'access'      => $this->getAccess(),
        ];
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getAccess(): string
    {
        return $this->access;
    }

    /**
     * @param string $access
     */
    public function setAccess(string $access = ''): void
    {
        $this->access = $access;
    }

}
