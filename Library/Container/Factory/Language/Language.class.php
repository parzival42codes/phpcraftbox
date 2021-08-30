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

        /** @var ePDOStatement $smtp */

        while ($smtpData = $smtp->fetch()) {
            self::$registry['/' . $smtpData['crudClass'] . $smtpData['crudLanguageKey']][$smtpData['crudLanguageLanguage']] = $smtpData['crudLanguageValue'];
        }

//        d(self::$registry);
//        eol();

    }

    public static function get(string $path, $alternative = null): string
    {
        if (isset(self::$registry[$path]) === true) {
            if (isset(self::$registry[$path][Config::get('/environment/language')])) {
                return self::$registry[$path][Config::get('/environment/language')];
            }
            else {
                return reset(self::$registry[$path]);
            }
        }
        elseif ($alternative !== null) {
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

                    $crud->insertUpdate();

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
        return '';
    }

    public static function set(string $path, string $value): void
    {
        self::$registry[$path][Config::get('/environment/language')] = $value;
    }

    public static function getLanguageText(array $languageContainer, string $language = null): string
    {
        if ($language === null) {
            $language = (string)Config::get('/environment/language');
        }

        if (isset($languageContainer[$language])) {
            return $languageContainer[$language];
        }
        else {
            return reset($languageContainer);
        }
    }
}
