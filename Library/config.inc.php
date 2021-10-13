<?php declare(strict_types=1);

// Melde alle PHP Fehler (siehe Changelog)
error_reporting(E_ALL);
// Dies entspricht error_reporting(E_ALL);
ini_set('error_reporting',
        (string)E_ALL);

$sTempRoot = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']);
if (
    strpos($sTempRoot,
           'public') !== false
) {
    $sTempRoot = dirname($sTempRoot);
}

define('CMS_ROOT',
       realpath(dirname($_SERVER['DOCUMENT_ROOT'])) . '/');

ini_set("log_errors",
        '1');
ini_set("error_log",
        CMS_ROOT . "/php-error.log");


include CMS_ROOT . 'Library/Thrirdparty/Kint/kint.phar';

define('CMS_SYSTEM_START_TIME',
       microtime(true));

define('CMS_SYSTEM_MEMORY_START',
       memory_get_usage());

const CMS_MKDIR_RIGHTS = 0777;

const CMS_DEBUG_ACTIVE = true;

ob_start();

$httpHost = ($_SERVER['HTTP_HOST'] ?? '');

define('CMS_ROOT_BACKSPACE',
       str_replace('/',
                   '\\',
                   CMS_ROOT));
define('CMS_PATH_STORAGE',
       CMS_ROOT . 'Storage' . DIRECTORY_SEPARATOR);
define('CMS_PATH_STORAGE_CACHE',
       CMS_PATH_STORAGE . 'Cache');
define('CMS_PATH_STORAGE_GENERATED',
       CMS_PATH_STORAGE . 'Generated');
define('CMS_PATH_STORAGE_DOWNLOAD',
       CMS_PATH_STORAGE . 'Download');
define('CMS_PATH_STORAGE_VENDOR',
       CMS_PATH_STORAGE . 'Vendor');

define('CMS_PATH_CUSTOM_REPOSITORY',
       CMS_ROOT . 'Custom' . DIRECTORY_SEPARATOR . 'Repository' . DIRECTORY_SEPARATOR);
define('CMS_PATH_CUSTOM_LOCAL',
       CMS_ROOT . 'Custom' . DIRECTORY_SEPARATOR . 'Local' . DIRECTORY_SEPARATOR);

define('CMS_PATH_LIBRARY',
       CMS_ROOT . 'Library' . DIRECTORY_SEPARATOR);
define('CMS_PATH_LIBRARY_CORE',
       CMS_PATH_LIBRARY . 'Core' . DIRECTORY_SEPARATOR);

define('CMS_PATH_LIBRARY_CONTAINER',
       CMS_PATH_LIBRARY . 'Container');
define('CMS_PATH_LIBRARY_CONTAINER_FACTORY',
       CMS_PATH_LIBRARY_CONTAINER . DIRECTORY_SEPARATOR . 'Factory' . DIRECTORY_SEPARATOR);
define('CMS_PATH_LIBRARY_CONTAINER_EXTENSION',
       CMS_PATH_LIBRARY_CONTAINER . DIRECTORY_SEPARATOR . 'Extension' . DIRECTORY_SEPARATOR);

try {

    require(CMS_PATH_LIBRARY . 'simple.inc.php');
    require(CMS_PATH_LIBRARY . 'Base/Base.class.php');
    require(CMS_PATH_LIBRARY . 'Base/Base.abstract.keyvalue.php');

    require(CMS_PATH_LIBRARY_CORE . 'Core.class.php');
    require(CMS_PATH_LIBRARY_CONTAINER . '/Container.class.php');
    require(CMS_PATH_LIBRARY_CONTAINER . '/Factory/Database/Database.class.php');
    require(CMS_PATH_LIBRARY_CONTAINER . '/Factory/Database/Engine/Engine.abstract.php');
    require(CMS_PATH_LIBRARY_CONTAINER . '/Factory/Database/Engine/Sqlite/Sqlite.class.php');
    require(CMS_PATH_LIBRARY_CONTAINER . '/Factory/Database/Engine/Sqlite/Table/Table.class.php');
    require(CMS_PATH_LIBRARY_CONTAINER . '/Factory/Database/Engine/Mysql/Mysql.class.php');
    require(CMS_PATH_LIBRARY_CONTAINER . '/Factory/Database/Query/Query.class.php');

    require(CMS_PATH_LIBRARY_CORE . '/Pdo/Pdo.class.php');

    $config = [];

    if (file_exists(CMS_ROOT . 'environment.ini')) {
        $config['environment'] = parse_ini_file(CMS_ROOT . 'environment.ini',
                                                true);
    }

    if (file_exists(CMS_ROOT . 'environment.json')) {
        $configJson        = json_decode(file_get_contents(CMS_ROOT . 'environment.json'),
                                         true);
        $configJsonDefault = $configJson['_default'];

        if (isset($configJson[$httpHost])) {
            $config['environment'] = array_replace_recursive($config['environment'],
                                                             $configJsonDefault,
                                                             $configJson[$httpHost]);
        }
        else {
            $config['environment'] = array_replace_recursive($config['environment'],
                                                             $configJsonDefault);
        }

    }

    $requestUri = $_SERVER['REQUEST_URI'];

    $config['server/path']            = ($_GET['url'] ?? '');
    $config['server/http']            = ((($_SERVER['SERVER_PORT'] ?? 0) == 443) ? 'https://' : 'http://');
    $config['server/root']            = realpath($sTempRoot) . DIRECTORY_SEPARATOR;
    $config['server/http/host']       = $httpHost;
    $config['server/domain']          = $httpHost;
    $config['server/http/host/clean'] = preg_replace('![^a-zA-Z0-9]+!si',
                                                     '',
                                                     $httpHost);
    $config['server/http/path']       = $requestUri;
    $config['server/http/base/url']   = $config['server/http'] . $config['server/http/host'];
    $config['server/http/hash']       = md5($config['server/http/base/url']);

    $sTempRoot = $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']);
    if (
        strpos($sTempRoot,
               'public') !== false
    ) {
        $sTempRoot = dirname($sTempRoot);
    }

    $urlQuery = '';
    if (
        strpos(($_SERVER['REQUEST_URI'] ?? ''),
            '?') !== false
    ) {
        $urlQuery          = '?' . explode('?',
                                           $_SERVER['REQUEST_URI'])[1];
        $config['cms/url'] = ((isset($_GET['url']) ? $_GET['url'] : null) . $urlQuery);
    }
    $config['cms/url'] = '';

    $config['cms/date']       = 'j.n.Y G:i:s';
    $config['cms/date/day']   = 'j.n.Y';
    $config['cms/date/time']  = 'G:i:s';
    $config['cms/date/dbase'] = 'Y-m-d H:i:s';
    $config['cms/date/html5'] = 'Y-m-d H:i:s';
    $config['cms/date/write'] = 'Y-m-d H:i:s';

    $config['cms/date/parsed/today']       = date($config['cms/date']);
    $config['cms/date/parsed/day']         = date($config['cms/date/day']);
    $config['cms/date/parsed/time']        = date($config['cms/date/time']);
    $config['cms/date/parsed/dbase']       = date($config['cms/date/dbase']);
    $config['cms/date/parsed/dbase/today'] = date($config['cms/date/dbase'],
                                                  mktime(0,
                                                         0,
                                                         0));

    $sTempRoot = $_SERVER['DOCUMENT_ROOT'];

    if (
        strpos($sTempRoot,
               'public') !== false
    ) {
        $sTempRoot = dirname($sTempRoot);
    }

    if ($sTempRoot === '') {
        $sTempRoot = dirname(dirname(__DIR__));
    }

    $config['cms/path/root'] = CMS_ROOT;

    $config['cms/path/library']           = $config['cms/path/root'] . 'Library' . DIRECTORY_SEPARATOR;
    $config['cms/path/library/container'] = $config['cms/path/library'] . DIRECTORY_SEPARATOR . 'Container';
    $config['cms/path/storage']           = $config['cms/path/root'] . DIRECTORY_SEPARATOR . 'Storage';
    $config['cms/path/storage/cache']     = $config['cms/path/storage'] . DIRECTORY_SEPARATOR . 'Cache';
    $config['cms/path/storage/temporary'] = $config['cms/path/storage'] . DIRECTORY_SEPARATOR . 'Temp';

    $config['cms/user/ip'] = ((isset($_SERVER['HTTP_X_FORWARDED_FOR']) === true) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : ((isset($_SERVER['REMOTE_ADDR']) === true) ? $_SERVER['REMOTE_ADDR'] : 0));

//d($config);
//eol();

    require(CMS_PATH_LIBRARY . 'Config/Config.class.php');
    Config::setCore($config);
    unset($config);

    define('DEBUG_LOG',
           Config::get('/environment/debug/active',
                       false));

    define('PAGE_REFRESH_DETECT',
        (isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'no-cache'));
    define('PAGE_REFRESH_DETECT_DEBUG',
        (PAGE_REFRESH_DETECT && Config::get('/environment/debug/active',
                                            false)));


    $accessPath = '/core/access/' . strtolower($_SERVER['PHP_AUTH_USER'] ?? '');
    $errorMsg   = 'ACCESS DENIED !';

    unset($errorMsg);

    Core::checkAndGenerateDirectoryByFilePath(CMS_PATH_STORAGE_CACHE . '/dummy.txt');

    /** @var ContainerFactoryDatabase $database */
    $database = Container::get('ContainerFactoryDatabase');
    $database->set('primary',
                   (string)Config::get('/environment/database/primary/type'),
                   (string)Config::get('/environment/database/primary/dsn'),
                   (string)Config::get('/environment/database/primary/user'),
                   (string)Config::get('/environment/database/primary/password'),
                   [
                       PDO::ATTR_PERSISTENT         => false,
                       PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                       PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;",
                   ]);
    $databaseSql = $database->get('primary');
    $databaseSql->query('SET SESSION group_concat_max_len=3423543543;');

    $database->set('cache',
                   (string)Config::get('/environment/database/cache/type'),
                   (string)Config::get('/environment/database/cache/dsn'),
                   '',
                   '',
                   [
                       PDO::ATTR_PERSISTENT         => false,
                       PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                       PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;",
                   ]);

    define('CMS_CACHE_ERORHANDLER_MESSAGE',
           'file');

    define('CMS_CACHE_CORE_CONFIG',
           'smart');
    define('CMS_CACHE_CORE_DATA',
           'smart');
    define('
CMS_CACHE_CORE_LINKREWRITE',
           'smart');
    define('CMS_CACHE_CORE_STATISTICS',
           'smart');
    define('CMS_CACHE_CORE_TEMPLATE',
           'smart');
    define('CMS_CACHE_CORE_LANGUAGE',
           'smart');
    define('CMS_CACHE_CORE_FILES',
           'smart');

    define('CMS_CACHE_PAGE',
           'smart');
    define('CMS_CACHE_PAGE_STYLE_DEFAULT_BASE_REPLACE',
           'smart');
    define('CMS_CACHE_PAGE_STYLE_DEFAULT_PAGE_CONTENT',
           'smart');
    define('CMS_CACHE_PAGE_STYLE_DEFAULT_BASE_FAVICON',
           'smart');
    define('CMS_CACHE_PAGE_STYLE_DEFAULT_BASE_BOXDATABASE',
           'smart');

    define('CMS_CACHE_CORE_MODUL_HELPER_GROUP',
           'smart');
    define('CMS_CACHE_CORE_MODUL_HELPER_USERONLINE',
           'smart');
    define('CMS_CACHE_CORE_MODUL_HELPER_WIDGET',
           'smart');

    define('CMS_CACHE_CORE_FUNCTION_SPRITE',
           'smart');

    define('CMS_CACHE_INDEX_ROUTE',
           'smart');

    define('CMS_CACHE_DIR_SMART',
           'APC|Memcache|Database|File');
    define('CMS_CACHE_DIR_MEMORY',
           'APC|Memcache');

    define('CMS_HOOK_RETURN_FAIL',
           '%%CMS_HOOK_RETURN_FAIL&&');

    $cmsDebugCacheDeactivate = true;

    if (defined('CMS_CACHE') === false) {
        define('CMS_CACHE',
               true);
    }
    if (defined('CMS_CACHE_CLASS') === false) {
        define('CMS_CACHE_CLASS',
               true);
    }
    if (defined('CMS_CACHE_EXT') === false) {
        define('CMS_CACHE_EXT',
               true);
    }

// --------------------------------------------------------------------------------------------------------------------------------------------------
// Initiation
// --------------------------------------------------------------------------------------------------------------------------------------------------

    require(CMS_PATH_LIBRARY . '/Event/Event.class.php');

    require(CMS_PATH_LIBRARY . '/exception.inc.php');

    require(CMS_PATH_LIBRARY . 'Core/Autoload/Autoload.class.php');
//\CoreAutoload::load('CoreErrorhandler');
    require(CMS_PATH_LIBRARY . '/Core/Debug/Debug.class.php');
    require(CMS_PATH_LIBRARY . '/Core/Debug/Dump/Dump.class.php');
    require(CMS_PATH_LIBRARY . '/Core/Debug/Profiler/Profiler.class.php');
    require(CMS_PATH_LIBRARY . '/Core/Debug/Profiler/Scope/Scope.class.php');
    require(CMS_PATH_LIBRARY . '/Container/Factory/Reflection/Reflection.class.php');

    register_shutdown_function('cmsShutdown');

    if (!function_exists('str_contains')) {
        function str_contains(string $haystack, string $needle): bool
        {
            return (strpos($haystack,
                           $needle) !== false);
        }
    }

//    ContainerFactorySession::setSessionHandler('Sqlite');
    ContainerFactorySession::setSessionHandler(null);

    ContainerFactorySession::start();

    setlocale(LC_ALL,
              (string)Config::get('/environment/language'));
    date_default_timezone_set((string)Config::get('/environment/datetime/timezone'));

    CoreDebugLog::addLog('Page Refresh Detect',
                         (int)PAGE_REFRESH_DETECT);

    CoreDebugLog::addLog('Config.inc',
                         'Locale => ' . Config::get('/environment/language'));

} catch (Throwable $exception) {
    d($exception);
    eol(true);
}

