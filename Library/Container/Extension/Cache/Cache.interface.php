<?php
declare(strict_types=1);

interface ContainerExtensionCache_interface
{

    public function get(ContainerExtensionCache_abstract $cacheObj, array &$scope, bool $forceCreate = false);

    public static function connection();

    public static function flush();



}
