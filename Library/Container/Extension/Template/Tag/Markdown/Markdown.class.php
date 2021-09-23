<?php declare(strict_types=1);

class ContainerExtensionTemplateTagMarkdown extends Base
{

    /**
     * @param ContainerExtensionTemplate $template
     */
    public static function setFunction(ContainerExtensionTemplate $template): void
    {
        $template::setRegisteredFunctions('_markdown',
            function ($content, $htmlTags, $templateObject) {
                $markdown = new ContainerHelperConvertMarkdown($content);

                return $markdown->get();
            });
    }

}
