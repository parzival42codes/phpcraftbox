<?php
declare(strict_types=1);

class ContainerExtensionCacheMemcached implements ContainerExtensionCache_interface
{
    protected static ?Memcached $memcached     = null;
    protected static bool   $connected = false;

    public function __construct(ContainerExtensionCache_abstract $cacheObj)
    {

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

    }

    public static function connection()
    {
        if (self::$memcached === null) {
            self::$memcached = new Memcached();
            try {
                self::$memcached->addServer((string)Config::get('/environment/server/redis/host'),
                                                         (int)Config::get('/environment/server/redis/port'));
            } catch (Throwable $e) {
                self::$connected = false;
            }
        }

        return self::$connected;

    }

}
