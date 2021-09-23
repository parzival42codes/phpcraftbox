<?php declare(strict_types=1);

class ContainerExtensionTemplateParseInsertInsert extends ContainerExtensionTemplateParseInsert_abstract
{
    /**
     * @var array
     */
    protected static array $insertion = [];

    public static function insert(string $key, string $value): void
    {
        CoreDebugLog::addLog('/Template/Insert/Insert/Insert',
                             $key . ' => ' . $value);
        self::$insertion[$key] = $value;
    }

    function parse(): string
    {
        $parameter = $this->getParameter();
        $key       = $parameter['key'];

        CoreDebugLog::addLog('/Template/Insert/Insert/Parse',
                             $key);

        return self::$insertion[$key];
    }

}
