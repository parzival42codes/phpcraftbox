<?php

class Core
{
    protected static array $rootClassCache
        = [
            'TemplateArrayStack' => [],
            'TemplateArray'      => [],
        ];

    private static array $convertClassDocBlockTags
        = [
            '@modul',
            '@database',
            '@CMSprofilerOption',
            '@CMSprofilerSet',
            '@CMSprofilerSetFromScope',
            '@DependencyInjection',
        ];

    public static function identifyFileClass(string $filePath): string
    {
        $pathLibraryFilename = str_replace(CMS_PATH_LIBRARY,
                                           '',
                                           $filePath);
        $pathLibraryFilename = str_replace(CMS_PATH_CUSTOM_REPOSITORY,
                                           '',
                                           $pathLibraryFilename);
        $pathLibraryFilename = str_replace(CMS_PATH_CUSTOM_LOCAL,
                                           '',
                                           $pathLibraryFilename);

        if ($pathLibraryFilename === '') {
            throw new DetailedException('identifyFileClassFail',
                                        0,
                                        null,
                                        [
                                            'filePath' => $filePath,
                                        ],
                                        1);
        }

        $filePathExplode = explode(DIRECTORY_SEPARATOR,
                                   $pathLibraryFilename);

        $filename             = array_pop($filePathExplode);
        $setClassExplode      = explode('.',
                                        $filename);
        $filePathExplodeLast  = array_pop($filePathExplode);
        $setClassExplodeFirst = array_shift($setClassExplode);
        $setClassExplodeLast  = array_pop($setClassExplode);

        if (isset($setClassExplode[0]) && $setClassExplode[0] == 'class') {
            if (!isset($setClassExplode[1])) {
                unset($setClassExplode[0]);
            }
        }

        $classPath = implode('',
                             $filePathExplode) . (($filePathExplodeLast === $setClassExplodeFirst) ? '' : ucfirst($filePathExplodeLast)) . ucfirst($setClassExplodeFirst) . (count($setClassExplode) > 0 ? '_' . strtolower(implode('_',
                                                                                                                                                                                                                                    $setClassExplode)) : '') . (($setClassExplodeLast === 'php') ? '' : '_' . $setClassExplodeLast);

        $classPath = strtr($classPath,
                           [
                               '-' => '_',
                               '.' => '_',
                           ]);

        return $classPath;
    }

    public static function getParentClass(string $class, int $level = 1): string
    {
        $class      = self::getRootClass($class);
        $classSplit = preg_split('/([A-Z]*[a-z]*)/',
                                 $class,
                                 -1,
                                 PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        for ($i = 1; $i <= $level; $i++) {
            array_pop($classSplit);
        }

        return implode('',
                       $classSplit);
    }

    public static function getRootClass(string $class): string
    {
        if (!isset(self::$rootClassCache[$class])) {
            $pos                          = strpos($class,
                                                   '_');
            self::$rootClassCache[$class] = (($pos === false) ? $class : substr($class,
                                                                                0,
                ($pos)));
        }
        return self::$rootClassCache[$class];
    }

    public static function convertClassDocBlock(string $docBlock): array
    {

        $docData = [
            'title'       => '',
            'description' => '',
        ];

        preg_match_all('@[\*]{1}(.*)@i',
                       $docBlock,
                       $matches);
        $matchesComments = $matches[1];

        $matchesComments = array_filter($matchesComments,
            function ($var) {
                if (
                    empty($var) || strpos($var,
                                          '*') === 0 || strpos($var,
                                                               '/') === 0
                ) {
                    return false;
                }
                return true;
            });

        array_walk($matchesComments,
            function (&$item) {
                $item = trim($item);
            });

        $docData['title']       = '';
        $docData['description'] = '';
        $getDescriptionFromDoc  = true;

        $commentData = [];
        foreach ($matchesComments as $matchesCommentsItem) {
            $matchesCommentsItemExplode = explode(' ',
                                                  trim($matchesCommentsItem),
                                                  2);

            $matchesCommentsItemExplodeFirst = trim($matchesCommentsItemExplode[0]);

            $matchesCommentsItemExplodeData = explode(' ',
                                                      trim($matchesCommentsItemExplode[1] ?? ''),
                                                      2);

            if (
                strpos($matchesCommentsItemExplodeFirst,
                       '@') === 0
            ) {
                if (
                in_array($matchesCommentsItemExplodeFirst,
                         self::$convertClassDocBlockTags)
                ) {
                    $commentData[$matchesCommentsItemExplodeFirst][($matchesCommentsItemExplodeData[0] ?? '')] = ($matchesCommentsItemExplodeData[1] ?? '');
                    $getDescriptionFromDoc                                                                     = false;
                }
                else {
                    $commentData[$matchesCommentsItemExplodeFirst] = trim(($matchesCommentsItemExplode[1] ?? ''));
                }

            }
            else {
                if ($docData['title'] === '') {
                    $docData['title'] = $matchesCommentsItem;
                }
                else {
                    if ($getDescriptionFromDoc === true) {
                        $docData['description'] .= $matchesCommentsItem . PHP_EOL;
                    }
                }
            }
        }

        unset($commentData['_']);
        $docData['paramData'] = $commentData;

        return $docData;
    }

    public static function getDiffSecoundsFromDatetime(?DateTime $dateTime1, ?DateTime $dateTime2 = null): int
    {
        if ($dateTime2 === null) {
            $dateTime2 = new \DateTime();
        }
        else {
            $dateTime2 = new \DateTime($dateTime2);
        }

        $dateTime1    = new \DateTime($dateTime1);
        $datetimeDiff = $dateTime2->diff($dateTime1);

        $secounds = $datetimeDiff->s + ($datetimeDiff->i * 60) + ($datetimeDiff->h * 3600) + ($datetimeDiff->d * 86400) + ($datetimeDiff->m * 2592000) + ($datetimeDiff->y * 31536000);

        return $secounds;
    }

    public static function checkAndGenerateDirectoryByFilePath(string $filename): void
    {
        $thisPath = strtr(dirname($filename),
                          [
                              '\\' => DIRECTORY_SEPARATOR,
                              '/'  => DIRECTORY_SEPARATOR,
                          ]);

        if (!is_dir($thisPath)) {
            $filePathExplode  = explode(DIRECTORY_SEPARATOR,
                                        $thisPath);
            $fileGeneratePath = array_shift($filePathExplode);

            foreach ($filePathExplode as $value) {
                $thisPath = $fileGeneratePath . DIRECTORY_SEPARATOR . $value;

                if (!is_dir($thisPath)) {
                    mkdir($thisPath,
                          0755);
                }
                $fileGeneratePath .= DIRECTORY_SEPARATOR . $value;
            }
        }
    }

    public static function getReducedFilename(string $filename): string
    {
        $filename = str_replace(CMS_PATH_STORAGE,
                                '',
                                $filename);
        $filename = str_replace(CMS_ROOT,
                                '',
                                $filename);
        return $filename;
    }

    public static function getClassFileName(string $class, bool $exception = false): ?string
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
        $filepathSeek                    = explode(';',
                                                   \Config::get('/environment/system/filepath'));

        $filePathCollect = [];
        foreach ($filepathSeek as $filePath) {
            $filepathSeekCheck = Config::get('/cms/path/root') . $filePath . $classFilePath . '/' . $classSplitLast . $classExtensionFragmentsFileName;

            if (is_file($filepathSeekCheck)) {
                return $filepathSeekCheck;
            }

            $filePathCollect[] = $filepathSeekCheck;

        }

        if ($exception === false) {
            return null;
        }
        else {

            throw new DetailedException('fileNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'filePathSeek' => implode(' # ',
                                                                          $filePathCollect),
                                                'class'        => $class,
                                                'root'         => Config::get('/cms/path/root'),
                                            ]
                                        ]);
        }
    }

    public static function getPHPClassFileName(string $class, bool $exception = false): ?string
    {
        $classExtension = explode('_',
                                  $class,
                                  2);

        $classSplit = preg_split('/([A-Z]*[0-9a-z^_]*)/',
                                 $classExtension[0],
                                 -1,
                                 PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $classFilePath = implode('/',
                                 $classSplit);

        $classSplitLast = end($classSplit);

        $classExtensionFragmentsFileName = '.class';
        if (isset($classExtension[1]) && !empty($classExtension[1])) {
            $classExtensionFragmentsFileName = '.' . str_replace('_',
                                                                 '.',
                                                                 $classExtension[1]);
        }

        $filepathSeek = explode(';',
                                \Config::get('/environment/system/filepath'));

        $filePathCollect = [];
        foreach ($filepathSeek as $filePath) {
            $filepathSeekCheck = CMS_ROOT . $filePath . $classFilePath . '/' . $classSplitLast . $classExtensionFragmentsFileName . '.php';

            if (is_file($filepathSeekCheck)) {
                return $filepathSeekCheck;
            }

            $filePathCollect[] = $filepathSeekCheck;

        }

        if ($exception === false) {
            return null;
        }
        else {

            throw new DetailedException('fileNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'filePathSeek' => implode(' # ',
                                                                          $filePathCollect),
                                                'class'        => $class,
                                                'root'         => Config::get('/cms/path/root'),
                                            ]
                                        ]);
        }
    }

}
