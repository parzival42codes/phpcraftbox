<?php
declare(strict_types=1);

class ContainerExtensionCacheRedis implements ContainerExtensionCache_interface
{
    protected static ?Redis $redis     = null;
    protected static bool   $connected = false;

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

        $redisData = self::$redis->get($cacheObj->getIdent());

        if ($redisData !== false) {
            $cacheData = unserialize($redisData);
            if (!$cacheData !== false) {
                $cacheObj->setCacheContent($cacheData);
            }
            else {
                $cacheObj->setCacheContent(null);
            }
        }
        else {
            $cacheObj->setCacheContent(null);
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

            $serializeData = serialize($cacheObj->getCacheContent());
            self::$redis->set($cacheObj->getIdent(),
                              $serializeData,
                              (int)Config::get('/ContainerExtensionCache/ttl'));

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
