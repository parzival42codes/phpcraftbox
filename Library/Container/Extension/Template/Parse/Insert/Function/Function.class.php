<?php declare(strict_types=1);

class ContainerExtensionTemplateParseInsertFunction extends ContainerExtensionTemplateParseInsert_abstract
{
    /**
     * @var array
     */
    protected static array $insertion = [];

    public static function insert(string $key, callable $value): void
    {
        CoreDebugLog::addLog('/Template/Insert/Function/Insert',
                             $key);
        self::$insertion[$key] = $value;
    }

    function parse(): string
    {
        $parameter = $this->getParameter();
        $key       = $parameter['function'];

        CoreDebugLog::addLog('/Template/Insert/Function/Parse',
                             $key);

        return call_user_func(self::$insertion[$key],
                              $parameter);

    }

}
