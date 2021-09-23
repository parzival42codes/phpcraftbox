<?php declare(strict_types=1);

class ContainerExtensionTemplateTagDontparse extends Base
{

    /**
     * @param ContainerExtensionTemplate $template
     */
    public static function setFunction(ContainerExtensionTemplate $template): void
    {
        $template::setRegisteredFunctions('_dontparse',
            function ($content, $htmlTags, $templateObject) {

//                $uniqueID = 'template_tag_dontparse_' . uniqid();
//
//                /** @var ContainerIndexPage $page */
//                $page = Container::getInstance('ContainerIndexPage');
//                /** @var ContainerExtensionTemplate $pageTemplate */
//                $pageTemplate = $page->getTemplatePage();
//                $pageTemplate->assign($uniqueID,
//                                      $content);
//
//                return '{$' . $uniqueID . '}';

                return strtr($content,
                             [
                                 '{' => '&#123;',
                                 '}' => '&#125;',
                             ]);
            });
    }

}
