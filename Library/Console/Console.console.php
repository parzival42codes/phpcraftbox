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

        $this->addProgressFunction(function ($progressData) {
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

                    echo Console_abstract::generateList($smtp->fetchAll());

//                $this->generateList([
//                    'class',
//                    'path',
//                                    ]);
                    break;
                default:


            }

            $progressData['message'] = 'View Cache: ' . $this->parameter[0];
            return $progressData;
        });
    }

    public function prepareTest()
    {

        $this->addProgressFunction(function ($progressData) {

            $testArray = [];

            $testArray[] = [
                'foo'    => 123,
                'bar'    => 456,
                'fooBar' => 456,
            ];

            $testArray[] = [
                'foo' => 'abc',
                'bar' => 'def',
            ];

            $testArray[] = [
                'bar' => 'Lorem Ipsum',
            ];

            echo Console_abstract::generateList($testArray);

            $progressData['message'] = 'foo bar';
            return $progressData;
        });
    }

}
