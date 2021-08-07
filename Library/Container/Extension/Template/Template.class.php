<?php
declare(strict_types=1);

/**
 * Class ContainerFactoryCrypt
 *
 * @method string parseCall(string $content) see _parseCall
 * ##
 */
class ContainerExtensionTemplate extends Base
{
    /*
     * Template laden, setzen, parsen und ausgeben
     *
     * Container::get('ContainerExtensionTemplate')->loadTemplate(__CLASS__, '<datei>,<datei>,....');
     */

    const SAVE_HTML_MARKER_START = '[Citx6e99ddejfDUAoX6L6nmguKcoWE]';
    const SAVE_HTML_MARKER_END   = '[LTd6nuGXZqbDK9s4PX6qXU8ASjgywB]';

    const SAVE_HTML_MARKER_START_HTML = '<span class="extensionTemplate-debug-marker">';
    const SAVE_HTML_MARKER_END_HTML   = '</span>';

    const SAVE_HTML_MARKER_LENGTH = 150;

    const PARSE_TYPE_SIMPLE        = 'simple';
    const PARSE_TYPE_COMPLEX       = 'complex';
    const PARSE_TYPE_COMPLEX_ERROR = 'complexError';
    const PARSE_TYPE_UNKNOWN       = 'unknown';

    const REPLACE_SAVE_START = 1;
    const REPLACE_SAVE_STOP  = 2;

    protected static array $replace     = [];
    protected static array $replaceMeta = [];
    protected static array $positions   = [];

    /**
     * Class ContainerExtensionTemplate
     * @method void set(string $value) set the Content
     * @method string get() get the Content
     * @method void assign(string $key, string $value) set Content to Key
     * @method void parse() Parse the Content
     */

    protected string                                   $id                       = '';
    protected string                                   $delimiterStart           = '';
    protected string                                   $delimiterStartInner      = '';
    protected string                                   $delimiterStartBorder     = '';
    protected string                                   $delimiterEnd             = '';
    protected string                                   $delimiterEndInner        = '';
    protected string                                   $delimiterEndBorder       = '';
    protected string                                   $template                 = '';
    protected array                                    $assign                   = [];
    protected array                                    $assignPart               = [];
    protected string                                   $assignPartSelected       = '';
    protected bool                                     $onErrorShowAssignReplace = false;
    protected array                                    $templateMeta             = [];
    protected string                                   $templateMetaClass        = '';
    protected ContainerExtensionTemplateInternalAssign $assignObject;
    protected array                                    $modifyTag                = [];

    /**
     * @var array
     */
    protected static array $finalTemplateValues = [];

    protected array $registeredFunctions = [];

    protected array        $dataCatch      = [];
    protected static array $dataCatchCache = [];

    public function __construct(?string $metaClass = null, ?string $id = null, ?ContainerExtensionTemplateInternalAssign $assign = null)
    {
        $this->reset();

        if (!empty($metaClass)) {
            $this->setMetaClass($metaClass);
        }

        if (!empty($id)) {
            $this->id = $id;
        }

        if ($assign === null) {
            $this->assignObject = Container::get('ContainerExtensionTemplateInternalAssign');
        }
        else {
            if (get_class($assign) === 'ContainerExtensionTemplateInternalAssign') {
                $this->assignObject = $assign;
            }
        }
    }

    public function reset(): void
    {
        $this->delimiterStartBorder     = '';
        $this->delimiterStart           = '{';
        $this->delimiterStartInner      = '{';
        $this->delimiterEnd             = '}';
        $this->delimiterEndInner        = '}';
        $this->delimiterEndBorder       = '';
        $this->template                 = '';
        $this->assign                   = [];
        $this->assignPart               = [];
        $this->assignPartSelected       = '';
        $this->onErrorShowAssignReplace = false;

        $this->templateMeta      = [];
        $this->templateMetaClass = '';
    }

    public function setMetaClass(string $class): void
    {
        $this->templateMetaClass = $class;
    }

    public static function initReplace(): void
    {
//        $rootClass = Core::getRootClass(__CLASS__);
//        if (\Container::callStatic('ContainerExtensionCache',
//                                   'getLibraryClassPath',
//                                   $rootClass . '_language_templates_json')) {
//            $language          = Container::get('ContainerExtensionLanguage',
//                                                 $rootClass,
//                                                 'templates');
//            self::$replace     = $language->getAll();
//            self::$replaceMeta = $language->getAllMeta();
//        }
    }

    public static function registerReplace(array $replace, array $replaceMeta = []): void
    {
        self::$replace     = array_merge(self::$replace,
                                         $replace);
        self::$replaceMeta = array_merge(self::$replaceMeta,
                                         $replaceMeta);
    }

    public static function getRegisterReplace(): array
    {
        return [
            'replace'     => self::$replace,
            'replaceMeta' => self::$replaceMeta,
        ];
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function set(string $template): void
    {
        $this->template = $template;
    }

    public function getAssign(?string $key = null)
    {
        return (($key === null) ? $this->assign : $this->assign[$key]);
    }

    public function resetAssign(): void
    {
        $this->assign = [];
    }

    public function assignArray(array $array): void
    {
        $this->assign = array_merge($this->assign,
                                    $array);
    }

    public function append(string $append): void
    {
        $this->template .= $append;
    }

    public function getDelimiterStart(): string
    {
        return $this->delimiterStart;
    }

    public function setDelimiterStart(string $var): void
    {
        $this->delimiterStart = $var;
    }

    public function getDelimiterStartInner(): string
    {
        return $this->delimiterStartInner;
    }

    public function setDelimiterStartInner(string $var): void
    {
        $this->delimiterStartInner = $var;
    }

    public function getDelimiterStartBorder(): string
    {
        return $this->delimiterStartBorder;
    }

    public function setDelimiterStartBorder(string $var): void
    {
        $this->delimiterStartBorder = $var;
    }

    public function getDelimiterEnd(): string
    {
        return $this->delimiterEnd;
    }

    public function setDelimiterEnd(string $var): void
    {
        $this->delimiterEnd = $var;
    }

    public function getDelimiterEndInner(): string
    {
        return $this->delimiterEndInner;
    }

    public function setDelimiterEndInner(string $var): void
    {
        $this->delimiterEndInner = $var;
    }

    public function getDelimiterEndBorder(): string
    {
        return $this->delimiterEndBorder;
    }

    public function setDelimiterEndBorder(string $var): void
    {
        $this->delimiterEndBorder = $var;
    }

    public function getOnErrorShowAssignReplace(): bool
    {
        return $this->onErrorShowAssignReplace;
    }

    public function setOnErrorShowAssignReplace(bool $status): void
    {
        $this->onErrorShowAssignReplace = $status;
    }

    /**
     * Only Text Replace
     *
     * @return void
     */
    public function parseString(): void
    {
        $assignVars       = $this->assignObject->get();
        $assignVarsString = [];

        foreach ($assignVars as $assignVarKey => $assignVarItem) {
            $assignVarsString['{$' . $assignVarKey . '}'] = $assignVarItem;
        }

        $this->template = strtr($this->template,
                                $assignVarsString);
    }

    /**
     * @param string $key
     *
     * @return callable
     */
    public function getRegisteredFunctions(string $key): callable
    {
        return $this->registeredFunctions[$key];
    }

    /**
     * @param string   $key
     * @param callable $value
     */
    public function setRegisteredFunctions(string $key, callable $value): void
    {
        $this->registeredFunctions[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function removeRegisteredFunctions(string $key): void
    {
        unset($this->registeredFunctions[$key]);
    }

    /**
     * Parse the Content
     *
     * @CMSprofilerSet          action parse
     * @CMSprofilerOption       isFunction true
     * @CMSprofilerOption       deph 2
     *
     * @param array  $scope
     *
     * @param string $content
     *
     * @return
     */
    protected function _parseCall(array &$scope, string $content)
    {
        $contentReplaced = preg_replace_callback("!\<CMS\s*(.*?)\>(.*?)\<\/CMS\>!si",
                                                 [
                                                     $this,
                                                     'parseHTMLTag'
                                                 ],
                                                 $content);

        if ($contentReplaced === null) {
            throw new DetailedException('pregReplaceCallbackParseCall',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'content' => $content,
                                            ]
                                        ]);
        }
        $pattern = '/' . $this->delimiterStart . '([^' . $this->delimiterStartInner . $this->delimiterEndInner . ']+)' . $this->delimiterEnd . '/si';


        return preg_replace_callback($pattern,
                                     [
                                         $this,
                                         'callback'
                                     ],
                                     $contentReplaced);
    }

    protected function parseHTMLTag(array $matches): string
    {
        preg_match_all('@([\_a-zA-Z].*?)=\"(.*?)\"@si',
                       $matches[1],
                       $htmlTags,
                       PREG_SET_ORDER);

        $htmlTagsCollected = [];
        foreach ($htmlTags as $htmlTagsItem) {
            $htmlTagsCollected[$htmlTagsItem[1]] = $htmlTagsItem[2];
        }

        if (!isset($htmlTagsCollected['function'])) {
            throw new DetailedException('functionNotSet',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'function' => $htmlTagsCollected['function'],
                                                'list'     => $htmlTagsCollected,
                                            ]
                                        ]);
        }

        if (
            strpos($htmlTagsCollected['function'],
                   '_') === 0
        ) {
            $tagLoadFunction = Container::get('ContainerExtensionTemplateTag' . ucfirst(substr($htmlTagsCollected['function'],
                                                                                               1)));
            $tagLoadFunction::setFunction($this);
        }

//        d($htmlTagsCollected);
//        d($this->registeredFunctions[$htmlTagsCollected['function']]);
//        d($htmlTagsCollected['function']);
//        eol(true);

        if (isset($this->registeredFunctions[$htmlTagsCollected['function']])) {
            return call_user_func_array($this->registeredFunctions[$htmlTagsCollected['function']],
                                        [
                                            $matches[2],
                                            $htmlTagsCollected,
                                            $this,
                                        ]);
        }
        else {
            throw new DetailedException('functionNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'function' => $htmlTagsCollected['modul'],
                                            ]
                                        ]);
        }
    }

    public function get(): string
    {
        return $this->template;
    }

    public function getAssignObject(): ContainerExtensionTemplateInternalAssign
    {
        return $this->assignObject;
    }

    public function setAssignObject(ContainerExtensionTemplateInternalAssign $assign): void
    {
        if (get_class($assign) === 'ContainerExtensionTemplateInternalAssign') {
            $this->assignObject = $assign;
        }
        else {
            \CoreErrorhandler::trigger(__METHOD__,
                                       'templateAssignObjectError',
                                       ['class' => get_class($assign)],
                                       [],
                                       1);
        }
    }

    /**
     * Parse the Content
     *
     * @CMSprofilerSet          action parse
     * @CMSprofilerOption       isFunction true
     * @CMSprofilerOption       deph 8
     *
     * @return void
     */
    public function parse(): void
    {
        $this->template = $this->parseCall($this->template);
    }

    public function parseQuote(): void
    {
        $backupDelimiterStart = $this->delimiterStart;
        $backupDelimiterEnd   = $this->delimiterEnd;

        $this->setDelimiterStart('"' . $backupDelimiterStart);
        $this->setDelimiterEnd($backupDelimiterEnd . '"');
        $this->setDelimiterStartBorder('"');
        $this->setDelimiterEndBorder('"');

//        $this->template = $this->parseCall($this->template);
        $pattern = '/' . $this->delimiterStart . '([^' . $this->delimiterStartInner . $this->delimiterEndInner . ']+)' . $this->delimiterEnd . '/si';

        if (is_string($this->template)) {
            while (preg_match($pattern,
                              $this->template) > 0) {
                $this->template = $this->parseCall($this->template);
            }
        }

        $this->setDelimiterStart($backupDelimiterStart);
        $this->setDelimiterEnd($backupDelimiterEnd);

        $this->setDelimiterStartBorder('');
        $this->setDelimiterEndBorder('');
    }

    public function assign(string $key, $value): void
    {
        /** @var ContainerExtensionTemplateInternalAssign $assignObject */
        $assignObject = $this->assignObject;
        $assignObject->set($key,
                           $value);

        $this->assign[$key] = $value;
    }

    protected function callback(array $replace): string
    {
        $replace[2] = substr($replace[1],
                             1);
        $replace[3] = $replace[1][0];

        $scope['type'] = self::PARSE_TYPE_UNKNOWN;

        $assignVar = array_merge($this->assignObject->get(),
                                 $this->assign);

        if ($replace[3] === '$') {

            $scope['parse'] = '$';
            $scope['type']  = self::PARSE_TYPE_SIMPLE;

            //Standartarrayparsung
            if (isset($assignVar[$replace[2]]) === true) {
                return $this->delimiterStartBorder . $assignVar[$replace[2]] . $this->delimiterEndBorder;
            }

            /* TODO: Javascript / CSS nochmals Testen für _noparse - Eigener JS /CSS Tag ?*/

//            \CoreErrorhandler::trigger(__METHOD__,
//                                       'templateParseError',
//                                       [
//                                           'key' => $replace[2],
//                                       ],
//                                       [],
//                                       5);

            if ($this->onErrorShowAssignReplace === true) {
                return $this->delimiterStartBorder . $replace[0] . $this->delimiterEndBorder;
            }

            return $this->delimiterStart . $replace[1] . $this->delimiterEnd;
        }
        elseif ($replace[3] === '/') {
            $scope['parse'] = '/';
            $scope['type']  = self::PARSE_TYPE_SIMPLE;

            $workData = explode('/',
                                $replace[2],
                                2);

            if ($workData[0] === 'base64encode') {
                return $this->delimiterStartBorder . base64_encode($workData[1]) . $this->delimiterEndBorder;
            }
            elseif ($workData[0] === 'base64decode') {
                return $this->delimiterStartBorder . base64_decode($workData[1]) . $this->delimiterEndBorder;
            }
            elseif ($workData[0] === 'json') {
                return $this->delimiterStartBorder . base64_encode('{/base64decode/' . $workData[1] . '}') . $this->delimiterEndBorder;
            }


        }
        elseif ($replace[3] === '|') {
//            $scope['template'] = $this->covertTemplate($this->template,
//                                                       $replace[0]);
            $scope['parse'] = '|';
            $scope['type']  = self::PARSE_TYPE_SIMPLE;
//            $scope['assign'] = $this->assign;

            switch ($replace[2]) {
                case 'class':
                    return $this->delimiterStartBorder . $this->templateMetaClass . $this->delimiterEndBorder;
            }
        }
        elseif ($replace[3] === '#') {
//            $scope['template'] = $this->covertTemplate($this->template,
//                                                       $replace[0]);
            $scope['parse'] = '#';
            $scope['type']  = self::PARSE_TYPE_SIMPLE;
//            $scope['assign']   = $this->assign;

            $parameter = explode('|',
                                 $replace[2]);
            $class     = array_shift($parameter);
            $modul     = array_shift($parameter);
            return $this->delimiterStartBorder . Container::get('Widget',
                                                                $class,
                                                                $modul,
                                                                $parameter)
                                                          ->get() . $this->delimiterEndBorder;
        }
        elseif ($replace[3] === '~') {
//            $scope['template'] = $this->covertTemplate($this->template,
//                                                       $replace[0]);
            $scope['parse'] = '~';
            $scope['type']  = self::PARSE_TYPE_SIMPLE;
//            $scope['assign']   = $this->assign;

            if (isset(self::$replace[$replace[2]]) === true) {
                return $this->delimiterStartBorder . ((isset(self::$replaceMeta[$replace[2]]) ? strtr(self::$replace[$replace[2]],
                                                                                                      self::$replaceMeta[$replace[2]]) : self::$replace[$replace[2]])) . $this->delimiterEndBorder;
                //                return self::$templatesCache[$replace[2]];
            }
            //  \CoreErrorhandler::trigger(__METHOD__, 'templateKeyErrorTemplates', ['key' => $replace[2]], [], 4);
            if ($this->onErrorShowAssignReplace === true) {
                return $this->delimiterStartBorder . htmlspecialchars($this->delimiterStart . $replace[1] . $this->delimiterEnd);
            }
            else {
                \CoreErrorhandler::trigger(__METHOD__,
                                           'replaceNotFound',
                                           [
                                               'icon'    => $replace[1],
                                               'replace' => self::$replace,
                                           ]);
            }
        }
        else {
            $parseFind = explode(' ',
                                 $replace[1],
                                 2);

            if (!empty($parseFind[1])) {

                //   simpleDebugDump($parseFind);

                preg_match_all('@([\_a-zA-Z].*?)=\"(.*?)\"@i',
                               $parseFind[1],
                               $parseFindResult,
                               PREG_SET_ORDER);

                $parseFindParameter = [];

                foreach ($parseFindResult as $parseFindResultItem) {
                    $parseFindParameter[$parseFindResultItem[1]] = $parseFindResultItem[2];
                }

                $data = explode('/',
                                $parseFind[0],
                                2);


                if (!empty($data[0]) && !empty($data[1])) {

                    $scope['parse'] = $data[0] . '/' . $data[1];

                    $parseClassName = 'ContainerExtensionTemplateParse' . ucfirst($data[0]) . ucfirst($data[1]);
                    //  eol();

                    if (class_exists($parseClassName)) {
                        $scope['type'] = self::PARSE_TYPE_COMPLEX;
                        $parseClass    = Container::get('ContainerExtensionTemplateParse');

                        return $this->delimiterStartBorder . $parseClass->get($parseClassName,
                                                                              $replace[1],
                                                                              $parseFindParameter,
                                                                              $this) . $this->delimiterEndBorder;
                    }

                }
                else {
                    $scope['type'] = self::PARSE_TYPE_COMPLEX_ERROR;

                    /* TODO: Javascript / CSS nochmals Testen für _noparse - Eigener JS /CSS Tag ?*/ //
//                    \CoreErrorhandler::trigger(__METHOD__,
//                                               'templateParseErrorComplex',
//                                               [
//                                                   'key' => $replace[0],
//                                               ],
//                                               [],
//                                               1);
                }


                return $this->delimiterStartBorder . $replace[0] . $this->delimiterEndBorder;
            }
            else {

//                d($replace);
//                d($parseFind);
//                d($this->template);
//                eol(true);

                /* TODO: Javascript / CSS nochmals Testen für _noparse - Eigener JS /CSS Tag ?*/

//                \CoreErrorhandler::trigger(__METHOD__,
//                                           'templateParseError',
//                                           [
//                                               'key' => $replace[0],
//                                           ],
//                                           [],
//                                           1);

                return $this->delimiterStartBorder . $replace[0] . $this->delimiterEndBorder;
            }
        }

        \CoreErrorhandler::trigger(__METHOD__,
                                   'unknownCallback',
                                   [
                                       'content' => $replace[1]
                                   ],
                                   [],
                                   1);

        return $this->delimiterStart . $replace[1] . $this->delimiterEnd;
    }

    public function getMetaClass(): string
    {
        return $this->templateMetaClass;
    }

    public function catchData(): void
    {
        self::$dataCatchCache = [];

        $cacheDataData = preg_replace_callback('@\[data\=(.*?)\](.*?)\[\/data\]@si',
            function ($var) {
                self::$dataCatchCache[trim($var[1])] = trim($var[2]);
                return '';
            },
                                               $this->template);

        if ($cacheDataData === null) {
            throw new DetailedException('catchDataDataError',
                                        0,
                                        null,
                                        [
                                            'debug' => []
                                        ]);
        }

        $this->template  = $cacheDataData;
        $this->dataCatch = self::$dataCatchCache;
    }

    public function getCacheData(?string $key = null)
    {
        return (($key === null) ? $this->dataCatch : $this->dataCatch[$key]);
    }

    public function catchDataClear(): void
    {

        $cacheDataData = preg_replace_callback('@\[data\=(.*?)\](.*?)\[\/data\]@si',
            function ($var) {
                return '';
            },
                                               $this->template);

        if ($cacheDataData === null) {
            throw new DetailedException('catchDataClearError',
                                        0,
                                        null,
                                        [
                                            'debug' => []
                                        ]);
        }

        $this->template = $cacheDataData;
    }

    public function addParseFinal(string $value): string
    {
        $uniqueId = uniqid('page_',
                           true);

        $key = '<!-- templateTagParse # ' . $uniqueId . ' ->';

        self::$finalTemplateValues[$key] = $value;

        return $key;
    }

    public function parseFinal(): void
    {
        $this->template = strtr($this->template,
                                self::$finalTemplateValues);
    }
}
