<?php

class ApplicationIndex_app extends Application_abstract
{

    public function setContent(array ...$parameter): string
    {
        /** @var ContainerIndexPageBox $pageBox */
        $pageBox    = Container::get('ContainerIndexPageBox');
        return $pageBox->get('index');
    }

}
