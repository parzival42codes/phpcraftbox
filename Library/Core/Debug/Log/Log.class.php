<?php declare(strict_types=1);

/**
 * Class CoreDebugLog
 */
class CoreDebugLog extends Base
{

    /**
     *
     */
    const LOG_TYPE_LOG = 'log';
    /**
     *
     */
    const LOG_TYPE_NOTE = 'note';
    /**
     *
     */
    const LOG_TYPE_DEPRECATED = 'deprecated';
    /**
     *
     */
    const LOG_TYPE_WARNING = 'warning';
    /**
     *
     */
    const LOG_TYPE_ERROR = 'error';
    /**
     *
     */
    const LOG_TYPE_EXCEPTION = 'exception';
    /**
     *
     */
    const LOG_TYPE_MAIN_STEP = 'main_step';

    const ICON_ERROR = '!';

    protected static array $logging = [];

    /**
     * @param string $ident
     * @param string $text
     * @param string $type
     * @param string $icon
     *
     */
    public static function addLog(string $ident, string $text, string $type = self::LOG_TYPE_LOG, string $icon = ''): void
    {
        if (!PCB_ENV_DEBUG) {
            return;
        }

        CoreDebug::setRawDebugData('CoreDebugLog',
                                   [
                                       'ident'     => $ident,
                                       'text'      => $text,
                                       'type'      => $type,
                                       'icon'      => $icon,
                                       'microtime' => (microtime(true) - CMS_SYSTEM_START_TIME),
                                       'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
                                   ]);
    }

    public static function assertIsEqual(string $text, $value, $valueTest): void
    {

    }
}
