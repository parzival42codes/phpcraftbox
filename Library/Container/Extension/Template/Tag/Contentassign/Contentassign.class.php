<?php declare(strict_types=1);

class ContainerExtensionTemplateTagContentassign extends Base
{

    /**
     * @param ContainerExtensionTemplate $template
     *
     */
    public static function setFunction(ContainerExtensionTemplate $template): void
    {
        $template->setRegisteredFunctions('_contentassign',
            function ($content, $htmlTags, $templateObject) {

                /** @var ContainerExtensionTemplate $templateObject */
                /** @var ContainerExtensionTemplate $templateWork */
                $templateWork = clone $templateObject;

                $templateWork->set($content);
                $templateWork->parseQuote();
                $templateWork->parse();
                $templateWork->parse();

                $templateObject->assign($htmlTags['assign'],$templateWork->get());

            });
    }

}
