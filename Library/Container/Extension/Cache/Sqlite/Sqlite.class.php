<?php
declare(strict_types=1);

class ContainerExtensionCacheSqlite implements ContainerExtensionCache_interface
{
    protected static array $persistentCache = [];

    public function __construct(ContainerExtensionCache_abstract $cacheObj)
    {
        if (
            ContainerFactoryDatabaseEngineSqlite::tableIsInDatabase('cache',
                                                                    'cache') === false
        ) {

            /** @var ContainerFactoryDatabaseEngineSqliteTable $queryStructure */
            $queryStructure = Container::get('ContainerFactoryDatabaseEngineSqliteTable',
                                             'cache');
            $queryStructure->setColumn('ident',
                                       'varchar;100');
            $queryStructure->setColumn('content',
                                       'text',
                                       true,
                                       ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL);
            $queryStructure->setColumn('target',
                                       'varchar;10');
            $queryStructure->setColumn('ttl',
                                       'int');
            $queryStructure->setColumn('ttlDatetime',
                                       'datetime');
            $queryStructure->setColumn('persistent',
                                       'tinyint;1');
            $queryStructure->setColumn('size',
                                       'int;11');
            $queryStructure->setColumn('dataVariableUpdated',
                                       'datetime');
            $queryStructure->setPrimary('ident');

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

            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __CLASS__ . '.create.console',
                                    'cache',
                                    ContainerFactoryDatabaseQuery::MODE_OTHER);

            $query->query('CREATE INDEX `persistent` ON `cache` (`persistent`)');
            $query->execute();

            ContainerFactoryDatabaseEngineSqlite::addTableDatabase('cache',
                                                                   'cache');
        }

        if (
            (!PAGE_REFRESH_DETECT_DEBUG)
        ) {
            if (empty(self::getPersistentCacheAll())) {
                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __METHOD__ . '#select',
                                        'cache',
                                        ContainerFactoryDatabaseQuery::MODE_SELECT);

                $query->setTable('cache');
                $query->select('ident');
                $query->select('content');
                $query->select('target');
                $query->select('ttl');
                $query->select('ttlDatetime');
                $query->select('persistent');
                $query->select('size');
                $query->select('dataVariableUpdated');

                $query->setParameterWhere('persistent',
                                          1);

                $query->construct();
                $smtp = $query->execute();

                while ($smtpData = $smtp->fetch()) {
                    self::setPersistentCache($smtpData['ident'],
                                             $smtpData);
                }
            }

            if (self::getPersistentCache($cacheObj->getIdent())) {
                $cacheObj->setCacheContent(unserialize(self::getPersistentCache($cacheObj->getIdent())['content']));
                $cacheObj->setTtl((int)self::getPersistentCache($cacheObj->getIdent())['ttl']);
                $cacheObj->setTarget((int)self::getPersistentCache($cacheObj->getIdent())['target']);
                $cacheObj->setPersistent((bool)self::getPersistentCache($cacheObj->getIdent())['persistent']);
            }

            if (empty($cacheObj->getCacheContent())) {
                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __METHOD__ . '#select',
                                        'cache',
                                        ContainerFactoryDatabaseQuery::MODE_SELECT);

                $query->setTable('cache');
                $query->select('ident');
                $query->select('content');
                $query->select('target');
                $query->select('persistent');
                $query->select('ttl');
                $query->select('ttlDatetime');
                $query->select('dataVariableUpdated');

                $query->setParameterWhere('ident',
                                          $cacheObj->getIdent());
                $query->setLimit(1);

                $query->construct();
                $smtp = $query->execute();

                $smtpData = $smtp->fetch();

                if ($smtpData !== false) {
                    $cacheObj->setCacheContent(unserialize($smtpData['content']));
                    $cacheObj->setTtl((int)$smtpData['ttl']);
                    $cacheObj->setTtlDatetime($smtpData['ttlDatetime']);
                    $cacheObj->setTarget((int)$smtpData['target']);
                    $cacheObj->setPersistent((bool)$smtpData['persistent']);
                    $cacheObj->setDataVariableUpdated($smtpData['dataVariableUpdated']);
                }
            }
        }
        else {
            $cacheObj->setCacheContent(null);
            $cacheObj->setPersistent(false);
        }

    }

    /**
     * @param ContainerExtensionCache_abstract $cacheObj
     * @param bool                             $forceCreate
     * @param array                            $scope
     *
     * @throws DetailedException
     */
    public function getCacheContent(ContainerExtensionCache_abstract $cacheObj, array &$scope, bool $forceCreate = false)
    {
        $cacheName = explode('_',
                             get_class($cacheObj),
                             2);

        $scope['cacheClassName'] = $cacheName[0];
        $scope['cacheName']      = $cacheName[1];
        $scope['isCreated']      = false;

        if (
            empty($cacheObj->getCacheContent()) || $forceCreate === true
        ) {
            $cacheObj->setCacheContent(null);
            $cacheObj->create();
            $cacheObj->setIsCreated(true);
            $scope['isCreated'] = true;

            if (empty($cacheObj->getIdent())) {
                throw new DetailedException('noIdent');
            }

            if (
                (!PAGE_REFRESH_DETECT_DEBUG)
            ) {

                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __METHOD__ . '#insertUpdate',
                                        'cache',
                                        \ContainerFactoryDatabaseQuery::MODE_INSERT_UPDATE);

                $serializeData = serialize($cacheObj->getCacheContent());
                $query->setTable('cache');
                $query->setTableKey('ident');
                $query->setInsertUpdate('ident',
                                        $cacheObj->getIdent());
                $query->setInsertUpdate('content',
                                        $serializeData,
                                        true);
                $query->setInsertUpdate('target',
                                        $cacheObj->getTarget(),
                                        true);
                $query->setInsertUpdate('ttl',
                                        $cacheObj->getTtl(),
                                        true);
                $query->setInsertUpdate('persistent',
                                        (int)$cacheObj->getPersistent(),
                                        true);
                $query->setInsertUpdate('size',
                                        strlen($serializeData),
                                        true);

                $ttlDatetime = new \DateTime();

                if ($cacheObj->getTtl() > 0) {
                    $ttlDatetime->modify('' . $cacheObj->getTtl() . 's');
                }
                $query->setInsertUpdate('ttlDatetime',
                    ((empty($this->ttl)) ? '0000-00-00 00:00:00' : $ttlDatetime->format((string)Config::get('/cms/date/dbase'))),
                                        true);

                $query->construct();
                $query->execute();
            }
        }
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public static function getPersistentCache(string $key)
    {
        return (self::$persistentCache[$key] ?? null);
    }

    /**
     * @return array
     */
    public static function getPersistentCacheAll(): array
    {
        return self::$persistentCache;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public static function setPersistentCache(string $key, $value): void
    {
        self::$persistentCache[$key] = $value;
    }

}
