<?php

class ContainerHelperConvertMarkdown extends Base
{
    private string $contentMarkdown = '';

    public function __construct($content)
    {
//        d($content);
        $this->contentMarkdown = $this->convert($content);
//        d($this->contentMarkdown);
//        eol();
    }

    public function convert($content): string
    {
        # https://www.markdownguide.org/basic-syntax/

        $paragraph  = false;
        $list       = false;
        $blockquote = false;

        $contentMarkdown = [];

        $contentMarkdownExploded   = explode("\n",
                                             $content);
        $contentMarkdownExploded[] = '';

        $contentExplodedCount = (count($contentMarkdownExploded) - 1);

        for ($i = 0; $i <= $contentExplodedCount; $i++) {
            $contentMarkdownItemTrimmed = trim($contentMarkdownExploded[$i]);

            $identFind = explode(' ',
                                 $contentMarkdownItemTrimmed,
                                 2);

            if (empty($identFind[0])) {
                $this->switchListClose($contentMarkdown,
                                       $list);
                $this->switchBlockquoteClose($contentMarkdown,
                                             $blockquote);
                $this->switchParagraph($contentMarkdown,
                                       $contentMarkdownExploded,
                                       $i,
                                       $paragraph);
            }
            else {
                switch ($identFind[0]) {
                    case '#';
                    case '##';
                    case '###';
                    case '####';
                    case '#####';
                    case '######';
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
                        $this->switchListClose($contentMarkdown,
                                               $list);
                        $this->switchBlockquoteClose($contentMarkdown,
                                                     $blockquote);
                        $contentMarkdown[] = '<hr />';
                        break;
                    case '*';
                    case '-';
                        $this->switchList($contentMarkdown,
                                          $identFind[1],
                                          $list);
                        break;
                    case '>';
                        $this->switchBlockquote($contentMarkdown,
                            ($identFind[1] ?? ''),
                                                $blockquote);
                        break;
                    default:
                        $this->switchListClose($contentMarkdown,
                                               $list);
                        $this->switchBlockquoteClose($contentMarkdown,
                                                     $blockquote);
                        $contentMarkdown[] = $contentMarkdownItemTrimmed . '<br />';
                }

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

    protected function switchParagraph(&$contentMarkdown, $contentMarkdownExploded, $i, &$paragraph): void
    {
        $prevEmpty = empty($contentMarkdownExploded[($i - 1)] ?? null);
        $nextEmpty = empty($contentMarkdownExploded[($i + 1)] ?? null);

        if ($prevEmpty && $nextEmpty) {
            $contentMarkdown[] = '<br />';
            return;
        }

        if ($paragraph === true) {
            $paragraph         = false;
            $contentMarkdown[] = '</p>';

            if (!$nextEmpty) {
                $paragraph         = true;
                $contentMarkdown[] = '<p>';
            }
            else {
                $contentMarkdown[] = '<br />';
            }

        }
        else {
            $paragraph         = true;
            $contentMarkdown[] = '<p>';
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
}
