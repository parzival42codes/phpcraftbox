<?php

class ContainerHelperConvertMarkdown extends Base
{
    protected array $contentMarkdownExploded = [];
    protected int   $contentMarkdownCounter  = 0;
    protected array $contentMarkdown         = [];
    protected int   $contentLevel            = 0;
    protected bool  $paragraph               = false;
    protected bool  $list                    = false;
    protected bool  $blockquote              = false;

    public function __construct($content)
    {
        $this->convert($content);
    }
    public function convert($content)
    {
        # https://www.markdownguide.org/basic-syntax/

        d($content);
        d($this->contentLevel);
        $this->contentMarkdownExploded[$this->contentLevel]   = explode("\n",
                                                                        $content);
        $this->contentMarkdownExploded[$this->contentLevel][] = '';

        $contentExplodedCount = (count($this->contentMarkdownExploded[$this->contentLevel]) - 1);

        for ($i = 0; $i <= $contentExplodedCount; $i++) {
            $this->contentMarkdownCounter = $i;
            $contentMarkdownItemTrimmed   = trim($this->contentMarkdownExploded[$this->contentLevel][$i]);

            $identFind = explode(' ',
                                 $contentMarkdownItemTrimmed,
                                 2);

            if (empty($identFind[0])) {
                $this->switchListClose();
                $this->switchBlockquoteClose();
                $this->switchParagraph();
            }
            else {
                switch ($identFind[0]) {
                    case '#';
                    case '##';
                    case '###';
                    case '####';
                    case '#####';
                    case '######';
                        $this->switchListClose();
                        $this->switchBlockquoteClose();
                        $this->markdownHeader($identFind[0],
                                              $identFind[1]);
                        break;
                    case '---';
                    case '***';
                    case '___';
                        $this->switchListClose();
                        $this->switchBlockquoteClose();
                        $this->contentMarkdown[] = '<hr />';
                        break;
                    case '*';
                    case '-';
                        $this->switchList($identFind[1]);
                        break;
                    case '>';
                        $this->switchBlockquote($identFind[1]);
                        break;
                    default:
                        $this->switchListClose();
                        $this->switchBlockquoteClose();
                        $this->contentMarkdown[] = $contentMarkdownItemTrimmed . '<br />';
                }

            }

        }

    }

    public function get()
    {

        $content = implode("\n",
                           $this->contentMarkdown);

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

        $content = preg_replace_callback("!\<blockquote\>(.*?)\<\/blockquote\>!si",
                                         [
                                             $this,
                                             'callbackRegexBlockquote'
                                         ],
                                         $content);

//        d($content);
//        eol();

        return $content;
    }

    protected function hasHtmlTags($contentItem): bool
    {
        return ($contentItem !== strip_tags($contentItem));
    }

    protected function markdownHeader($find, $content): void
    {
        $strLen                  = strlen($find);
        $this->contentMarkdown[] = '<h' . $strLen . '>' . $content . '</h' . $strLen . '>';
    }

    protected function switchParagraph(): void
    {
        $prevEmpty = empty($this->contentMarkdownExploded[$this->contentLevel][($this->contentMarkdownCounter - 1)] ?? null);
        $nextEmpty = empty($this->contentMarkdownExploded[$this->contentLevel][($this->contentMarkdownCounter + 1)] ?? null);

        if ($prevEmpty && $nextEmpty) {
            $this->contentMarkdown[] = '<br />';
            return;
        }

        if ($this->paragraph === true) {
            $this->paragraph         = false;
            $this->contentMarkdown[] = '</p>';

            if (!$nextEmpty) {
                $this->paragraph         = true;
                $this->contentMarkdown[] = '<p>';
            }
            else {
                $this->contentMarkdown[] = '<br />';
            }

        }
        else {
            $this->paragraph         = true;
            $this->contentMarkdown[] = '<p>';
        }

    }

    protected function switchList($content): void
    {
        if ($this->list === false) {
            $this->list              = true;
            $this->contentMarkdown[] = '<ul>';
        }

        $this->contentMarkdown[] = '<li>' . $content . '</li>';
    }

    protected function switchListClose(): void
    {
        if ($this->list === true) {
            $this->list              = false;
            $this->contentMarkdown[] = '</ul>';
        }
    }

    protected function switchBlockquote($content): void
    {
        if ($this->blockquote === false) {
            $this->blockquote        = true;
            $this->contentMarkdown[] = '<blockquote>';
        }

        $this->contentMarkdown[] = $content;
    }

    protected function switchBlockquoteClose(): void
    {
        if ($this->blockquote === true) {
            $this->blockquote        = false;
            $this->contentMarkdown[] = '</blockquote>';
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

    protected function callbackRegexBlockquote($content): void
    {
        $this->contentLevel++;
        $this->convert($content[1]);
        $this->contentLevel--;
    }
}
