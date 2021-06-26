<?php

class ContainerExtensionTemplateParseCreateTableTableYesno extends
    ContainerExtensionTemplateParseCreateTableTable_abstract_modification
{
    public function get(string $content, array $parameter, $modificationParameter): string
    {
        $content = (int)$content;

        if ($content == 1) {
            $contentValue = \ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateTableTableYesno/yes');
        }
        else {
            $contentValue = \ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateTableTableYesno/no');
        }

        return $contentValue;
    }
}
