<?php

class ContainerIndexTableTablePercent extends ContainerExtensionTemplateTagTableTable_abstract_modification
{
    public function get(string $content, array $parameter, $modificationParameter): string
    {
            //$attribute->set('style', 'background', 'background: green;');
            return $content;
    }
}
