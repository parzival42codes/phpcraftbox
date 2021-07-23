<?php

class ContainerHelperConvertMarkdown extends Base
{
    private string $contentMarkdown = '';

    public function __construct($content)
    {
        $this->contentMarkdown = $this->convert($content);
    }

    public function convert($content): string
    {
        # https://www.markdownguide.org/basic-syntax/

        $paragraph  = false;
        $list       = false;
        $blockquote = false;

        $contentMarkdown = [];

        $contentMarkdownExploded = explode("\n",
                                           $content);

        $contentExplodedCount = (count($contentMarkdownExploded) - 1);

        for ($i = 0; $i <= $contentExplodedCount; $i++) {
            $contentMarkdownItemTrimmed = trim($contentMarkdownExploded[$i]);

            $identFind = explode(' ',
                                 $contentMarkdownItemTrimmed,
                                 2);

            switch ($identFind[0]) {
                case '#';
                case '##';
                case '###';
                case '####';
                case '#####';
                case '######';
                    $this->switchParagraphClose($contentMarkdown,
                                                $paragraph);
                    $this->switchListClose($contentMarkdown,
                                           $list);
                    $this->switchBlockquoteClose($contentMarkdown,
                                                 $blockquote);
                    $this->markdownHeader($contentMarkdown,
                                          $identFind[0],
                                          $identFind[1]);
                    break;
                case '---';
                case '***';
                case '___';
                    $this->switchParagraphClose($contentMarkdown,
                                                $paragraph);
                    $this->switchListClose($contentMarkdown,
                                           $list);
                    $this->switchBlockquoteClose($contentMarkdown,
                                                 $blockquote);
                    $contentMarkdown[] = '<hr />';
                    break;
                case '*';
                case '-';
                    $this->switchParagraphClose($contentMarkdown,
                                                $paragraph);
                    $this->switchList($contentMarkdown,
                                      $identFind[1],
                                      $list);
                    break;
                case '>';
                    $this->switchParagraphClose($contentMarkdown,
                                                $paragraph);
                    $this->switchBlockquote($contentMarkdown,
                        ($identFind[1] ?? ''),
                                            $blockquote);
                    break;
                default:
                    $this->switchParagraph($contentMarkdown,
                                           $contentMarkdownExploded,
                                           $i,
                                           $paragraph);

                    $this->switchListClose($contentMarkdown,
                                           $list);
                    $this->switchBlockquoteClose($contentMarkdown,
                                                 $blockquote);

//                    if (!empty($contentMarkdownItemTrimmed)) {
                        $contentMarkdown[] = $contentMarkdownItemTrimmed . '<br />';

//                    }
            }

        }

        $content = implode("\n",
                           $contentMarkdown);

        $content = preg_replace_callback("!\*\*(.*?)\*\*!i",
                                         [
                                             $this,
                                             'callbackRegexBold'
                                         ],
                                         $content);

        $content = preg_replace_callback("!\_\_(.*?)\_\_!i",
                                         [
                                             $this,
                                             'callbackRegexBold'
                                         ],
                                         $content);

        $content = preg_replace_callback("!\_(.*?)\_!i",
                                         [
                                             $this,
                                             'callbackRegexItalic'
                                         ],
                                         $content);

        $content = preg_replace_callback("!\*(.*?)\*!i",
                                         [
                                             $this,
                                             'callbackRegexItalic'
                                         ],
                                         $content);

        $content = preg_replace_callback("!\`(.*?)\`!i",
                                         [
                                             $this,
                                             'callbackRegexCodeSingle'
                                         ],
                                         $content);

        $content = preg_replace_callback("!\[(.*?)\]\((.*)\)!i",
                                         [
                                             $this,
                                             'callbackRegexLink'
                                         ],
                                         $content);

        return preg_replace_callback("!\<blockquote\>(.*?)\<\/blockquote\>!si",
                                     [
                                         $this,
                                         'callbackRegexBlockquote'
                                     ],
                                     $content);

    }

    public function get()
    {
        return $this->contentMarkdown;
    }

    protected function markdownHeader(&$contentMarkdown, $find, $content): void
    {
        $strLen            = strlen($find);
        $contentMarkdown[] = '<h' . $strLen . '>' . $content . '</h' . $strLen . '>';
    }

    protected function switchParagraph(&$contentMarkdown, &$contentMarkdownExploded, $i, &$paragraph): void
    {
        $isEmpty = empty($contentMarkdownExploded[$i]);

        if ($isEmpty) {
            return;
        }

        if ($paragraph === false) {
            $paragraph         = true;
            $contentMarkdown[] = '<p>';
        }

    }

    protected function switchParagraphClose(&$contentMarkdown, &$paragraph): void
    {
        if ($paragraph === true) {
            $paragraph         = false;
            $contentMarkdown[] = '</p>';
        }
    }

    protected function switchList(&$contentMarkdown, $content, &$list): void
    {
        if ($list === false) {
            $list              = true;
            $contentMarkdown[] = '<ul>';
        }

        $contentMarkdown[] = '<li>' . $content . '</li>';
    }

    protected function switchListClose(&$contentMarkdown, &$list): void
    {
        if ($list === true) {
            $list              = false;
            $contentMarkdown[] = '</ul>';
        }
    }

    protected function switchBlockquote(&$contentMarkdown, $content, &$blockquote): void
    {
        if ($blockquote === false) {
            $blockquote        = true;
            $contentMarkdown[] = '<blockquote>';
        }

        $contentMarkdown[] = $content;
    }

    protected function switchBlockquoteClose(&$contentMarkdown, &$blockquote): void
    {
        if ($blockquote === true) {
            $blockquote        = false;
            $contentMarkdown[] = '</blockquote>';
        }
    }

    protected function callbackRegexBold($var): string
    {
        return '<strong>' . $var[1] . '</strong>';
    }

    protected function callbackRegexItalic($var): string
    {
        return '<em>' . $var[1] . '</em>';
    }

    protected function callbackRegexBlockquote($content): string
    {
        return '<blockquote>' . $this->convert($content[1] . '</blockquote>');
    }

    protected function callbackRegexCodeSingle($content): string
    {
        return '<code>' . $content[1] . '</code>';
    }

    protected function callbackRegexLink($content): string
    {
        $contentTitleExplode = explode(' ',
                                       $content[2],
                                       2);

        return '<a href="' . $contentTitleExplode[0] . '" ' . ((isset($contentTitleExplode[1]) ? 'title=' . $contentTitleExplode[1] : '')) . '>' . $content[1] . '</a>';

    }
}
