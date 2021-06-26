<?php

class ThrirdpartyJavascriptParsley_free extends Base
{

    public function __construct()
    {
        /** @var ContainerFactoryFile $fileTemplate */
        $fileTemplate = Container::get('ContainerFactoryFile',
                                       Core::getRootClass(__CLASS__) . '.min.js.map');

        if ($fileTemplate->exists() === true) {
            $fileTemplate->load();
            echo $fileTemplate->get();
        }
    }

}
