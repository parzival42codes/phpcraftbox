<?php declare(strict_types=1);

class ContainerExtensionTemplateParseCreateFilter extends ContainerExtensionTemplateParseCreate_abstract
{

    function parse():string
    {
        $parameter = $this->getParameter();

        return ContainerExtensionTemplateParseCreateFilterHelper::getFilter($parameter['ident']);
    }


}

