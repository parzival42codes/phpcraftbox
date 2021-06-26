<?php

class ContainerIndexTableTableEllipsis extends ContainerExtensionTemplateTagTableTable_abstract_modification
{
    public function get(string $content, array $parameter, $modificationParameter): string
    {
        $modificationParameter->set('class', null, 'table-cell-ellipsis');
            return $content;
    }
}
