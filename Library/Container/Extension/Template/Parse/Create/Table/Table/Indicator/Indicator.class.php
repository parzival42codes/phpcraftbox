<?php

class ContainerIndexTableTableIndicator extends ContainerExtensionTemplateParseCreateTableTable_abstract_modification
{
    public function get(string $content, array $parameter, $modificationParameter): string
    {

        $color = '';
        if (isset($modificationParameter['breakpoints']) && is_array($modificationParameter['breakpoints'])) {
            $content = (float)$content;

            foreach ($modificationParameter['breakpoints'] as $modificationParameterBreakpointsValue => $modificationParameterBreakpointsStepColor) {
                if ($content >= $modificationParameterBreakpointsValue) {
                    $color = $modificationParameterBreakpointsStepColor;
                }
            }
        }
        return '<span style="background-color:' . $color . '; width: 100%; display: block;">&nbsp;</span>';
    }
}
