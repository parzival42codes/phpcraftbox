<?php
declare(strict_types=1);

class ContainerFactoryUserGroupAccess extends Base
{

    protected array $access = [];

    public function __construct(array $access)
    {
        $this->access = $access;

//        $pathActiveSeek = [];
//
//        $serverPath        = Config::get('/server/http/path');
//        $serverPathExplode = explode('/',
//                                     $serverPath);
//
//        array_shift($serverPathExplode);
//
//        $serverPathExplodeItemLast = '';
//        foreach ($serverPathExplode as $serverPathExplodeItem) {
//            $pathActiveSeek[]          = $serverPathExplodeItemLast . '/' . $serverPathExplodeItem;
//            $serverPathExplodeItemLast = $serverPathExplodeItemLast . '/' . $serverPathExplodeItem;
//        }


//
//        in_array($router->getUrlReadable(true),
//                 $pathActiveSeek)

    }

    public function isIn(string $path):void
    {

    }


}
