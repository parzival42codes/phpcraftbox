<?php declare(strict_types=1);

class ContainerExtensionTemplateParseInsertRegistry extends ContainerExtensionTemplateParseInsert_abstract
{

    function parse(): string
    {
        $parameter = $this->getParameter();
        return (string)Config::get('/environment/config/'.$parameter['path'],
                                   '');
    }
}
