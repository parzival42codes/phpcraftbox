<?php declare(strict_types=1);

Class ContainerExtensionTemplateParseFunctionForeach extends ContainerExtensionTemplateParseFunction_abstract
{

    function parse():string
    {
        $parameter = $this->getParameter();
        return '';
    }
}
