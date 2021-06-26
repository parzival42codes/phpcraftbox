<?php

class ContainerFactoryTemp extends Base
{
    protected string $fileNameTemp = '';

    /**
     */
    public function setName(string $name): void
    {
        $fileName           = (string)Config::get('/cms/path/storage/temporary') . DIRECTORY_SEPARATOR . md5($name) . '.tmp';
        $this->fileNameTemp = $fileName;
    }

    /**
     */
    public function setContent(string $content): void
    {
        Core::checkAndGenerateDirectoryByFilePath($this->fileNameTemp);
        file_put_contents($this->fileNameTemp,
                          $content);
    }

    /**
     */
    public function getContent(): string
    {
        return file_get_contents($this->fileNameTemp);
    }

    public function exists():bool
    {
        return file_exists($this->fileNameTemp);
    }


}
