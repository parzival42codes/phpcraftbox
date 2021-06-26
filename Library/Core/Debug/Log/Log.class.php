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
    const LOG_TYPE_WARN = 'warn';
    /**
     *
     */
    const LOG_TYPE_ERROR = 'error';
    /**
     *
     */
    const LOG_TYPE_EXCEPTION = 'exception';

    const ICON_ERROR = '!';

    protected static array $logging = [];

    /**
     * @param string $text
     * @param string $type
     */
    public static function addLog(string $text, string $type = self::LOG_TYPE_LOG, string $icon = '', bool $saveIntoDB = false): void
    {
        $date = new DateTime();

        self::$logging[] = [
            'text'     => $text,
            'type'     => $type,
            'icon'     => $icon,
            'datetime' => $date->format(Config::get('/environment/datetime/format')),
        ];
    }

    public static function assertIsEqual(string $text, $value, $valueTest): void
    {

    }
}
