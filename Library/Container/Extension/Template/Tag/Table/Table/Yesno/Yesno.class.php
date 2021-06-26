<?php

class ContainerExtensionTemplateTagTableTableYesno extends
    ContainerExtensionTemplateTagTableTable_abstract_modification
{
    public function get(string $content, array $parameter, $modificationParameter): string
    {
        $content = (int)$content;

        if ($content == 1) {
            $contentValue = \ContainerFactoryLanguage::get('/ContainerExtensionTemplateTagTableTableYesno/yes');
        }
        else {
            $contentValue = \ContainerFactoryLanguage::get('/ContainerExtensionTemplateTagTableTableYesno/no');
        }

        return $contentValue;
    }
}
