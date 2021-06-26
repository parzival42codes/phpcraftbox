<?php declare(strict_types=1);

abstract class ContainerExtensionTemplateParse_abstract extends Base
{

    protected static array $instance = [];

    protected  $parentTemplateObject = null;
    protected string $parseString          = '';
    protected array  $parameter            = [];


    public function __construct(string $parseString, array $parameter, ContainerExtensionTemplate $parentTemplateObject)
    {
        $this->parseString = $parseString;
        $this->parameter   = $parameter;

        $this->parentTemplateObject = $parentTemplateObject;
    }

    abstract public function parse(): string;

    public function getParameter(): array
    {
        return $this->parameter;
    }

    public function getParseString(): string
    {
        return $this->parseString;
    }

    public function getParentTemplateObject(): ContainerExtensionTemplate
    {
        return $this->parentTemplateObject;
    }
}
