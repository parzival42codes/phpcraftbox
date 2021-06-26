<?php declare(strict_types=1);

class ContainerExtensionTemplateParseInsertConfig extends ContainerExtensionTemplateParseInsert_abstract
{

    function parse(): string
    {
        $parameter = $this->getParameter();
        return (string)Config::get('/' . $parameter['class'] . $parameter['path']);
    }
}
