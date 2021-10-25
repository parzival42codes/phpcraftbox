<?php

function ErrorhandlerGetReducedFilename(string $filename): string
{
    $filename = str_replace('/',
                            '\\',
                            $filename);
    $filename = str_replace('\\\\',
                            '\\',
                            $filename);
    $filename = str_replace(CMS_ROOT,
                            '',
                            $filename);
    return str_replace(CMS_ROOT_BACKSPACE,
                       '',
                       $filename);
//$Output = str_replace(CMS_ROOT, '', realpath($filename));
//return (($Output !== '') ? $Output : $filename);
//return str_replace($_SERVER['DOCUMENT_ROOT'], '', (strpos($filename, '\\') === false) ? $filename : str_replace('\\', '/', $filename));
}

//class MessageException extends Exception {
//    static function doException () {
//        return new static ();
//    }
//}

class CoreErrorhandler
{

    static private array $errorCounter      = [];
    static private int   $errorCounterCount = 0;

    static public function trigger(string $method, string $key, array $data = [], array $details = [], int $deep = 0, ?int $level = null): void
    {
        if ($level === null) {
            $level = E_USER_NOTICE;
        }

//        d($method . ' :: ' . $key . ' :: ' . var_export($data,
//                                                        true));

        //DEBUG_BACKTRACE_IGNORE_ARGS
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        $backtraceFile = ((isset($backtrace[$deep]['file']) === true) ? $backtrace[$deep]['file'] : '?');
        $backtraceLine = ((isset($backtrace[$deep]['line']) === true) ? $backtrace[$deep]['line'] : '?');

        CoreDebug::setRawDebugData(__CLASS__,
                                   [
                                       'level'     => $level,
                                       'message'   => $method . ' :: ' . $key . ' :: ' . var_export($data,
                                                                                                    true),
                                       'file'      => $backtraceFile,
                                       'line'      => $backtraceLine,
                                       'backtrace' => $backtrace,

                                   ]);

        /** @var ContainerFactoryLogError_crud $log */
        $log = Container::get('ContainerFactoryLogError_crud');
        $log->setCrudTitle($method . ' :: ' . $key);
        $log->setCrudContent(var_export($data,
                                        true));
        $log->setCrudPath('/trigger/' . $backtraceFile . '/' . $backtraceLine);
        $log->setCrudType(ContainerFactoryLogError_crud::LOG_TYPE_TRIGGER);
        $log->setCrudBacktrace(serialize($backtrace));
        $log->insert();

    }

//    static public function triggerException($method, $key, $class, $parameter, $e) {
//        //DEBUG_BACKTRACE_IGNORE_ARGS
//        $backtrace = $e->getTrace();
//
//        debugDump($method);
//        debugDump($key);
//
//        $data = [
//            'message' => $e->getMessage(),
//        ];
//
//        $details = [
//            'parameter'          => '<pre>' . var_export($parameter, true) . '</pre>',
//            'parameterException' => '<pre>' . var_export($e->getParameter(), true) . '</pre>',
//        ];
//
//        \Container::callStatic('CoreDebug', 'setRawDebugData', __CLASS__, [
//            'level'     => 0,
//            'message'   => ['method' => $method, 'key' => $key, 'data' => $data],
//            'details'   => $details,
//            'file'      => $e->getFile(),
//            'line'      => $e->getLine(),
//            'backtrace' => $backtrace
//        ]);
//    }

    static public function captureNormal(int $level, string $message, string $file_handler, string $line_handler): void
    {
        ++self::$errorCounterCount;

        if (isset(self::$errorCounter[$level]) === true) {
            ++self::$errorCounter[$level];
        }
        else {
            self::$errorCounter[$level] = 1;
        }

        if (
            self::$errorCounterCount < \Config::get('/debug/errorhandler/capture/normal/max',
                                                    100)
        ) {
            $Backtrace = ((\Config::get('/debug/errorhandler/capture/normal/args',
                                        CMS_DEBUG_ACTIVE) ?? false) ? debug_backtrace() : debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));

            /** @var ContainerFactoryLogError_crud $log */
            $log = Container::get('ContainerFactoryLogError_crud');
            $log->setCrudTitle($message);
            $log->setCrudContent('');
            $log->setCrudPath('/normal/' . $level . '/' . Core::getReducedFilename($file_handler) . '/' . $line_handler);
            $log->setCrudType(ContainerFactoryLogError_crud::LOG_TYPE_WARNING);
            $log->setCrudBacktrace(serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)));
            $log->insert();

            if (class_exists('Container') && class_exists('CoreDebug')) {
                CoreDebug::setRawDebugData(__CLASS__,
                                           [
                                               'level'     => $level,
                                               'message'   => $message,
                                               'file'      => $file_handler,
                                               'line'      => $line_handler,
                                               'backtrace' => $Backtrace,
                                               '_'         => [
                                                   'backtraceFile'  => '',
                                                   'backtraceLine'  => '',
                                                   'microtimeStart' => 0,
                                                   'memoryStart'    => 0,
                                                   'microtimeEnd'   => 0,
                                                   'memoryEnd'      => 0,
                                               ]
                                           ]);
            }

            if (
                Config::get('/environment/debug/active',
                            0) == 1 && Config::get('/environment/debug/print/error',
                                                   0) == 1
            ) {

                echo '
         ErrorLevel: ' . $level . '<br />
         ErrorMessage: ' . $message . '<br />
         ErrorFile: ' . $file_handler . '<br />
         ErrorLine: ' . $line_handler . '<br />

         <pre>' . var_export($Backtrace,
                             true) . '</pre><br />
 <hr />9
   ';
            }


            if (defined('CMS_CACHE_ERROR') === true && CMS_CACHE_ERROR === true) {

                $errorText = '
     Referrer: ' . ((isset($_SERVER['HTTP_REFERER']) === true) ? htmlentities(strip_tags($_SERVER['HTTP_REFERER']),
                                                                              ENT_QUOTES) : '-') . '
     UserAgent: ' . ((isset($_SERVER['HTTP_USER_AGENT']) === true) ? htmlentities(strip_tags($_SERVER['HTTP_USER_AGENT']),
                                                                                  ENT_QUOTES) : '-') . '
     Scriptname: ' . ((isset($_SERVER['SCRIPT_FILENAME']) === true) ? htmlentities(strip_tags($_SERVER['SCRIPT_FILENAME']),
                                                                                   ENT_QUOTES) : '-') . '
     URL: ' . ((isset($_SERVER['REQUEST_URI']) === true) ? htmlentities(strip_tags($_SERVER['REQUEST_URI']),
                                                                        ENT_QUOTES) : '-') . '
     IP: ' . ((isset($_SERVER['REMOTE_ADDR']) === true) ? htmlentities(strip_tags($_SERVER['REMOTE_ADDR']),
                                                                       ENT_QUOTES) : '-') . '

     ' . $level . '
     ' . $message . '
     ' . $file_handler . '
     ' . $line_handler . '
     ' . var_export($Backtrace,
                    true);

//            , array(
//                'noDB' => true
//            )

                if (is_dir(CMS_PATH_CACHE . DIRECTORY_SEPARATOR . 'Error') === false) {
                    mkdir(CMS_PATH_CACHE . DIRECTORY_SEPARATOR . 'Error',
                          0777);
                }

                file_put_contents(CMS_PATH_CACHE . DIRECTORY_SEPARATOR . 'Error' . DIRECTORY_SEPARATOR . strtr(CMS_DATA_DATE_PARSE . '_' . microtime(),
                                                                                                               array(
                                                                                                                   ' ' => '_',
                                                                                                                   ':' => '_',
                                                                                                                   '.' => '_'
                                                                                                               )) . '.txt',
                                  $errorText);

//\SysCore::CacheWrapper(strtr(CMS_DATA_DATE_PARSE . '_' . microtime(), array(' ' => '_', ':' => '_', '.' => '_')) . '.txt', 'error', $errorText);
            }
        }
    }

    public static function captureException(Throwable $exception): string
    {
        if (defined('CMS_ERRORHANDLER_EXCEPTION_LOOP_DETECTION')) {
            echo 'Warning: Errorhandler Execption Loop detected !';
            d($exception->getParameter());
            d($exception->getTrace());
            d($exception);
            d(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS));
            die();
        }
        define('CMS_ERRORHANDLER_EXCEPTION_LOOP_DETECTION',
               true);

        $errorFile = $exception->getFile();
        $errorLine = $exception->getLine();

        try {
            /** @var ContainerFactoryLogError_crud $log */
            $log = Container::get('ContainerFactoryLogError_crud');
            $log->setCrudTitle($exception->getMessage());
            $log->setCrudContent(serialize($exception));
            $log->setCrudPath('/exception/' . Core::getReducedFilename($errorFile) . '/' . $errorLine);
            $log->setCrudType(ContainerFactoryLogError_crud::LOG_TYPE_EXCEPTION);
            $log->setCrudBacktrace(serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)));
            $log->insert();
        } catch (Throwable $exceptionLog) {
            /** @var ContainerFactoryLogError_crud $log */
            $log = Container::get('ContainerFactoryLogError_crud');
            $log->setCrudTitle($exception->getMessage());
            $log->setCrudContent('');
            $log->setCrudPath('/exception/' . Core::getReducedFilename($errorFile) . '/' . $errorLine);
            $log->setCrudType(ContainerFactoryLogError_crud::LOG_TYPE_EXCEPTION);
            $log->setCrudBacktrace(serialize(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)));
            $log->insert();
        }

        return self::doExceptionView($exception);
    }

    public static function doExceptionView(Throwable $exception): string
    {
        $trace     = $exception->getTrace();
        $class     = get_class($exception);
        $errorFile = $exception->getFile();
        $errorLine = $exception->getLine();

        $parameter = [];
        if ($class === 'DetailedException') {
            $parameter      = $exception->getParameter();
            $exceptionClass = $exception->getClass();
        }
        else {
            $exceptionClass = get_class($exception);
        }


        $fileName           = Config::get('/cms/path/library') . 'Core/Errorhandler/Errorhandler.template.exception.message.tpl';
        $fileContentMessage = file_get_contents($fileName);

        $outputTranslation = self::getExceptionMessages($exception);

        $output = strtr($fileContentMessage,
                        [
                            '{$title}'   => $outputTranslation['title'],
                            '{$content}' => $outputTranslation['message'],
                        ]);

        if (
            \Config::get('/environment/debug/active',
                         CMS_DEBUG_ACTIVE) === true
        ) {
            $fileName           = Config::get('/cms/path/library') . 'Core/Errorhandler/Errorhandler.template.exception.debug.tpl';
            $fileContentMessage = file_get_contents($fileName);

            if (
            class_exists('ContainerHelperView')
            ) {
                $outputBacktrace = \ContainerHelperView::convertBacktraceView($exception->getTrace());
            }
            else {
                $outputBacktrace = var_export($exception->getTrace(),
                                              true);
            }

            $output .= strtr($fileContentMessage,
                             [
                                 '{$lineAndRow}'        => $errorFile . ' # ' . $errorLine,
                                 '{$debug}'             => $outputTranslation['debug'],
                                 '{$originalMessage}'   => $exception->getMessage(),
                                 '{$getClassException}' => get_class($exception),
                                 '{$exceptionClass}'    => $exceptionClass,
                                 '{$outputBacktrace}'   => $outputBacktrace,
                                 '{$dump}'              => var_export(($parameter['debug'] ?? []),
                                     true),

                             ]);
        }

        return $output;
    }

    public static function getExceptionMessages(Throwable $exception): array
    {
        $trace     = $exception->getTrace();
        $class     = get_class($exception);
        $errorFile = $exception->getFile();
        $errorLine = $exception->getLine();

        $outputTranslation = [
            'title'   => '',
            'message' => '',
            'debug'   => '',
        ];

        $parameter = [
            'title'   => '',
            'message' => '',
            'debug'   => '',
        ];

        $parameter = [];
        if ($class === 'DetailedException') {
            $parameter      = $exception->getParameter();
            $exceptionClass = $exception->getClass();
        }
        else {
            $exceptionClass = get_class($exception);
        }

        return \CoreErrorhandler::getErrorTranslatedException($exceptionClass,
                                                              $exception->getMessage(),
                                                              [
                                                                  'title'   => ($parameter['title'] ?? []),
                                                                  'message' => ($parameter['message'] ?? []),
                                                                  'debug'   => ($parameter['debug'] ?? []),
                                                              ]);

    }


    public static function getErrorTranslatedTrigger(string $class, string $method, string $key, array $data = []): string
    {
        $need      = 'Need: /trigger/' . $method . '/' . $key . '<pre>' . htmlspecialchars(var_export($data,
                                                                                                      true)) . '</pre>';
        $errorPath = '/trigger/' . $method . '/' . $key;
        return \ContainerFactoryLanguage::get('/' . $class . $errorPath,
                                              $need);
    }

    public static function getErrorTranslatedException(string $class, string $exception, array $data = []): array
    {

        if (
            class_exists('ContainerFactoryDatabase',
                         false) && class_exists('ContainerFactoryDatabaseQuery',
                                                false) && class_exists('ContainerFactoryLanguage',
                                                                       false)
        ) {

            $errorExceptionStdTitle   = \ContainerFactoryLanguage::get('/CoreErrorhandler/exception/template/title',
                                                                       '');
            $errorExceptionStdMessage = \ContainerFactoryLanguage::get('/CoreErrorhandler/exception/template/message',
                                                                       '');

            $errorPath = '/' . Core::getRootClass($class) . '/exception/' . $exception;
            $need      = 'Need: ' . $errorPath;

            $languageArrayTitle   = array_values($data['title'] ?? []);
            $languageArrayMessage = array_values($data['message'] ?? []);
            $languageArrayDebug   = array_values($data['debug'] ?? []);

            return [
                'title'   => sprintf(\ContainerFactoryLanguage::get($errorPath . '/title',
                                                                    $errorExceptionStdTitle),

                    ...
                                     $languageArrayTitle),
                'message' => sprintf(\ContainerFactoryLanguage::get($errorPath,
                                                                    $errorExceptionStdMessage),
                    ...
                                     $languageArrayMessage),
                'debug'   => sprintf(\ContainerFactoryLanguage::get($errorPath . '/debug',
                                                                    $need),
                    ...
                                     $languageArrayDebug),
            ];

        }
        else {
            return [
                'title'   => '',
                'message' => '',
                'debug'   => '',
            ];
        }

    }


}

set_error_handler(array(
                      'CoreErrorhandler',
                      'captureNormal'
                  ));
set_exception_handler(array(
                          'CoreErrorhandler',
                          'captureException'
                      ));

function cmsShutdownError(): void
{
    if ($error = error_get_last()) {
        if (isset($error['type']) && ($error['type'] == E_ERROR || $error['type'] == E_PARSE || $error['type'] == E_COMPILE_ERROR)) {
            $contentSend = ob_get_contents();
            ob_clean();

            $contentSendError = strpos($contentSend,
                                       'Fatal error');
            if ($contentSendError !== false) {
                $contentSend = substr($contentSend,
                                      0,
                                      $contentSendError);


                if (
                    class_exists('SysCore',
                                 false) === true
                ) {
                    $fileSmall = ErrorhandlerGetReducedFilename($error['file']);
                }
                else {
                    $fileSmall = $error['file'];
                }

                if (!headers_sent()) {
                    header('content-type: text/html; charset=utf-8');
                    //  header('HTTP/1.1 500 Internal Server Error');
                }

                $Output = '<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<title>PHP Error @ File: ' . $fileSmall . ' - Line: ' . $error['line'] . '</title>
		<style type="text/css">';

//                simpleDebugLog(Core::getPHPClassFileName('ContainerExtensionStyle_error_css'));

                if (
                    class_exists('ContainerFactoryFile',
                                 false) === true
                ) {
                    simpleDebugDump($error);
                    die();
                    eol();
                    $Output .= file_get_contents(Core::getClassFileName('ContainerExtensionStyle_error_css'));
                }

                $Output .= '</style>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        </head><body>
       <div id="ErrorException">
       <h1>PHP Error bar</h1>';

                if (
                    \Config::get('/environment/debug/active',
                                 CMS_DEBUG_ACTIVE) === false
                ) {
                    $Output .= '<p>:(</p>';
                    eol();
                }
                else {

//                switch ($error['type']) {
//                    case E_ERROR:
//                        $error['type'] = 'E_ERROR';
//                        break;
//                }

                    if (
                        strpos($error['message'],
                               'Uncaught Exception:') !== false
                    ) {
                        preg_match('#Uncaught exception: (.*?) in #si',
                                   $error['message'],
                                   $exceptionTest);
                    }
                    elseif (
                        strpos($error['message'],
                               'Uncaught exception \'Exception\' with message:') !== false
                    ) {
                        preg_match('#Uncaught exception \'Exception\' with message \'(.*?)\' in #si',
                                   $error['message'],
                                   $exceptionTest);
                    }

                    if (isset($exceptionTest[1]) === true) {
                        $exceptionTest    = explode('|',
                                                    $exceptionTest[1]);
                        $error['message'] .= '<hr />1' . stripcslashes(var_export(unserialize(base64_decode($exceptionTest[1])),
                                                                                  true));
                    }


                    $Output .= '<table>
         <tr><th style="text-align: left;">Typ:</th><td><strong>' . $error['type'] . '</strong></td></tr>
         <tr><th style="text-align: left;">Message:</th><td><pre>' . $error['message'] . '</pre></td></tr>
         <tr><th style="text-align: left;">File:</th><td><strong>' . $error['file'] . '</strong></td></tr>
         <tr><th style="text-align: left;">Line:</th><td><strong>' . $error['line'] . '</strong></td></tr>
         </table>
		 ';


                    $Output .= '<table>
			<tr><th style="text-align: left;width:5%;">Typ:</th><th style="text-align: left;width:50%;">Message:</th><th style="text-align: left;width:10%;">File:</th><th style="text-align: left;width:5%;">Line:</th><th style="text-align: left;width:30%;">Trace:</th></tr>';

                    if (class_exists('Container') === true && class_exists('CoreDebug') === true) {
                        $errorData = CoreDebug::getRawDebugData(__CLASS__);

                        if (is_array($errorData) === true) {
                            foreach ($errorData as $key => $elem) {

                                foreach ($elem as $elem2) {
                                    if (
                                        class_exists('ContainerHelperView',
                                                     false) === true
                                    ) {
                                        $backtrace = \ContainerHelperView::convertBacktraceView($elem2['backtrace']);
                                    }
                                    else {
                                        $backtrace = '';
                                    }

                                    $Output .= '<tr><td>' . $key . '</td><td><pre>' . $elem2['message'] . '</pre></td><td>' . $elem2['file'] . '</td><td>' . $elem2['line'] . '</td><td>' . $backtrace . '</td></tr>';
                                }
                            }
                        }
                    }
                    else {
                        print '<pre>' . var_export($error,
                                                   true) . '</pre>';
                    }


                    if (
                        class_exists('CoreDebug',
                                     false) === true
                    ) {
                        $Output .= '<tr><td colspan="5"><hr />1</td></tr>
                           <tr><td colspan="5"><pre>' . \CoreDebug::getSourceCodeInFile($error['file'],
                                                                                        $error['line']) . '</pre></td></tr>
			';
                    }
                    else {
                        $Output .= '<tr><td colspan="5"><hr />3</td></tr>
                           <tr><td colspan="5">Debug not loadet</td></tr>
			';
                    }

                    $Output .= '
                               <tr><td colspan="5"><hr />4</td></tr>
                           <tr><td colspan="5"><pre>' . $contentSend . '</pre></td></tr>
                          </table>
			';

                }

                if (
                    \Config::get('/environment/debug/errorhandler/shutdownErrorViewMessage',
                                 0) === 1
                ) {
                    $Output .= '<tr><td colspan="5"><hr />5</td></tr>
                           <tr><td colspan="5"><pre style="overflow: auto;display: inline-block; height: 100px;">' . var_export(\CoreDebug::getRawDebugData('CoreErrorhandler'),
                                                                                                                                true) . '</pre></td></tr>
			';
                }

                if (
                    \Config::get('/environment/debug/errorhandler/shutdownErrorViewProfiler',
                                 0) === 1 && class_exists('CoreDebugProfiler',
                                                          false) === true
                ) {
                    $Output .= '<tr><td colspan="5"><hr />6</td></tr>
                           <tr><td colspan="5"><pre style="overflow: auto;display: inline-block; height: 100px;">' . var_export(\CoreDebug::getRawDebugData('CoreDebugProfiler'),
                                                                                                                                true) . '</pre></td></tr>
			';
                }

                $Output .= '</div></body></html>';

                $headersList = headers_list();
                if (
                    in_array('Content-Encoding: gzip',
                             $headersList) === true
                ) {
                    $Output = \SysCore::Gzip($Output);
                }

                echo $Output;
            }
        }
        else {
            if (defined('CMS_SHOW_ERROR_COMMENT') && CMS_SHOW_ERROR_COMMENT === true) {
                $output    = '/*' . PHP_EOL;
                $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                $errorData = CoreDebug::getRawDebugData('CoreErrorhandler');
                foreach ($errorData as $key => $elem) {
                    $output .= $key . ' - ' . $elem['level'] . ' - ' . var_export($elem['message'],
                                                                                  true) . ' - ' . $elem['file'] . ' - ' . $elem['line'] . '<hr />7<pre>' . var_export($backtrace,
                                                                                                                                                                      true) . '</pre><hr />' . PHP_EOL;
                }

                $output .= '*/' . PHP_EOL;

                echo $output;
            }
        }
    }

}

function cmsShutdown(): void
{
    d(error_get_last());

//    cmsShutdownError();
//    ob_end_flush();
//    exit;

}
