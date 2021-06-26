<?php

class ContainerExternResourcesCss_cache_init extends ContainerExtensionCache_abstract
{
    protected string $styleSelected = 'default';

    public function prepare(): void
    {
        $this->styleSelected = ($this->parameter[0] ?? 'default');

        $this->ident = __CLASS__ . '/' . $this->styleSelected;

        $this->setPersistent(true);
    }

    public function create(): void
    {
        /** @var ContainerFactoryFile $file */
        $file = Container::get('ContainerFactoryFile',
                               ['filename' => 'Style_style_format_css']);

        if ($file->exists() === true) {
            $file->load();
            $this->cacheContent .= $file->get();
        }

        /** @var ContainerFactoryFile $file */
        $file = Container::get('ContainerFactoryFile',
                               ['filename' => 'Style' . ucfirst($this->styleSelected) . '_style_main_css']);

        if ($file->exists() === true) {
            $file->load();
            $this->cacheContent .= $file->get();
        }

        /** @var ContainerFactoryFile $file */
        $file = Container::get('ContainerFactoryFile',
                               ['filename' => 'Style_style_init_css']);

        if ($file->exists() === true) {
            $file->load();
            $this->cacheContent .= $file->get();
        }

    }

}
