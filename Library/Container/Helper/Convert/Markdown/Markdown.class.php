<?php

class ContainerHelperConvertMarkdown extends Base
{
    protected array  $contentMarkdownExploded = [];
    protected int    $contentMarkdownCounter  = 0;
    protected array  $contentMarkdown         = [];
    protected bool   $paragraph               = false;

    public function convert($content): string
    {
        # https://www.markdownguide.org/basic-syntax/

        d($content);
        $this->contentMarkdownExploded = explode("\n",
                                                 $content);

        $contentExplodedCount = (count($this->contentMarkdownExploded) - 1);

        for ($i = 0; $i <= $contentExplodedCount; $i++) {
            $this->contentMarkdownCounter = $i;
            $contentMarkdownItemTrimmed   = trim($this->contentMarkdownExploded[$i]);

            $identFind = explode(' ',
                                 $contentMarkdownItemTrimmed,
                                 2);

            if (empty($identFind[0])) {
                $this->switchParagraph();
            }
            else {
                if ($this->checkHtmlTags($identFind[1])) {
                    $this->contentMarkdown[] = $contentMarkdownItemTrimmed;
                }
                else {
                    switch ($identFind[0]) {
                        case '#';
                        case '##';
                        case '###';
                        case '####';
                        case '#####';
                        case '######';
                            $this->markdownHeader($identFind[0],
                                                  $identFind[1]);
                            break;
                        default:
                            $this->contentMarkdown[] = $contentMarkdownItemTrimmed . '<br />';
                    }
                }
            }

        }

        d(implode("\n",
                  $this->contentMarkdown));
        eol();

        return $content;
    }

    protected function checkHtmlTags($contentItem): bool
    {
        return ($contentItem !== strip_tags($contentItem));
    }

    protected function markdownHeader($find, $content): void
    {
        $strLen                  = strlen($find[0]);
        $this->contentMarkdown[] = '<h' . $strLen . '>' . $content . '</h' . $strLen . '>';
    }

    protected function switchParagraph(): void
    {
        $prevEmpty = empty($this->contentMarkdownExploded[($this->contentMarkdownCounter - 1)] ?? null);
        $nextEmpty = empty($this->contentMarkdownExploded[($this->contentMarkdownCounter + 1)] ?? null);

        if ($prevEmpty && $nextEmpty) {
            $this->contentMarkdown[] = '<br />';
            return;
        }

        if ($this->paragraph === true) {
            $this->paragraph         = false;
            $this->contentMarkdown[] = '</p>';

            if (!$nextEmpty) {
                d($this->contentMarkdownExploded[($this->contentMarkdownCounter + 1)]);
                $this->paragraph         = true;
                $this->contentMarkdown[] = '<p>';
            }
            else {
                d($this->contentMarkdownExploded[($this->contentMarkdownCounter + 1)]);
                $this->contentMarkdown[] = '<br />';
            }

        }
        else {
            $this->paragraph         = true;
            $this->contentMarkdown[] = '<p>';
        }

    }

}
