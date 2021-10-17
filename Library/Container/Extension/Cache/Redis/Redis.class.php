<?php
declare(strict_types=1);

class ContainerExtensionCacheRedis implements ContainerExtensionCache_interface
{
    protected static ?Redis $redis     = null;
    protected static bool   $connected = false;

    public function __construct(ContainerExtensionCache_abstract $cacheObj)
    {

        if (
            (!PAGE_REFRESH_DETECT_DEBUG)
        ) {

            if (self::$redis->get($cacheObj->getIdent()) !== false) {
                $cacheObj->setCacheContent(unserialize(self::$redis->get($cacheObj->getIdent())));
            }
            else {
                $cacheObj->setCacheContent(null);
            }

            $cacheObj->setTtl((int)Config::get('/ContainerExtensionCache/ttl'));
            $cacheObj->setTarget(ContainerExtensionCache_abstract::TARGET_INTERN);
            $cacheObj->setPersistent(true);
        }
        else {
            $cacheObj->setCacheContent(null);
            $cacheObj->setPersistent(false);
        }
    }

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
                $serializeData = serialize($cacheObj->get());
                self::$redis->set($cacheObj->getIdent(),
                                  $serializeData,
                                  (int)Config::get('/ContainerExtensionCache/ttl'));
            }
        }
    }

    public static function connection()
    {

        if (self::$redis === null) {
            self::$redis = new Redis();
            try {
                self::$connected = self::$redis->connect((string)Config::get('/environment/server/redis/host'),
                                                         (int)Config::get('/environment/server/redis/port'));
            } catch (Throwable $e) {
                self::$connected = false;
            }
        }

        return self::$connected;
    }

}
