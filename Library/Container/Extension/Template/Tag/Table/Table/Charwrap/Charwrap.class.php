<?php

class ContainerIndexTableTableCharwrap extends ContainerExtensionTemplateTagTableTable_abstract_modification
{
    public function get(string $content, array $parameter, $modificationParameter): string
    {
        $result = preg_replace('@([' . ($modificationParameter['char'] ?? '') . '])@si',
                               '$1&shy;',
                               $content);

        if ($result === null) {
            throw new DetailedException('pregReplaceError',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                $modificationParameter['char']
                                            ]
                                        ]);
        }

        return $result;
    }
}
