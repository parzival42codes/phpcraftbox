<?php declare(strict_types=1);

class ContainerExtensionTemplateParseInsertPositions extends ContainerExtensionTemplateParseInsert_abstract
{
    /**
     * @var array
     */
    protected static array $positionInsertion = [];

    public static function load(): void
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);

        $query->setTable('template_positions');
        $query->selectRaw('GROUP_CONCAT(crudContent SEPARATOR "") as crudContentConcat');
        $query->select('crudPosition');
        $query->groupBy('crudPosition');

        $query->construct();
        $smtp = $query->execute();

        while ($smtpData = $smtp->fetch()) {
            self::$positionInsertion[$smtpData['crudPosition']] = trim($smtpData['crudContentConcat']);
        }

        self::$positionInsertion['/_/base/url'] = Config::get('/server/http/base/url');
    }

    public static function insert(string $position, string $content): void
    {
        CoreDebugLog::addLog('/Template/Insert/Position/Insert',
                             $position);

        if (!isset(self::$positionInsertion[$position])) {
            self::$positionInsertion[$position] = '';
        }
        self::$positionInsertion[$position] .= $content;
    }

    function parse(): string
    {
        $parameter = $this->getParameter();
        $position  = (self::$positionInsertion[$parameter['position']] ?? ($parameter['default'] ?? ''));

        CoreDebugLog::addLog('/Template/Insert/Position/Parse',
                             $position);


        return $position;
    }

    public static function add(string $key, string $value)
    {
        CoreDebugLog::addLog('/Template/Insert/Position/Add',
                             $key . ' => ' . $value);


        self::$positionInsertion[$key] = $value;
    }
}
