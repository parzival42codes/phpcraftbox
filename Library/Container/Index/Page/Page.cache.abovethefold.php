<?php

class ContainerIndexPage_cache_abovethefold extends ContainerExtensionCache_abstract
{
    protected string $styleSelected = '';

    public function prepare(): void
    {
        $this->styleSelected = ($this->parameter[0] ?? 'default');

        $this->ident = __CLASS__ . '/' . $this->styleSelected . '/' . $this->styleSelected;

        $this->setPersistent(true);
    }

    public function create(): void
    {
        $this->cacheContent = '';
        /** @var ContainerExternResourcesCss_cache_css $contentCssObj */
        $contentCssObj = Container::get('ContainerExternResourcesCss_cache_css',
                                        false,
                                        $this->styleSelected);

        $this->setCacheContent($contentCssObj->getCacheContent()['contentAboveTheFold']);
    }

}
