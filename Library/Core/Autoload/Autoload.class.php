<?php

class CoreAutoload
{

    private static array $classCache = [];

    public static function autoLoadClass(string $class): void
    {

        self::load($class);

        if (
        method_exists($class,
                      '_autoload')
        ) {
            call_user_func([
                               $class,
                               '_autoload'
                           ]);
        }

        if (
            Config::get('/debug/status',
                        CMS_DEBUG_ACTIVE) === true
        ) {
            CoreDebug::setRawDebugData(__CLASS__,
                                       [
                                           'class' => $class,
                                           'load'  => (int)method_exists($class,
                                                                         '_load'),
                                           'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
                                       ]);


        }

    }

    public static function load(string $class): void
    {

        if (
            ContainerFactoryDatabaseEngineSqlite::tableIsInDatabase('cache',
                                                                    'autoload') === false
        ) {
            /** @var ContainerFactoryDatabaseEngineSqliteTable $queryStructure */
            $queryStructure = Container::get('ContainerFactoryDatabaseEngineSqliteTable',
                                             'autoload');
            $queryStructure->setColumn('class',
                                       'varchar;50');
            $queryStructure->setColumn('path',
                                       'varchar;50');

            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __CLASS__ . '.create.console',
                                    'cache',
                                    ContainerFactoryDatabaseQuery::MODE_OTHER);

            foreach ($queryStructure->createQuery() as $queryItem) {
                $query->query($queryItem);
                $query->execute();
            }

            ContainerFactoryDatabaseEngineSqlite::reInit();
        }

        if (
        class_exists('ContainerFactoryDatabaseQuery',
                     false)
        ) {
            if (empty(self::$classCache)) {

                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __CLASS__ . '.create.console',
                                        'cache',
                                        ContainerFactoryDatabaseQuery::MODE_SELECT);
                $query->setTable('autoload');
                $query->selectRaw('class');
                $query->selectRaw('path');
                $query->construct();
                $smtp = $query->execute();

                while ($item = $smtp->fetch()) {
                    self::$classCache[$item['class']] = $item['path'];
                }
            }
        }

        if (isset(self::$classCache[$class])) {
            require_once(self::$classCache[$class]);
            return;
        }

        try {

            $filePath = Core::getPHPClassFileName($class);

            if ($filePath !== null) {

                if (is_file($filePath) === true) {
                    require_once($filePath);

                    /** @var ContainerFactoryDatabaseQuery $query */
                    $query = Container::get('ContainerFactoryDatabaseQuery',
                                            __CLASS__ . '.create.console',
                                            'cache',
                                            ContainerFactoryDatabaseQuery::MODE_INSERT);
                    $query->setTable('autoload');
                    $query->setInsertInto('class',
                                          $class);
                    $query->setInsertInto('path',
                                          $filePath);

                    $query->construct();
                    $query->execute();
                }
                else {
                    throw new DetailedException('pathNotFound',
                                                0,
                                                null,
                                                [
                                                    'debug' => [
                                                        'class'    => $class,
                                                        'filePath' => $filePath,
                                                    ]
                                                ]);
                }
            }
            else {
                throw new DetailedException('pathNotFound',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    'class'    => $class,
                                                    'filePath' => $filePath,
                                                ]
                                            ]);
            }


        } catch (Throwable $e) {
            throw $e;
        }
    }

    public static function register(): void
    {
        spl_autoload_register([
                                  __CLASS__,
                                  'autoLoadClass'
                              ]);
    }

}

CoreAutoload::register();
