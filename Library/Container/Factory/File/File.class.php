<?php

/**
 * Class ContainerFactoryFile
 * @method load() Load the Content
 * @method save() Load the Content
 */

class ContainerFactoryFile extends Base
{
    const FILE_TYPE_UNKNOWN   = 0;
    const FILE_TYPE_JSON      = 1;
    const FILE_TYPE_XML       = 2;
    const FILE_TYPE_PLAIN     = 3;
    const FILE_TYPE_PHP       = 4;
    const FILE_TYPE_DATA      = 5;
    const FILE_TYPE_CSV       = 6;
    const FILE_STATUS_ENCODE  = 1;
    const FILE_STATUS_DECODE  = 2;
    const FILE_STATUS_UNKNOWN = 0;

    const FILE_SOURCE_NOT_FOUND  = 0;
    const FILE_SOURCE_DATABASE   = 1;
    const FILE_SOURCE_LOCAL      = 2;
    const FILE_SOURCE_REPOSITORY = 3;

    protected $filename     = '';
    protected bool         $fileExists   = false;
    protected        $fileContent  = '';
    protected    $fileType     = '';
    protected string       $fileEncoding = '';
    protected int          $fileRights   = 0755;

    /**
     * ContainerFactoryFile constructor.
     *
     * @param $parameter
     * @param bool         $direct
     *
     * @throws DetailedException
     */
    public function __construct($parameter, bool $direct = false,  $filePath = null)
    {
        if ($direct === false) {
            if (is_array($parameter)) {
                $fileNameClass = ($parameter['filename'] ?? null);
            }
            else {
                $fileNameClass = $parameter;
            }

            if ($filePath === null) {
                $filepathSeek = explode(';',
                                        \Config::get('/environment/system/filepath'));

                foreach ($filepathSeek as $filepathSeekItem) {
                    $this->filename = $this->getFileNameFromClass($fileNameClass,
                                                                  $filepathSeekItem);

                    if (file_exists($this->filename)) {
                        $this->fileExists = true;
                        break;
                    }
                }
            }
            else {
                $this->filename = $this->getFileNameFromClass($fileNameClass,
                                                              $filePath);
            }
        }
        else {
            $this->filename = $parameter;

            if (file_exists($this->filename)) {
                $this->fileExists = true;
            }

        }


        $type = substr($this->filename,
            (strrpos($this->filename,
                     '.') + 1));
        switch ($type) {
            case 'json':
                $this->fileType = self::FILE_TYPE_JSON;
                break;
            case 'xml':
                $this->fileType = self::FILE_TYPE_XML;
                break;
            case 'php':
                $this->fileType = self::FILE_TYPE_PHP;
                break;
            case 'txt':
                break;
            case 'data':
                $this->fileType = self::FILE_TYPE_DATA;
                break;
            case 'csv':
                $this->fileType = self::FILE_TYPE_CSV;
                break;
        }

    }

    public static function getFilenameWrap(string $filename): string
    {
        return preg_replace('@([\/\\\.\#])@si',
                            '$1&shy;',
                            \ContainerFactoryFile::getReducedFilename($filename));
    }

    public static function getReducedFilename(string $filename): string
    {
        $filename = str_replace(CMS_PATH_STORAGE,
                                '',
                                $filename);
        return str_replace(CMS_ROOT,
                           '',
                           $filename);
    }

    public function checkAndGenerateDirectoryByFilePath(): void
    {
        \Core::checkAndGenerateDirectoryByFilePath($this->filename);
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * Load the Content
     *
     * @CMSprofilerSet          action load
     * @CMSprofilerSetFromScope filename
     * @CMSprofilerOption       isFunction true
     * @CMSprofilerOption       deph 8
     *
     * @param array $scope
     *
     * @return void
     * @throws DetailedException
     */
    public function _load(array &$scope): void
    {
        //        simpleDebugDump($scope);

        $scope = array_merge($scope,
                             [
                                 'filename' => $this->filename,
                             ]);

        $this->requireFilename();

        if ($this->fileExists === null) {
            $this->exists();
        }

        if ($this->fileExists === true) {
            $this->fileContent = file_get_contents($this->filename);
        }
        else {
            \CoreErrorhandler::trigger(__METHOD__,
                                       'filePathNoFound',
                                       [
                                           'filepath' => $this->filename,
                                       ]);
        }
    }

    private function requireFilename(): void
    {
        if ($this->filename === null) {
            throw new DetailedException('noFileName',
                                        0,
                                        null,
                                        []);
        }
    }

    public function get()
    {
        $this->requireFilename();
        $this->requireContent();

        return $this->fileContent;
    }

    private function requireContent(): void
    {
        if ($this->fileContent === null) {
            throw new DetailedException('noExecuteCode',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'filename' => $this->filename,
                                            ]
                                        ]);

        }
    }

    public function encode(): void
    {
        if ($this->fileEncoding != self::FILE_STATUS_ENCODE) {
            $type = substr($this->filename,
                (strrpos($this->filename,
                         '.') + 1));
            switch ($type) {
                case 'json':
                    $this->fileContent = json_encode($this->fileContent);
                    $this->checkJsonError();
                    break;
                case 'data':
                    $this->fileContent = serialize($this->fileContent);
                    break;
                case 'csv':
                    $this->fileContent = $this->encodeCsv();
                    break;
                case 'php':
                case 'txt':
                    trigger_error('typeHasNoEncode||6|',
                                  E_USER_NOTICE);
                    break;
            }
            $this->fileEncoding = self::FILE_STATUS_ENCODE;
        }
        else {
            \CoreErrorhandler::trigger(__METHOD__,
                                       'decodingEncodeTrue',
                                       [
                                           'filename'     => $this->filename,
                                           'fileEncoding' => $this->fileEncoding,
                                       ]);
        }
    }

    private function checkJsonError(): void
    {
        $errorJson = json_last_error();
        if ($errorJson !== JSON_ERROR_NONE) {
            $jSonErrorData = [];
            $jSonError     = '';

            switch ($errorJson) {
                case JSON_ERROR_DEPTH:
                    $jSonError = 'JSON_ERROR_DEPTH';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $jSonError = 'JSON_ERROR_STATE_MISMATCH';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $jSonError = 'JSON_ERROR_CTRL_CHAR';
                    break;
                case JSON_ERROR_SYNTAX:
                    $jSonErrorData = [
                        'filepath' => $this->filename,
                        'content'  => $this->fileContent,
                    ];
                    $jSonError     = 'JSON_ERROR_SYNTAX';
                    break;
                case JSON_ERROR_UTF8:
                    $jSonError = 'JSON_ERROR_UTF8';
                    break;
                case JSON_ERROR_RECURSION:
                    $jSonError = 'JSON_ERROR_RECURSION';
                    break;
                case JSON_ERROR_INF_OR_NAN:
                    $jSonError = 'JSON_ERROR_INF_OR_NAN';
                    break;
                case JSON_ERROR_UNSUPPORTED_TYPE:
                    $jSonError = 'JSON_ERROR_UNSUPPORTED_TYPE';
                    break;
            }

            \CoreErrorhandler::trigger(__METHOD__,
                                       'jsonDecodeError',
                                       [
                                           'error' => $jSonError,
                                           'data'  => $jSonErrorData,
                                       ]);
        }
    }

    protected function encodeCsv(): array
    {
        $csvKeys       = array_keys(reset($this->fileContent));
        $csvFileData   = [];
        $csvFileData[] = '"' . implode('";"',
                                       $csvKeys) . '"';


        foreach ($this->fileContent as $fileContentItem) {
            $csvFileData[] = '"' . implode('";"',
                                           $fileContentItem) . '"';
        }

        return $csvFileData;

    }

    public function decode(): void
    {
        if ($this->fileEncoding != self::FILE_STATUS_DECODE) {
            $type = substr($this->filename,
                (strrpos($this->filename,
                         '.') + 1));

            switch ($type) {
                case 'json':
                    $this->fileContent = json_decode($this->fileContent,
                                                     true);
                    $this->checkJsonError();
                    break;
                case 'data':
                    $this->fileContent = unserialize($this->fileContent);
                    break;
                case 'ini':
                    $this->fileContent = $iniData = parse_ini_string($this->fileContent,
                                                                     true);
                    break;
                case 'csv':

                    $csvData          = [];
                    $csvDataContainer = explode("\n",
                                                $this->fileContent);
                    array_shift($csvDataContainer);
                    foreach ($csvDataContainer as $fileContentItem) {
                        $csvDataContainer = str_getcsv($fileContentItem);
                        if ($csvDataContainer[0] !== null) {
                            ;
                            array_walk($csvDataContainer,
                                       [
                                           $this,
                                           'csvTrimWalk'
                                       ]);
                            $csvData[] = $csvDataContainer;
                        }
                    }
                    $this->fileContent = $csvData;
                    break;
                case 'php':
                    trigger_error('typeHasNoDecode||6|',
                                  E_USER_NOTICE);
                    break;
                case 'txt':
                    trigger_error('typeHasNoDecode||6|',
                                  E_USER_NOTICE);
                    break;
            }
            $this->fileEncoding = self::FILE_STATUS_DECODE;
        }
        else {
            \CoreErrorhandler::trigger(__METHOD__,
                                       'decodingDecodeTrue',
                                       [
                                           'filename'     => $this->filename,
                                           'fileEncoding' => $this->fileEncoding,
                                       ]);
        }
    }

    public function set($content): void
    {
        $this->requireFilename();
        $this->fileContent = $content;
    }

    /**
     * Save the Content.
     *
     * @CMSprofilerSet          action save
     * @CMSprofilerSetFromScope filename
     * @CMSprofilerOption       isFunction true
     * @CMSprofilerOption       deph 1
     *
     * @param array $scope
     * @param bool  $fileAppend
     *
     * @return void
     * @throws DetailedException
     */
    public function _save(array &$scope, bool $fileAppend = false): void
    {
        $scope = array_merge($scope,
                             [
                                 'filename' => $this->filename,
                             ]);

        $this->requireFilename();

        $this->fileExists = true;
        if ($fileAppend === false) {
            file_put_contents($this->filename,
                              $this->fileContent);
        }
        else {
            file_put_contents($this->filename,
                              $this->fileContent,
                              FILE_APPEND);
        }
    }

    public function exists(): bool
    {
        $this->requireFilename();

        if (is_file($this->filename) === true) {
            $this->fileExists = true;
        }
        else {
            $this->fileExists = false;
        }

        return $this->fileExists;
    }

    /**
     * @return int
     */
    public function getFileRights(): int
    {
        return $this->fileRights;
    }

    /**
     * @param int $fileRights
     */
    public function setFileRights(int $fileRights): void
    {
        if (
            strpos($this->filename,
                   CMS_PATH_STORAGE_CACHE) !== 0
        ) {
            throw new DetailedException('onlyInStorageCache');
        }

        $this->fileRights = $fileRights;

        if (is_file($this->filename)) {
            chmod($this->filename,
                  $this->fileRights);
        }
    }

    protected function csvTrimWalk(string &$item): string
    {
        $item = trim($item);
    }


    protected function getFileNameFromClass(string $class, string $alternatePath = ''): string
    {

        $class = str_replace('.',
                             '_',
                             $class);

        $classExtension = explode('_',
                                  $class,
                                  2);

        $classSplit = preg_split('/([A-Z]*[a-z^_]*)/',
                                 $classExtension[0],
                                 -1,
                                 PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $classFilePath = implode('/',
                                 $classSplit);

        $classSplitLast = end($classSplit);

        $classExtensionFragmentsFileName = '.' . str_replace('_',
                                                             '.',
                                                             $classExtension[1]);

        return CMS_ROOT . $alternatePath . $classFilePath . '/' . $classSplitLast . $classExtensionFragmentsFileName;
    }


}
