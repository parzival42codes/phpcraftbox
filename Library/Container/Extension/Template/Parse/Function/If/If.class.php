<?php declare(strict_types=1);

class ContainerExtensionTemplateParseFunctionIf extends ContainerExtensionTemplateParseFunction_abstract
{

    function parse(): string
    {
        return 'FooBar';
    }
}
