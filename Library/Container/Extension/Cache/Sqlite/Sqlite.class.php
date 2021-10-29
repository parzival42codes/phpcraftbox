<?php
declare(strict_types=1);

class ContainerExtensionCacheSqlite implements ContainerExtensionCache_interface
{
    protected static array $persistentCache = [];

    public function __construct()
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

    }

    /**
     * @param ContainerExtensionCache_abstract $cacheObj
     * @param bool                             $forceCreate
     * @param array                            $scope
     *
     * @throws DetailedException
     */
    public function get(ContainerExtensionCache_abstract $cacheObj, array &$scope, bool $forceCreate = false)
    {
        $cacheName = explode('_',
                             get_class($cacheObj),
                             2);

        $scope['cacheClassName'] = $cacheName[0];
        $scope['cacheName']      = $cacheName[1];
        $scope['isCreated']      = false;

        if (self::getPersistentCache($cacheObj->getIdent())) {
            $cacheObj->setCacheContent(unserialize(self::getPersistentCache($cacheObj->getIdent())['content']));
        }
        else {
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
            $query->select('size');
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
                $cacheObj->setSize((int)$smtpData['size']);
                $cacheObj->setDataVariableUpdated($smtpData['dataVariableUpdated']);
            }

        }

        if (
            empty($cacheObj->getCacheContent()) || $forceCreate === true || PAGE_REFRESH_DETECT_DEBUG
        ) {
            $cacheObj->setCacheContent(null);
            $cacheObj->create();
            $cacheObj->setIsCreated(true);
            $scope['isCreated'] = true;

            if (empty($cacheObj->getIdent())) {
                throw new DetailedException('noIdent');
            }

//            if ($cacheObj->getCacheContent() !== null) {

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
            $query->setInsertUpdate('persistent',
                                    (int)$cacheObj->getPersistent(),
                                    true);
            $query->setInsertUpdate('size',
                                    strlen((is_string($cacheObj->getCacheContent()) ? $cacheObj->getCacheContent() : var_export($cacheObj->getCacheContent(),
                                                                                                                                true))),
                                    true);

            $ttlDatetime = new \DateTime();

            if ($cacheObj->getTtl() > 0) {
                $ttlDatetime->modify('+' . $cacheObj->getTtl() . ' seconds');
            }
            else {
                if (Config::get('/ContainerExtensionCache/ttl') > 0) {
                    $ttlDatetime->modify('+' . Config::get('/ContainerExtensionCache/ttl') . ' seconds');
                    $cacheObj->setTtl((int)Config::get('/ContainerExtensionCache/ttl'));
                }
            }

            $query->setInsertUpdate('ttl',
                                    $cacheObj->getTtl(),
                                    true);

            $query->setInsertUpdate('ttlDatetime',
                (empty($cacheObj->getTtl())) ? '0000-00-00 00:00:00' : $ttlDatetime->format((string)Config::get('/cms/date/dbase')),
                                    true);

            $query->construct();
            $query->execute();

//            }
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

    public static function getCache()
    {

        $cacheContent = [];

        foreach (self::getPersistentCacheAll() as $element) {
            $cacheContent[] = [
                'key'         => $element['ident'],
                'content'     => $element['content'],
                'ttl'         => $element['ttl'],
                'ttlDateTime' => $element['ttlDatetime'],
                'size'        => ContainerHelperCalculate::calculateMemoryBytes((int)$element['size']),
            ];
        }

        return $cacheContent;


    }


}
