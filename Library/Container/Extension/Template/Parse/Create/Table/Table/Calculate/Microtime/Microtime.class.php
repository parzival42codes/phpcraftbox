<?php

class ContainerIndexTableTableCalculateMicrotime extends ContainerExtensionTemplateParseCreateTableTable_abstract_modification
{
    public function get(string $content, array $parameter, $modificationParameter): string
    {
        return \ContainerHelperCalculate::calculateMicroTimeDisplay((float)$content);
    }
}
