<?php

class ContainerExternResourcesJavascript extends Base
{

    /**
     * @return string
     * @throws DetailedException
     */
    public function get(): string
    {
        $chunks = 5;

        /** @var ContainerExternResourcesJavascript_cache_js $jsCache */
        $jsCache = Container::get('ContainerExternResourcesJavascript_cache_js');

        $return = $jsCache->get();

        return implode('',
                       $return) . PHP_EOL . PHP_EOL . ';parseOnLoad();';
    }
}
