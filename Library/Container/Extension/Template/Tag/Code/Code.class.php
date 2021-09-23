<?php declare(strict_types=1);

class ContainerExtensionTemplateTagCode extends Base
{

    /**
     * @param ContainerExtensionTemplate $template
     */
    public static function setFunction(ContainerExtensionTemplate $template): void
    {
        $template::setRegisteredFunctions('_code',
            function ($content, $htmlTags, $templateObject) {
                return '<span class="codeBox">' . nl2br(strtr(trim($content),
                                                              [
                                                                  '{' => '&#123;',
                                                                  '}' => '&#125;',
                                                              ])) . '</span>';
            });
    }

}
