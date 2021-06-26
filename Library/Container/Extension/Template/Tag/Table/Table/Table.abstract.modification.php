<?php

abstract class ContainerExtensionTemplateTagTableTable_abstract_modification extends Base
{
    protected string $content = '';

    abstract function get(string $content, array $parameter, array $modificationParameter): string;

}
