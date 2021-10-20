<?php

class Config extends Base_abstract_keyvalue
{
    private static array $registry            = [];
    private static array $convertArrayContent = [];
    protected string     $prefix              = 'config';
    protected string     $group               = '';

    public static function setCore(array $core): void
    {
        self::convertArray($core);
        self::$registry = self::$convertArrayContent;
    }

    private static function convertArray(array $array, string $path = ''): void
    {
        foreach ($array as $key => $item) {
            if (is_array($item) === true) {
                self::convertArray($item,
                                   $path . '/' . $key);
            }
            else {
                self::$convertArrayContent[$path . '/' . $key] = $item;
            }
        }
    }

    public static function getAll(): array
    {
        return self::$registry;
    }

    public static function get(string $path, $default = null)
    {
        if (
            array_key_exists($path,
                             self::$registry)
        ) {
            return self::$registry[$path];
        }

        $pathClass = explode('/',
                             $path,
                             3);

        array_shift($pathClass);
        $class = array_shift($pathClass);

        $formData = self::getForm($class,
                                  null);

        $pathName = '/' . $pathClass[0];

        if (isset($formData[$pathName]['value'])) {
            return $formData[$pathName]['value'];
        }


        if ($default !== null) {
            return $default;
        }

        throw new DetailedException('configReadFail',
                                    0,
                                    null,
                                    [
                                        'debug' => [
                                            [
                                                'configKey' => $path,
                                                'config'    => '<pre>' . var_export(self::$registry,
                                                                                    true) . '</pre>'
                                            ],
                                        ]
                                    ]);
    }

    public static function setDatabase(): void
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('config');
        $query->select('crudClass',
                       'crudConfigKey',
                       'crudConfigValue');
        $query->setParameterWhere('crudConfigGroup',
                                  null);
        $query->construct();
        $smtp = $query->execute();

        while ($smtpData = $smtp->fetch()) {
            self::$registry['/' . $smtpData['crudClass'] . $smtpData['crudConfigKey']] = $smtpData['crudConfigValue'];
        }
    }

    public static function getForm(string $rootClass, ?string $path = ''): array
    {
        $fileConfigImport = new ContainerFactoryFile($rootClass . '.install.config.json');
        if ($fileConfigImport->exists()) {

            $fileConfigImport->load();
            $fileConfigImport->decode();
            $configAll = $fileConfigImport->get();

            if ($path !== null) {
                return ($configAll['content'][$path]['form'] ?? []);
            }
            else {
                return $configAll['content'];
            }
        }
        else {
            return [];
        }
    }

}
