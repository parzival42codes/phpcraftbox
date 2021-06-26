<?php

class ContainerExtensionTemplateTagTableTableDate extends
    ContainerExtensionTemplateTagTableTable_abstract_modification
{
    public function get(string $content, array $parameter, $modificationParameter): string
    {
        $dateTine = new DateTime($content);
        return $dateTine->format((string)Config::get('/environment/datetime/format'));
    }
}
