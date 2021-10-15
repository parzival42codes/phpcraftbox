<?php

use JetBrains\PhpStorm\NoReturn;

/**
 * Class ContainerFactoryRouter
 *
 */
class ContainerFactoryRouter extends Base
{
    public static array $routerCache = [];

    public static array $routerCacheIndex = [];

    /**
     * @var string
     */
    protected string $urlPure = '';
    /**
     * @var string
     */
    protected string $urlReadable = '';
    /**
     * @var string
     */
    protected string $route = 'default';
    /**
     * @var string
     */
    protected string $application = '';
    /**
     * @var array
     */
    protected array $parameter = [];

    /**
     * @var string
     */
    protected string $path = '';

    /**
     * @var array
     */
    protected $typeSimple = [];

    /**
     * @var array
     */
    protected $typeRegex = [];

    /**
     * @var string
     */
    protected string $target = '';

    /**
     * @var array
     */
    protected array $query = [];

    /**
     * @var string
     */
    protected string $anchor = '';

    /**
     * @var array
     */
    protected static array $regexCache = [];
    /**
     * @var array
     */
    protected static array $parameterIndex = [];

    public function __construct(string $url = '')
    {
        if (empty(self::$routerCache)) {

            $query = new ContainerFactoryDatabaseQuery(__METHOD__ . '#select',
                                                       true,
                                                       ContainerFactoryDatabaseQuery::MODE_SELECT);

            $query->setTable('index_router');
            $query->select('crudClass',
                           'crudType',
                           'crudPath',
                           'crudRoute',
                           'crudTarget');

            $query->construct();
            $smtp = $query->execute();


            while ($smtpData = $smtp->fetch()) {
                self::$routerCacheIndex[$smtpData['crudClass']][$smtpData['crudRoute']] = $smtpData;

                if ($smtpData['crudType'] === 'simple') {
                    self::$routerCache['simple'][$smtpData['crudPath']]                               = $smtpData;
                    self::$routerCacheIndex['simple'][$smtpData['crudClass']][$smtpData['crudRoute']] = $smtpData;
                }
                else {
                    if ($smtpData['crudType'] === 'regex') {
                        self::$routerCache['regex'][$smtpData['crudPath']] = $smtpData;
                    }
                }
            }
        }

        $this->typeSimple = self::$routerCache['simple'];
        $this->typeRegex  = self::$routerCache['regex'];

        $this->analyzeUrl($url);
    }

    public static function get(string $url): void
    {
        $router = new self();
        $router->analyzeUrl($url);
    }

    public function analyzeUrl(string $url): void
    {
        $url = strtr($url,
                     [
                         Config::get('/server/http/base/url') => ''
                     ]);

        if (
            strpos($url,
                   '#') !== false
        ) {
            $linkAnchor   = explode('#',
                                    $url,
                                    2);
            $this->anchor = $linkAnchor[1];
            $url          = $linkAnchor[0];
        }

        debugDump($url);

        debugDump(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

        if (
            strpos($url,
                   'index.php') !== false
        ) {
            $this->urlPure = $url;
            $this->analyzeUrlPure();
        }
        else {
            $this->urlReadable = $url;
            $this->analyzeUrlReadable();
        }

    }

    protected function analyzeUrlPure(): void
    {
        $linkData = strtr($this->urlPure,
                          [
                              'index.php?' => '',
                              '&amp;'      => '&',
                          ]);

        $linkDataToParse = explode('&',
                                   $linkData);

        $this->setRoute('default');
        foreach ($linkDataToParse as $linkDataToParseItem) {
            $parameterData = explode('=',
                                     $linkDataToParseItem);

            switch ($parameterData[0]) {
                case 'application':
                    $application       = $parameterData[1];
                    $applicationLength = strlen($application);
                    if (
                        strpos($parameterData[1],
                               '/') === ($applicationLength - 1)
                    ) {
                        $application = substr($application,
                                              0,
                                              -1);
                    }
                    $this->setApplication($application);
                    break;
                case 'route':
                    $this->setRoute($parameterData[1]);
                    break;
                case 'target':
                    $this->setTarget($parameterData[1]);
                    break;
                default:
                    $this->setParameter($parameterData[0],
                        ($parameterData[1] ?? ''));
                    break;
            }

        }

        $templateCache        = Container::get('ContainerFactoryRouter_cache_route');
        $templateCacheContent = $templateCache->get();

        if (isset($templateCacheContent[$this->getApplication()]['simple'][$this->getRoute()])) {
            $this->path  = $templateCacheContent[$this->getApplication()]['simple'][$this->getRoute()];
            $this->query = $this->parameter;
        }
        else {
            $this->path = ($templateCacheContent[$this->getApplication()]['regex'][$this->getRoute()] ?? '');

            $this->analyzePath($this->path);

            $parsedPath = preg_replace_callback('@\{(.*?)\}@i',
                function ($var) {

                    $varReplaceData = explode('|',
                                              $var[1]);

                    $this->analyzeParameter($varReplaceData[0]);
                    return $this->getParameter($varReplaceData[0]);
                },
                                                $this->path);

            foreach ($this->parameter as $parameterKey => $parameter) {
                if (!isset(self::$parameterIndex[$this->path][$parameterKey])) {
                    $this->setQuery($parameterKey,
                                    $parameter);
                }
            }

            $this->path = $parsedPath;
        }
    }

    protected function analyzeUrlReadable(): void
    {
        $urlReadableData = explode('?',
                                   $this->urlReadable,
                                   2);
        $urlQuery        = ($urlReadableData[1] ?? '');
        $urlReadable     = $urlReadableData[0];

        $urlQueryFind = explode('&',
                                $urlQuery);

        if (!empty($urlQueryFind) && !empty($urlQueryFind[0])) {
            foreach ($urlQueryFind as $urlQueryFindItem) {
                $urlQueryFindItemData = explode('=',
                                                $urlQueryFindItem);
                $this->setQuery($urlQueryFindItemData[0],
                    ($urlQueryFindItemData[1] ?? ''));
            }
        }

        if (isset($this->typeSimple[$urlReadable])) {
            $this->application = ($this->typeSimple[$urlReadable]['crudClass'] ?? '');
            $this->target      = ($this->typeSimple[$urlReadable]['crudTarget'] ?? '');
            $this->route       = ($this->typeSimple[$urlReadable]['crudRoute'] ?? '');
            $this->path        = ($this->typeSimple[$urlReadable]['crudPath'] ?? '');
            return;
        }

        $routeIndex   = [];
        $routeCounter = 0;
        $regexFind    = [];
        foreach ($this->typeRegex as $regex) {
            $routeCounter++;
            $routeIndex[$routeCounter] = $regex;
            $regexFind[]               = $regex['crudPath'] . '(*:' . $routeCounter . ')';
        }

        $regexFindContent = implode('|',
                                    $regexFind);

        $regexFindContent = '!^(?|' . preg_replace_callback("!\{(.*?)\}!si",
                function ($value) {
                    $valueReturn = explode('|',
                                           $value[1]);
                    return '(' . ($valueReturn[1] ?? '.*?') . ')';
                },
                                                            $regexFindContent) . ')$!si';
        preg_match($regexFindContent,
                   $urlReadable,
                   $findData);

        $routeFound = null;
        if (isset($findData['MARK']) && isset($routeIndex[$findData['MARK']])) {
            $routeFound = $routeIndex[$findData['MARK']];
        }

        if (!empty($routeFound['crudPath'])) {
            preg_match_all('@\{(.*?)\}@i',
                           $routeFound['crudPath'],
                           $matchesPathParameter,
                           PREG_SET_ORDER);
        }
        else {
            $matchesPathParameter = [];
        }

        foreach ($matchesPathParameter as $matchesPathParameterCount => $matchesPathParameterValue) {
            $matchesPathParameterValueData = explode('|',
                                                     $matchesPathParameterValue[1]);

            $this->setParameter($matchesPathParameterValueData[0],
                                $findData[$matchesPathParameterCount + 1]);
        }

        $this->application = ($routeFound['crudClass'] ?? '');
        $this->target      = ($routeFound['crudTarget'] ?? '');
        $this->route       = ($routeFound['crudRoute'] ?? '');
        $this->path        = ($routeFound['crudPath'] ?? '');

        debugDump($this->application);
        debugDump($this->target);
        debugDump($this->route);
        debugDump($this->path);
        debugDump($this->query);

        return;
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     */
    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getApplication(): string
    {
        return $this->application;
    }

    /**
     * @param string $application
     */
    public function setApplication(string $application): void
    {
        $this->application = $application;
    }

    /**
     * @return string
     */
    public function getRoute(): string
    {
        return ($this->route ?? 'default');
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @param     $key
     * @param int $filter
     *
     * @return mixed
     */
    public function getParameter($key = null, int $filter = FILTER_SANITIZE_STRING)
    {
        if ($key === null) {
            return $this->parameter;
        }

        if (!isset($this->parameter[$key])) {
            return null;
        }

        return filter_var($this->parameter[$key],
                          $filter);
    }

    /**
     * @param string $key
     * @param        $parameter
     */
    public function setParameter(string $key, $parameter): void
    {
        $this->parameter[$key] = $parameter;
    }

    protected function analyzePath(string $path): void
    {
        if (empty(self::$regexCache[$path])) {
            preg_match_all('@\{\$(.*?)\}@i',
                           $path,
                           $matches);

            foreach ($matches[1] as $match) {
                $matchData = explode('|',
                                     $match,
                                     2);
            }
        }

    }

    public function analyzeParameter(string $key): void
    {
        $this->analyzePath($this->path);

        $parameter = $this->getParameter($key);

        self::$parameterIndex[$this->path][$key] = true;

        if ($parameter === null) {
            throw new DetailedException('parameterNotExists',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'readable' => $this->urlReadable,
                                                'pure'     => $this->urlPure,
                                                'key'      => $key,
                                                'path'     => $this->path,
                                            ]
                                        ]);
        }

        if (
            isset(self::$regexCache[$this->path][$key]) && preg_match('@' . self::$regexCache[$this->path][$key] . '@i',
                                                                      $parameter) === 0
        ) {

            throw new DetailedException('parameterRegexFail',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'key'       => $key,
                                                'parameter' => $parameter,
                                                'regex'     => self::$regexCache[$this->path][$key],
                                            ]
                                        ]);

        }
    }

    /**
     * @param string $key
     *
     * @return
     */
    public function getQuery(string $key): ?string
    {
        return $this->query[$key] ?? null;
    }

    /**
     * @param string $key
     * @param        $value
     */
    public function setQuery(string $key, $value): void
    {
        $this->query[$key] = $value;
    }

    public function clearQuery(): void
    {
        $this->query = [];
    }

    /**
     * @return string
     */
    public function getUrlReadable(bool $raw = false): string
    {
        if (isset(self::$routerCacheIndex[$this->application][$this->route])) {

            if (self::$routerCacheIndex[$this->application][$this->route]['crudType'] === 'simple') {
                $this->urlReadable = self::$routerCacheIndex[$this->application][$this->route]['crudPath'];
            }
            else {
                $path = self::$routerCacheIndex[$this->application][$this->route]['crudPath'];

                $this->urlReadable = preg_replace_callback('@\{(.*?)\}@i',
                    function ($var) {

                        $varReplaceData = explode('|',
                                                  $var[1]);

                        $this->analyzeParameter($varReplaceData[0]);
                        $parameter = $this->getParameter($varReplaceData[0]);;
//                        unset($this->parameter[$varReplaceData[0]]);

                        return $parameter;
                    },
                                                           $path);
            }

            if (!empty($this->query)) {
                $query = [];
                foreach ($this->query as $queryKey => $queryItem) {
                    if ($queryItem !== null) {
                        $query[] = $queryKey . '=' . $queryItem;
                    }
                }
                $this->urlReadable .= '?' . implode('&',
                                                    $query);
            }
        }

        if ($raw === false) {
            return \Config::get('/server/http/base/url') . $this->urlReadable;
        }
        else {
            return $this->urlReadable;
        }
    }

    /**
     * @param string $urlReadable
     */
    public function setUrlReadable(string $urlReadable): void
    {
        $this->urlReadable = $urlReadable;
    }

    /**
     * @return string
     */
    public function getUrlPure(bool $raw = false): string
    {

        $this->urlPure = 'index.php?';

        $parameter = [];
        foreach ($this->parameter as $parameterKey => $parameterItem) {
            $parameter[] = $parameterKey . '=' . ($parameterItem ?? '');
        }

        $queryWorker = [];
        foreach ($this->query as $queryKey => $queryItem) {
            if ($queryItem === null || $queryItem === false) {
                continue;
            }
            $queryWorker[] = $queryKey . '=' . $queryItem;
        }

        $urlParameter = array_merge($parameter,
                                    $queryWorker);

        $this->urlPure .= implode('&',
                                  $urlParameter);

        if ($raw === false) {
            return \Config::get('/server/http/base/url') . $this->urlPure;
        }
        else {
            return $this->urlReadable;
        }
    }

    /**
     * @param string $urlPure
     */
    public function setUrlPure(string $urlPure): void
    {
        $this->urlPure = $urlPure;
    }

    #[NoReturn]
    protected function createUrlPureParameter(array $merge = []): string
    {
        $parameter = [];
        foreach ($this->parameter as $parameterKey => $parameterItem) {
            $parameter[] = $parameterKey . '=' . ($parameterItem ?? '');
        }

        $queryWorker = [];
        foreach ($this->query as $queryKey => $queryItem) {
            if ($queryItem === null || $queryItem === false) {
                continue;
            }
            $queryWorker[] = $queryKey . '=' . $queryItem;
        }

        $urlParameter = array_merge($parameter,
                                    $queryWorker,
                                    $merge);

        return implode('&',
                       $urlParameter);
    }

    public function redirect(): void
    {
        header('Location: ' . $this->getUrlReadable());
        exit();
    }

}
