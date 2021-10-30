<?php
declare(strict_types=1);

class ContainerExtensionCacheMemcached implements ContainerExtensionCache_interface
{
    protected static ?Memcached $memcached = null;
    protected static bool       $connected = false;

    /**
     * @param ContainerExtensionCache_abstract $cacheObj
     * @param array                            $scope
     *
     * @param bool                             $forceCreate
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

        $cacheObj->setCacheContent(self::$memcached->get($cacheObj->getIdent()));

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

            self::$memcached->set($cacheObj->getIdent(),
                                  $cacheObj->getCacheContent(),
                                  (int)(Config::get('/ContainerExtensionCache/ttl') * 1000));
        }

        $cacheObj->setTtl((int)Config::get('/ContainerExtensionCache/ttl'));
        $cacheObj->setTarget(ContainerExtensionCache_abstract::TARGET_INTERN);
        $cacheObj->setPersistent(true);

    }

    public static function connection()
    {
        if (self::$memcached === null) {
            self::$memcached = new Memcached();
            try {
                self::$memcached->addServer((string)Config::get('/environment/server/memcached/host'),
                                            (int)Config::get('/environment/server/memcached/port'));
                self::$connected = (bool)self::$memcached->getStats();
            } catch (Throwable $e) {
                self::$connected = false;
            }
        }

        return self::$connected;

    }

    public static function flush()
    {
        return self::$memcached->flush();
    }

}
