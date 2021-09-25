<?php declare(strict_types=1);

class ContainerExtensionTemplateTagIfthen extends Base
{

    /**
     * @param ContainerExtensionTemplate $template
     *
     */
    public static function setFunction(ContainerExtensionTemplate $template): void
    {
        $template::setRegisteredFunctions('_ifthen',
            function ($content, $htmlTags, $templateObject) {
                CoreDebugLog::addLog('/Template/Tag/IfThen',
                                     ($htmlTags['ifthen'] ?? '') . ' => ' . $htmlTags['value'] . ' => ' . $templateObject->getAssign($htmlTags['assigned']));

                /** @var ContainerExtensionTemplate $templateObject */

                switch ($htmlTags['ifthen']) {
                    case 'notEmpty':

                        $valueContent = $templateObject->getAssign($htmlTags['assigned']);
                        if (!empty($valueContent)) {
                            return $content;
                        }
                        else {
                            return '';
                        }
                    case 'equal':
                        if ($htmlTags['value'] == $templateObject->getAssign($htmlTags['assigned'])) {
                            return $content;
                        }
                        else {
                            return '';
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
