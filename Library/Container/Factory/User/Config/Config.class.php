<?php

class ContainerFactoryUserConfig extends Base

{
    protected static array $registry        = [];
    protected static array $registryDefault = [];

    public static function get(string $path, ?int $user = null)
    {

        if ($user !== null) {
            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __METHOD__ . '#select',
                                    true,
                                    ContainerFactoryDatabaseQuery::MODE_SELECT);
            $query->setTable('user_config');
            $query->addSelect('CASE WHEN user_config_user.crudConfigValue IS NULL THEN user_config.crudConfigValueDefault ELSE user_config_user.crudConfigValue END as configValue');
            $query->addSelect('user_config.crudIdent');

            $query->join('user_config_user',
                         [],
                         'user_config.crudIdent = user_config_user.crudIdent AND user_config_user.crudId = ' . $user);

            $query->setParameterWhere('user_config.crudIdent',
                                      $path);
            $query->setLimit(1);

            $query->construct();
            $smtp = $query->execute();

            $smtpData = $smtp->fetch();

            return $smtpData['configValue'];
        }

//        d($path);
//
//        d(self::$registry);
//
//        d(array_key_exists($path,
//                           self::$registry));
//
//        d(self::$registry[$path]);
//
//        d(array_key_exists($path,
//                           self::$registryDefault));
//
//        d(self::$registryDefault[$path]);

        if (
        array_key_exists($path,
                         self::$registry)
        ) {
            return self::$registry[$path];
        }
//        elseif (
//        array_key_exists($path,
//                         self::$registryDefault)
//        ) {
//            return self::$registryDefault[$path];
//        }
        else {
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
    }

    public static function setDatabase(): void
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('user_config');
        $query->addSelect('CASE WHEN user_config_user.crudConfigValue IS NULL THEN user_config.crudConfigValueDefault ELSE user_config_user.crudConfigValue END as configValue');
        $query->addSelect('user_config.crudIdent');

        $query->join('user_config_user',
                     [],
                     'user_config.crudIdent = user_config_user.crudIdent');

        $query->setParameterWhere('crudConfigGroup',
                                  null);

        $query->construct();
        $smtp = $query->execute();

        while ($smtpData = $smtp->fetch()) {
//            self::$registryDefault[$smtpData['crudIdent']] = $smtpData['crudConfigValueDefault'];
            self::$registry[$smtpData['crudIdent']] = $smtpData['configValue'];
        }
    }


}
