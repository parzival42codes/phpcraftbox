<?php

class ContainerExtensionTemplateTagTableTableTest extends
    ContainerExtensionTemplateTagTableTable_abstract_modification
{
    public function get(string $content, array $parameter, $modificationParameter): string
    {

        debugDump($modificationParameter);

        //$attribute->set('style', 'background', 'background: green;');
        return $modificationParameter[0] . ' ' . $content . ' ' . $modificationParameter[1];
    }
}
