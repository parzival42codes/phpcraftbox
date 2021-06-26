<?php

class ContainerIndexTableTableHide extends ContainerExtensionTemplateTagTableTable_abstract_modification
{
    public function get(string $content, array $parameter, $modificationParameter): string
    {
        return '';
    }
}
