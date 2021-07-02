<?php declare(strict_types=1);

class ContainerExtensionTemplateTagIfthen extends Base
{

    /**
     * @param ContainerExtensionTemplate $template
     *
     */
    public static function setFunction(ContainerExtensionTemplate $template): void
    {
        $template->setRegisteredFunctions('_ifthen',
            function ($content, $htmlTags, $templateObject) {
                /** @var ContainerExtensionTemplate $templateObject */

                switch ($htmlTags['ifthen']) {
                    case 'notEmpty':

                        $valueContent = $templateObject->getAssign($htmlTags['value']);

                        debugDump($valueContent);
                        debugDump($content);

                        if (!empty($valueContent)) {
                            return $content;
                        }
                        else {
                            return '';
                        }
                    case 'assigned':
                        if ($htmlTags['wanted'] === $templateObject->getAssign($htmlTags['assigned'])) {
                        }
                        else {

                        }
                }


            });
    }

}

//case 'notEmpty':
//                        $templateWork->set($content);
//                        $templateWork->parseQuote();
//                        $templateWork->parse();
//
//                        if (!empty($templateWork->get())) {
//                            return $templateWork->get();
//                        }
//                        else {
//                            return '';
//                        }
//                        break;
