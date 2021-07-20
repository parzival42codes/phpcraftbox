<?php

class ContainerHelperConvertMarkdown extends Base
{
    protected string $contentMarkdownItem = '';
    protected array  $contentMarkdown     = [];
    protected bool   $paragraph           = false;

    public function convert($content): string
    {
        # https://github.com/adam-p/markdown-here/wiki/Markdown-Cheatsheet

        d($content);
        $contentExploded = explode("\n",
                                   $content);

        foreach ($contentExploded as $contentMarkdownKey => $contentMarkdownItem) {
            $contentMarkdownItemTrimmed = trim($contentMarkdownItem);

            if (empty($contentMarkdownItemTrimmed)) {
                $this->switchParagraph();
            }
            else {
                if ($this->checkHtmlTags($contentMarkdownItemTrimmed)) {
                    $this->contentMarkdown[] = $contentMarkdownItemTrimmed;
                }
                else {
                    $this->contentMarkdownItem = $contentMarkdownItemTrimmed;
                    if ($this->markdownHeader()) {
                        continue;
                    }
                    else {
                        $this->contentMarkdown[] = $contentMarkdownItemTrimmed;
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

    protected function markdownHeader(): bool
    {
        if (
            strpos($this->contentMarkdownItem,
                   '#') === 0
        ) {
            $headerCheck = explode(' ',
                                   $this->contentMarkdownItem,
                                   2);

            $strLen                  = strlen($headerCheck[0]);
            $this->contentMarkdown[] = '<h' . $strLen . '>' . $headerCheck[1] . '</ h' . $strLen . '>';

            return true;
        }
        else {
            return false;
        }
    }

    protected function switchParagraph(): void
    {
        if ($this->paragraph === true) {
            $this->paragraph         = false;
            $this->contentMarkdown[] = '</p>';
        }
        else {
            $this->paragraph         = true;
            $this->contentMarkdown[] = '<p>';
        }
    }

}
