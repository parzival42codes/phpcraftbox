<?php

class ContainerFactoryZip extends Base
{

    protected string     $filePath                 = '';
    protected string     $filePathDirectory        = '';
    protected      $filePathDirectoryExtract = '';
    protected ZipArchive $fileZip;

    public function __construct(string $filePath, string $filePathDirectoryExtract = '')
    {
        $this->filePath                 = $filePath;
        $this->filePathDirectory        = dirname($filePath);
        $this->filePathDirectoryExtract = (($filePathDirectoryExtract === '') ? $this->filePathDirectory : $filePathDirectoryExtract);
        $this->fileZip                  = new ZipArchive;

        $fileOpenStatus = $this->fileZip->open($this->filePath);

        switch ($fileOpenStatus) {
            case ZipArchive::ER_EXISTS:
                $errorMessage = ContainerFactoryLanguage::get('/ContainerFactoryZip/errorcodes/open/ER_EXISTS');
                break;
            case ZipArchive::ER_INCONS:
                $errorMessage = ContainerFactoryLanguage::get('/ContainerFactoryZip/errorcodes/open/ER_INCONS');
                break;
            case ZipArchive::ER_INVAL:
                $errorMessage = ContainerFactoryLanguage::get('/ContainerFactoryZip/errorcodes/open/ER_INVAL');
                break;
            case ZipArchive::ER_MEMORY:
                $errorMessage = ContainerFactoryLanguage::get('/ContainerFactoryZip/errorcodes/open/ER_MEMORY');
                break;
            case ZipArchive::ER_NOENT:
                $errorMessage = ContainerFactoryLanguage::get('/ContainerFactoryZip/errorcodes/open/ER_NOENT');
                break;
            case ZipArchive::ER_NOZIP:
                $errorMessage = ContainerFactoryLanguage::get('/ContainerFactoryZip/errorcodes/open/ER_NOZIP');
                break;
            case ZipArchive::ER_OPEN:
                $errorMessage = ContainerFactoryLanguage::get('/ContainerFactoryZip/errorcodes/open/ER_OPEN');
                break;
            case ZipArchive::ER_READ:
                $errorMessage = ContainerFactoryLanguage::get('/ContainerFactoryZip/errorcodes/open/ER_READ');
                break;
            case ZipArchive::ER_SEEK:
                $errorMessage = ContainerFactoryLanguage::get('/ContainerFactoryZip/errorcodes/open/ER_SEEK');
                break;
            default:
                $errorMessage = $fileOpenStatus;
                break;
        }

        if ($fileOpenStatus !== true) {
            throw new DetailedException('zipFileErrorOpen',
                                        0,
                                        null,
                                        [
                                            'path'    => $this->filePath,
                                            'message' => $errorMessage,
                                        ],
                                        0);
        }
    }

    public function close(): void
    {
        $this->fileZip->close();
    }

    public function extract(array $filesExtract): void
    {
        foreach ($filesExtract as $filesExtractKey => $filesExtractValue) {
            if (!is_file($this->filePathDirectoryExtract . DIRECTORY_SEPARATOR . $filesExtractValue)) {
                $fileObject = Container::get('ContainerFactoryFile',
                                             $this->filePathDirectoryExtract . DIRECTORY_SEPARATOR . $filesExtractValue);
                $fileObject->checkAndGenerateDirectoryByFilePath();
                file_put_contents($this->filePathDirectoryExtract . DIRECTORY_SEPARATOR . $filesExtractValue,
                                  $this->fileZip->getFromName($filesExtractKey));
            }
        }
    }

    public function extractDirectory(): void
    {

        d($this->filePathDirectoryExtract);
        d(is_dir($this->filePathDirectoryExtract));

        if (!is_dir($this->filePathDirectoryExtract)) {

            $index = [];
            for ($i = 0; $i < $this->fileZip->numFiles; $i++) {
                $filename = $this->fileZip->getNameIndex($i);
                d($filename);
                $index[$i] = $filename;
//                if (strpos($filename, $directoryExtractSource) !== false) {
//                    $index[$i] = $filename;
//                }
            }

            d($index);

            foreach ($index as $indexKey => $indexValue) {
                $pathExtract = $this->filePathDirectoryExtract . DIRECTORY_SEPARATOR . $indexValue;

//                $pathExtract = strtr($targetDir . DIRECTORY_SEPARATOR . $indexValue, [
//                    $directoryExtractSource => ''
//                ]);

                Core::checkAndGenerateDirectoryByFilePath($pathExtract);
                file_put_contents($pathExtract,
                                  $this->fileZip->getFromIndex($indexKey));
            }
        }

        eol();
    }

    public function extractAll(): void
    {

    }

    public function extractSingle(): void
    {

    }

}
