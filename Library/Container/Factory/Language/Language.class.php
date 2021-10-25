<?php

/**
 * Language
 *
 * Language
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    language_path_de_DE /Kernfunktion
 * @modul    language_name_de_DE Sprache
 * @modul    language_path_en_US /Core Function
 * @modul    language_name_en_US Language
 */

class ContainerFactoryLanguage extends Base_abstract_keyvalue
{
    protected static array $registry = [];
    protected string       $prefix   = 'language';

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public static function getAll(): array
    {
        return self::$registry;
    }

    public static function setCore(): void
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('language');
        $query->select('crudClass',
                       'crudLanguageKey',
                       'crudLanguageValue');
        $query->select('crudLanguageLanguage');

        $query->construct();
        $smtp = $query->execute();

        $doubleCheck = [];

        $count = 0;
        while ($smtpData = $smtp->fetch()) {
            $count++;
            self::$registry['/' . $smtpData['crudClass'] . $smtpData['crudLanguageKey']][$smtpData['crudLanguageLanguage']] = $smtpData['crudLanguageValue'];
            $doubleCheck['/' . $smtpData['crudClass'] . $smtpData['crudLanguageKey'] . $smtpData['crudLanguageLanguage']]   = true;
        }

//        $cacheContent    = new ContainerFactoryLanguage_cache_entries();
//        $cacheContentGet = $cacheContent->get();
//
//        if (!empty($cacheContentGet)) {
//            self::$registry = $cacheContent->get();
//        }

//        d($count);
//        d($doubleCheck);
//        d(self::$registry);
//        eol();

    }

    public static function get(string $path, $alternative = null): ?string
    {
        if (isset(self::$registry[$path]) === true) {
            if (isset(self::$registry[$path][Config::get('/environment/language')])) {
                return self::$registry[$path][Config::get('/environment/language')];
            }
            else {
                return reset(self::$registry[$path]);
            }
        }

        $pathClass = explode('/',
                             $path,
                             3);

        array_shift($pathClass);
        $class = array_shift($pathClass);

        $language = self::getLanguage($class,
                                      $pathClass[0]);

        if ($language !== null) {
            return $language;
        }

        if ($alternative !== null) {
            if (is_array($alternative)) {

                $pathExtracted = explode('/',
                                         $path,
                                         3);

                if (isset($alternative[Config::get('/environment/language')])) {
                    /** @var ContainerFactoryLanguage_crud $crud */
                    $crud = Container::get("ContainerFactoryLanguage_crud");
                    $crud->setCrudClass(Core::getRootClass($pathExtracted[1]));
                    $crud->setCrudLanguageLanguage(Config::get('/environment/language'));
                    $crud->setCrudLanguageKey('/' . $pathExtracted[2]);
                    $crud->setCrudLanguageValue($alternative[Config::get('/environment/language')]);
                    $crud->setCrudLanguageValueDefault($alternative[Config::get('/environment/language')]);

                    $crud->insert(true);

                    return $alternative[Config::get('/environment/language')];

                }
            }
            else {
                return $alternative;
            }
        }

        \CoreErrorhandler::trigger(__METHOD__,
                                   'languageKeyNotFound',
                                   [
                                       'path'     => $path,
                                       'language' => '<details><summary>Language List</summary><pre>' . var_export(array_keys(self::$registry),
                                                                                                                   true) . '</pre></details>',
                                   ]);
        return null;
    }

    public static function set(string $path, string $value): void
    {
        self::$registry[$path][Config::get('/environment/language')] = $value;
    }

    public static function getLanguageText($languageContainer, string $language = null)
    {
        if ($language === null) {
            $language = (string)Config::get('/environment/language');
        }

        if (is_string($languageContainer)) {
            $languageContainerEncode = json_decode($languageContainer,
                                                   true);
            if ($languageContainerEncode === null) {
                throw new DetailedException('languageTextJsonError',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    json_last_error_msg(),
                                                    $languageContainer,
                                                ]
                                            ]);
            }

            $languageContainer = $languageContainerEncode;

        }

        if (isset($languageContainer[$language])) {
            return $languageContainer[$language];
        }
        else {
            return reset($languageContainer);
        }
    }

    public static function getLanguage(string $rootClass, string $path = ''): ?string
    {
        $fileConfigImport = new ContainerFactoryFile($rootClass . '.install.language.json');
        if ($fileConfigImport->exists()) {

            $fileConfigImport->load();
            $fileConfigImport->decode();
            $language = $fileConfigImport->get();

            $pathName = '/' . $path;

            if (isset($language['content'][$pathName]['language'])) {
                $languageText = self::getLanguageText($language['content'][$pathName]['language']);

                if (isset($languageText['value'])) {
                    $languageText = $languageText['value'];
                }

                if ($languageText !== null) {
                    /** @var ContainerFactoryLanguage_crud $crud */
                    $crud = Container::get("ContainerFactoryLanguage_crud");
                    $crud->setCrudClass($rootClass);
                    $crud->setCrudLanguageLanguage(Config::get('/environment/language'));
                    $crud->setCrudLanguageKey($pathName);
                    $crud->setCrudLanguageValue($languageText);
                    $crud->setCrudLanguageValueDefault($languageText);

                    $crud->insert(true);

                    return $languageText;
                }

                return null;
            }
            elseif (isset($language['content'][$pathName])) {
                $languageText = self::getLanguageText($language['content'][$pathName]);

                if ($languageText !== null) {
                    /** @var ContainerFactoryLanguage_crud $crud */
                    $crud = Container::get("ContainerFactoryLanguage_crud");
                    $crud->setCrudClass($rootClass);
                    $crud->setCrudLanguageLanguage(Config::get('/environment/language'));
                    $crud->setCrudLanguageKey($pathName);
                    $crud->setCrudLanguageValue($languageText);
                    $crud->setCrudLanguageValueDefault($languageText);

                    $crud->insert(true);

                    return $languageText;
                }
            }
        }

        return null;
    }
}
