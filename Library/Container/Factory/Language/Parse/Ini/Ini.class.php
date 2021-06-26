<?php

class ContainerFactoryLanguageParseIni extends Base
{
    protected $language = [];
    protected string      $iniClean = '';

    public function __construct(string $value)
    {
        $iniToParse = explode(PHP_EOL,
                              $value);

        array_walk($iniToParse,
            function (&$iniToParseItem) {
                $iniToParseItem = trim($iniToParseItem);
            });

        $this->iniClean = implode(PHP_EOL,
                                  $iniToParse);

        $this->language = parse_ini_string($this->iniClean,
                                           true);
    }

    public function get(?string $language = null): array
    {
        if ($language === null) {
            $language = Config::get('/environment/config/iso_language_code');
        }

        if (isset($this->language[$language])) {
            return $this->language[$language];
        }
        else {
            $firstLanguage = reset($this->language);
            return (!empty($firstLanguage) ? $firstLanguage : []);
        }
    }

    /**
     * @return string
     */
    public function getIniClean(): string
    {
        return $this->iniClean;
    }

    /**
     * @return array
     */
    public function getLanguages(): array
    {
        return $this->language;
    }

}
