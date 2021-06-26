<?php declare(strict_types=1);

class ContainerExtensionTemplateParseInsertResources extends ContainerExtensionTemplateParseInsert_abstract
{

    function parse(): string
    {
        return ResourcesIcons::getIcon(($this->getParameter()['icon'] ?? ''));
    }
}
