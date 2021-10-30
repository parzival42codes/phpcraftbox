<?php

class Console_console extends Console_abstract
{

    public function prepareCacheDelete()
    {
        $this->addProgressFunction(function ($progressData) {

            $cacheSource = 'sqlite';
            if (Config::get('/ContainerExtensionCache/source') === 'redis') {
                if (ContainerExtensionCacheRedis::connection()) {
                    $cacheSource = 'redis';
                };
            }
            elseif (Config::get('/ContainerExtensionCache/source') === 'memcached') {
                if (ContainerExtensionCacheMemcached::connection()) {
                    $cacheSource = 'memcached';
                };
            }

            if ($cacheSource === 'redis') {
                $cacheResourceObj = new ContainerExtensionCacheRedis();
            }
            elseif ($cacheSource === 'memcached') {
                $cacheResourceObj = new ContainerExtensionCacheMemcached();
            }
            else {
                $cacheResourceObj = new ContainerExtensionCacheSqlite();
            }

            $progressData['message'] = 'Flush Cache: ' . $cacheSource . ' : ' . (int)$cacheResourceObj::flush();

            return $progressData;
        });
    }


    public function prepareCacheContent()
    {
        d($this->parameter);

        switch ($this->parameter[0]) {
            case 'autoload':
                $query = new ContainerFactoryDatabaseQuery(__METHOD__ . '#select',
                                                           'cache',
                                                           ContainerFactoryDatabaseQuery::MODE_SELECT);

                $query->setTable('autoload');
                $query->select('path');
                $query->select('class');

                $query->construct();
                $smtp = $query->execute();

                d($smtp->fetchAll());

//                $this->generateList([
//                    'class',
//                    'path',
//                                    ]);
                break;
            default:


        }
    }

    public function prepareTest()
    {

        $this->addProgressFunction(function ($progressData) {
            $progressData['message'] = 'foo bar';
            return $progressData;
        });
    }

}
