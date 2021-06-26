<?php

class ContainerHelperHeaderExtern extends Base
{

    private string $fileTime = '';
    private string $fileHash = '';

    public function __construct(string $fileTime, string $fileHash)
    {
        $this->fileTime = $fileTime;
        $this->fileHash = $fileHash;
    }

    public function checkChanged(): bool
    {
        if (($_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '') === $this->fileTime || trim(($_SERVER['HTTP_IF_NONE_MATCH'] ?? '')) === $this->fileHash) {

            /** @var ContainerFactoryHeader $header */
            $header = Container::getInstance('ContainerFactoryHeader');
            $header->set('#',
                         'HTTP/1.1 304 Not Modified');
            return false;
        }
        else {
            return true;
        }
    }

    public function header(string $contentType, &$contentGZip = false): void
    {
        /** @var ContainerFactoryHeader $header */
        $header = Container::getInstance('ContainerFactoryHeader');

        if ($contentGZip !== false) {
            $header->set('Content-Encoding',
                         'gzip');
            $header->set('Content-Length',
                         strlen($contentGZip));
        }

        $header->set('Last-Modified',
                     $this->fileTime);
        $header->set('ETag',
                     $this->fileHash);

        if ($contentType === 'css') {
            $contentType = 'text/css';
        }
        elseif ($contentType === 'js') {
            $contentType = 'text/javascript';
        }
        elseif ($contentType === 'woff') {
            $contentType = 'application/font-woff';
        }
        elseif ($contentType === 'woff2') {
            $contentType = 'application/font-woff2';
        }
        elseif ($contentType === 'ttf') {
            $contentType = 'text/application/x-font-ttf';
        }
        elseif ($contentType === 'eot') {
            $contentType = 'application/vnd.ms-fontobject';
        }

        $header->set('Content-type',
                     $contentType . '; charset=utf-8');
        $header->set('pragma',
                     'public');
        $header->set('Cache-Control',
                     'public');
        $header->set('Expires',
                     gmdate("D, d M Y H:i:s",
                            time() + 31536000) . " GMT");
        $header->set('Connection',
                     'keep-alive');
        $header->set('keep-alive',
                     'timeout=5, max=100');

        header_remove("Cookie");
        header_remove("Set-Cookie");

    }

}
