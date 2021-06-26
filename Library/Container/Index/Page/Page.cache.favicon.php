<?php

class ContainerIndexPage_cache_favicon extends ContainerExtensionCache_abstract
{

    protected int $ttl = 3600;

    public function prepare(): void
    {
        $this->ident = __CLASS__ . 'FavIcon';
        $this->setPersistent(true);
    }

    public function create(): void
    {
        $file = Container::get('ContainerFactoryFile',
                                [
                                    'filename' => CMS_ROOT . 'favicon' . ((isset($this->parameter[0]) === false || empty($this->parameter[0]) === true) ? '' : '_' . $this->parameter[0]) . '.png',
                                    'fromList' => false
                                ]);

        if ($file->exists() === true) {
            $this->cacheContent = base64_encode($file->load()
                                                     ->get());
        }
        else {
            $this->cacheContent = 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII=';
        }

    }


}
