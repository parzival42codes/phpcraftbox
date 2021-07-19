<?php

class ContainerHelperConvertMarkdown extends Base
{
    public function convert($content): string
    {
        # https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet

        d($content);
        $contentExploded = explode("\n",
                                   $content);
        $contentMarkdown = [];

        foreach ($contentExploded as $contentMarkdownItem) {
            $contentMarkdownItemTrimmed = trim($contentMarkdownItem);

            if (empty($contentMarkdownItemTrimmed)) {
                $contentMarkdown[] = '<br/>';
            }
            else {

            }


        }

        d(implode("\n",
                  $contentMarkdown));
        eol();

        return $content;
    }

    protected function checkHtmlTags ($contentItem) {

    }

}
