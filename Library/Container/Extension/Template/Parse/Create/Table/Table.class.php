<?php declare(strict_types=1);

class ContainerExtensionTemplateParseCreateTable extends ContainerExtensionTemplateParseCreate_abstract
{
    protected static array $container = [];

    /**
     *
     * Parse the Template.
     *
     * @return string
     * @throws DetailedException
     */
    function parse(): string
    {
        $parameter = $this->getParameter();

        if (isset($parameter['data']) && !empty($parameter['data'])) {
            return $this->parseTable();
        }

        return '';
    }

    protected function parseHeader(): void
    {
        $parameter = $this->getParameter();

        /** @var ContainerExtensionTemplateParseCreateTableTable $table */
        $table = $this->checkContainer($parameter);

        $table->setConfigHeader($parameter['key'],
                                $parameter);
    }

    protected function checkContainer(array $parameter): array
    {
        if (empty(self::$container[$parameter['table']])) {

            /** @var ContainerExtensionTemplate $parentObjectTemplate */
            $parentObjectTemplate = $this->getParentTemplateObject();

            /** @var ContainerExtensionTemplateInternalAssign $assignObject */
            $assignObject = $parentObjectTemplate->getAssignObject();

            self::$container[$parameter['table']] = Container::get('ContainerExtensionTemplateParseCreateTableTable',
                                                                   $parameter,
                                                                   $assignObject);
        }

        return self::$container[$parameter['table']];
    }

    protected function parseTable(): string
    {
        $parameter = $this->getParameter();

        $parentObjectTemplate = $this->getParentTemplateObject();

        $parentObjectTemplate->parseQuote();
        $parentObjectTemplate->catchData();

        if ($parentObjectTemplate->getCacheData($parameter['data']) === null) {
            throw new DetailedException('iniDataTemplateError',
                                        0,
                                        null,
                                        [
                                            'debug' => [

                                            ]
                                        ]);
        }

        $iniData = parse_ini_string($parentObjectTemplate->getCacheData($parameter['data']),
                                    true);

        if ($iniData === false) {
            return '';
        }

        $assignObject  = $parentObjectTemplate->getAssignObject();
        $iniDataConfig = ($iniData['_config'] ?? []);
        unset($iniData['_config']);

        /** @var ContainerExtensionTemplateParseCreateTableTable $table */
        $table = Container::get('ContainerExtensionTemplateParseCreateTableTable',
                                $parameter,
                                $assignObject,
                                $iniDataConfig['table']);

        foreach ($iniData as $iniDataKey => $iniDataItem) {
            $table->setConfigHeader($iniDataKey,
                ($iniDataItem ?? []));
            $table->setConfigRow($iniDataKey,
                ($iniDataItem ?? []));
        }

        if (!empty($iniDataConfig['uniqid'])) {
            $table->setUniqid($iniDataConfig['uniqid']);
        }

        $table->setTableClass($iniDataConfig['cssClass'] ?? '');

        $table->setData($assignObject->get($iniDataConfig['table'] . '_' . $iniDataConfig['source']) ?? []);

        return $table->get();
    }

}

