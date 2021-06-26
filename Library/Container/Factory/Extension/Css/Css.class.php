<?php

class ContainerFactoryExtensionCss extends Base
{

    /**
     * CSS Worker
     *
     * @example (at)description:"<text>"; set the description for the Style
     * @example (at)media:"<text>"; set the mediaQuery for the followed Styles
     * @example (at)<text>: <content>; set the variable
     *
     */
    protected string $css                   = '';
    protected array  $cssParsed             = [];
    protected array  $cssDescription        = [];
    protected array  $cssSet                = [];
    protected array  $cssParsedAboveTheFold = [];
    protected string $cssAboveTheFold       = '';

    public function import(array $filename = []): void
    {

    }

    public function parse(): void
    {
        $this->css = preg_replace('@/\*([^/\*\*/]+)\*/@s',
                                  '',
                                  $this->css);

        $regex = "/(.*?){(.*?)}/isS";
        preg_match_all($regex,
                       $this->css,
                       $output,
                       PREG_SET_ORDER);

        foreach ($output as $elem2) {
            $elem2[1] = trim(strtr($elem2[1],
                                   [
                                       ', ' => ',',
                                       ' ,' => ',',
                                       "\n" => '',
                                       "\r" => ''
                                   ]));
            $elem2[2] = trim(strtr($elem2[2],
                                   [
                                       "\n" => '',
                                       "\r" => ''
                                   ]));

            $parseMedia      = '_';
            $cssAboveTheFold = 0;

            preg_match_all('!(.*):(.*);+!isU',
                           $elem2[2],
                           $cssMedia,
                           PREG_SET_ORDER);
            foreach ($cssMedia as $cssMediaItem) {

                $cssMediaItem[1] = trim($cssMediaItem[1]);
                $cssMediaItem[2] = trim($cssMediaItem[2]);

                if (
                    strpos($cssMediaItem[1],
                           '@') === 0
                ) {
                    //                    $parseKey = substr($cssMediaItem[2], 1);
                    $parseValue = $cssMediaItem[2];
                    if (
                        strpos($parseValue,
                               '"') === 0
                    ) {
                        $parseValue = substr($cssMediaItem[2],
                                             1,
                                             -1);
                    }

                    $parseKey = substr($cssMediaItem[1],
                                       1);

                    if ($parseKey === 'media') {
                        $parseMedia = $this->cssSet[$parseValue] ?? $parseValue;
                    }
                    elseif ($parseKey === 'description') {
                        $this->cssDescription[$elem2[1]] = $parseValue;
                    }
                    elseif ($parseKey === 'aboveTheFold') {
                        $cssAboveTheFold = (int)$parseValue;
                    }
                    else {
                        $this->cssSet['#' . $parseKey] = $parseValue;
                    }
                }
                else {
                    $elem2[1] = preg_replace('@(/\*(.*)\*/)@siS',
                                             '',
                                             $elem2[1]);
                    if ($cssAboveTheFold == 0) {
                        $this->cssParsed[$parseMedia][$elem2[1]][$cssMediaItem[1]] = $cssMediaItem[2];
                    }
                    elseif ($cssAboveTheFold == 1) {
                        $this->cssParsedAboveTheFold[$parseMedia][$elem2[1]][$cssMediaItem[1]] = $cssMediaItem[2];
                    }
                }
            }
        }

        $this->css             = $this->parseCSS($this->cssParsed);
        $this->cssAboveTheFold = $this->parseCSS($this->cssParsedAboveTheFold);
    }

    protected function parseCSS(array $cssToWork): string
    {
        $implodeTagAndContent = [];
        $scanDoubleHash       = [];
        $scanDoubleHashTag    = [];

        foreach ($cssToWork as $media => $stylesheet) {
            foreach ($stylesheet as $stylesheetTag => $stylesheetContentRow) {

                foreach ($stylesheetContentRow as $stylesheetType => $stylesheetContent) {
                    $implodeTagAndContent[$media][$stylesheetTag][] = $stylesheetType . ':' . $stylesheetContent . ';';
                }

                $scanDoubleHashImplode                          = implode($implodeTagAndContent[$media][$stylesheetTag]);
                $scanDoubleHashItem                             = md5($scanDoubleHashImplode);
                $scanDoubleHash[$media][$scanDoubleHashItem][]  = $stylesheetTag;
                $scanDoubleHashTag[$media][$scanDoubleHashItem] = $scanDoubleHashImplode;
            }
        }

        $cssPrepare = [];
        $css        = '';

        foreach ($scanDoubleHash as $scanDoubleHashMedia => $scanDoubleHashRow) {
            if ($scanDoubleHashMedia === '_') {
                foreach ($scanDoubleHashRow as $scanDoubleHashKey => $scanDoubleHashContent) {
                    $css .= implode(',',
                                    $scanDoubleHashContent) . '{' . $scanDoubleHashTag[$scanDoubleHashMedia][$scanDoubleHashKey] . '}';
                }
            }
            else {
                if (isset($implodeTagAndContent[$scanDoubleHashMedia]) && !empty($implodeTagAndContent[$scanDoubleHashMedia])) {
                    $css .= ' @media ' . $scanDoubleHashMedia . ' {';
                    foreach ($scanDoubleHashRow as $scanDoubleHashKey => $scanDoubleHashContent) {
                        $css .= implode(',',
                                        $scanDoubleHashContent) . '{' . $scanDoubleHashTag[$scanDoubleHashMedia][$scanDoubleHashKey] . '}';
                    }
                    $css .= ' }';
                }
            }
        }

        $CSSCallbackParse = function ($replace) {
            return base64_decode($replace[1]);
        };

        $css = preg_replace_callback('!\/\*BASE64\:(.*?)\*\/!isS',
                                     $CSSCallbackParse,
                                     $css);
        return strtr($css,
                     $this->cssSet);
    }

    public function getDescription(?string $css = null)
    {
        if ($css !== null) {
            return ($this->cssDescription[$css] ?? null);
        }
        else {
            return $this->cssDescription;
        }
    }

    public function get(string $css): array
    {
        $output = [];
        foreach ($this->cssParsed as $cssParsedKey => $cssParsedItem) {
            if (isset($cssParsedItem[$css])) {
                $output[$cssParsedKey] = $cssParsedItem[$css];
            }
        }

        return $output;
    }

    public function getCss(): string
    {
        return $this->css;
    }

    public function setCSS(string $css, ?string $class = null): void
    {
        if ($class !== null) {
            $css = strtr($css,
                         [
                             'thisClass' => Core::getRootClass($class),
                         ]);
        }

        $this->css .= $css;
    }

    public function resetCss(): void
    {
        $this->css = '';
    }

    public function getCssAboveTheFold(): string
    {
        return $this->cssAboveTheFold;
    }

}
