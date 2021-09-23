<?php declare(strict_types=1);

class ContainerExtensionTemplateTagPage extends Base
{

    /**
     * @param ContainerExtensionTemplate $template
     */
    public static function setFunction(ContainerExtensionTemplate $template): void
    {
        $template::setRegisteredFunctions('_page',
            function (string $content, array $htmlTags, ContainerExtensionTemplate $templateObject) {
                return $templateObject->addParseFinal($content);
            });
    }

}
