<?php
declare(strict_types=1);

class ContainerExtensionCacheRedis implements ContainerExtensionCache_interface
{
    protected static ?Redis $redis     = null;
    protected static bool   $connected = false;
    protected array         $redisData = [];

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

            if ($cacheData !== false) {

                $cacheObj->setCacheContent($cacheData['content']);
                $cacheObj->setTtl((int)$cacheData['ttl']);
                $cacheObj->setTtlDatetime($cacheData['ttlDatetime']);
                $cacheObj->setTarget((int)$cacheData['target']);
                $cacheObj->setPersistent(true);
                $cacheObj->setDataVariableUpdated($cacheData['dataVariableUpdated']);
                $cacheObj->setSize($cacheData['size']);


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

            $ttlDatetimeNow = new \DateTime();

            $serializeData = serialize([
                                           'content'             => $cacheObj->getCacheContent(),
                                           'ttl'                 => $cacheObj->getTtl(),
                                           'ttlDatetime'         => (empty($cacheObj->getTtl())) ? '0000-00-00 00:00:00' : $ttlDatetime->format((string)Config::get('/cms/date/dbase')),
                                           'target'              => $cacheObj->getTarget(),
                                           'dataVariableUpdated' => $ttlDatetimeNow->format((string)Config::get('/cms/date/dbase')),
                                           'size'                => strlen((is_string($cacheObj->getCacheContent()) ? $cacheObj->getCacheContent() : var_export($cacheObj->getCacheContent(),
                                                                                                                                                                true))),
                                       ]);

            self::$redis->setex($cacheObj->getIdent(),
                                (int)Config::get('/ContainerExtensionCache/ttl'),
                                $serializeData);

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

    public static function flush()
    {
        return self::$redis->flushAll();
    }

    public static function getCache()
    {
        if (self::connection()) {

            $keys = self::$redis->keys('*');

            $cacheContent = [];
            foreach ($keys as $key) {

                $redisData = unserialize(self::$redis->get($key));

                if ($redisData) {
                    $cacheContent[] = [
                        'key'         => $key,
                        'content'     => is_string($redisData['content']) ? $redisData['content'] : var_export($redisData['content'],
                                                                                                               true),
                        'ttl'         => self::$redis->ttl($key) . ' / ' . $redisData['ttl'],
                        'ttlDateTime' => $redisData['ttlDatetime'],
                        'size'        => ContainerHelperCalculate::calculateMemoryBytes($redisData['size']),
                    ];
                }
                else {
                    $cacheContent[] = [
                        'key'         => $key,
                        'content'     => '???',
                        'ttl'         => 0,
                        'ttlDatetime' => '0',
                        'size'        => 0,
                    ];
                }

            }

//            d($cacheContent);
//            eol();

            return $cacheContent;
        }
        else {
            return [];
        }

    }

}
