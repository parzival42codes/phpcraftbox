<?php declare(strict_types=1);

abstract class ContainerExtensionTemplateParseInsertWidget_abstract extends Base
{
    protected array $parameter = [];

    public function __construct(array $parameter)
    {
        $this->parameter = $parameter;
    }

    abstract public function get(): string;
}
