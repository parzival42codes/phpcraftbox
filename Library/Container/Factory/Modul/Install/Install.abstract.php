<?php

abstract class ContainerFactoryModulInstall_abstract extends Base
{
    protected Console_abstract $console;
    protected int              $progressPartCounter = 1;

    const DOCUMENTATION_TYPE_CODE   = 'code';
    const DOCUMENTATION_TYPE_WIDGET = 'widget';

    public function __construct($console)
    {
        /** @var Console_abstract $console */
        $this->console = $console;
    }

    abstract public function install(): void;

    public function uninstall(): void
    {
        $this->removeStdEntities();
    }

    public function refresh(): void
    {
    }

    public function update(): void
    {
    }

    public function activate(): void
    {
        $this->importRoute();
        $this->importMenu();
    }

    public function deactivate(): void
    {
        $this->removeStdEntitiesDeactivate();
    }

    public function repair(): void
    {
    }

    public function queryDatabase(array $queryDatabase): void
    {
        foreach ($queryDatabase as $databaseQueryItem) {
            $this->installFunction(function () {
                /** @var array $data */ /*$before*/
                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __METHOD__,
                                        true,
                                        ContainerFactoryDatabaseQuery::MODE_OTHER,
                                        false);

                $query->query($data['queryString']);
                $query->construct();
                $query->execute();

                $progressData['message'] = $data['queryString'];

                /*$after*/
            },
                [
                    'queryString' => $databaseQueryItem
                ]);
        }
    }

    public function importQueryDatabaseFromCrud(string $class): void
    {
        /** @var Base_abstract_crud $crud */
        $crud = Container::get($class);
//        $crud->truncate();

        $queryDatabase = $crud->getInstallUpdateQuery();

        foreach ($queryDatabase as $databaseQueryItem) {
            $this->installFunction(function () {

                /** @var array $data */ /*$before*/
                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __METHOD__,
                                        true,
                                        ContainerFactoryDatabaseQuery::MODE_OTHER,
                                        false);

                $query->query($data['queryString']);
                $query->construct();
                $query->execute();

                $progressData['message'] = $data['queryString'];

                /*$after*/
            },
                [
                    'queryString' => $databaseQueryItem
                ]);
        }
    }

    public function importLanguage(): void
    {

        $rootClass = Core::getRootClass(get_called_class());
        /** @var ContainerFactoryFile $fileLanguageImport */
        $fileLanguageImport = Container::get('ContainerFactoryFile',
                                             $rootClass . '_install_language_json');

        if (!$fileLanguageImport->exists()) {
//                d($fileLanguageImport);
            throw new DetailedException('languageInstallFileNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'class' => $rootClass,
                                            ]
                                        ]);
        }

        $fileLanguageImport->load();
        $fileLanguageImport->decode();
        $list = $fileLanguageImport->get();

        foreach ($list['content'] as $csvKey => $csvContainer) {

            if (isset($csvContainer['language'])) {

                foreach ($csvContainer['language'] as $csvLanguage => $csvData) {
                    if (is_array($csvData)) {
                        $csvItem              = $csvData;
                        $csvItem['rootClass'] = $rootClass;
                        $csvItem['key']       = $csvKey;
                        $csvItem['language']  = $csvLanguage;
                    }
                    else {
                        $csvItem = [
                            'rootClass' => $rootClass,
                            'key'       => $csvKey,
                            'language'  => $csvLanguage,
                            'value'     => $csvData,
                        ];
                    }

                    $this->installFunction(function () {
                        /** @var array $data */ /*$before*/

                        /** @var ContainerFactoryLanguage_crud $crud */
                        $crud = Container::get("ContainerFactoryLanguage_crud");
                        $crud->setCrudClass($data['crud']["rootClass"]);
                        $crud->setCrudLanguageLanguage($data['crud']["language"]);
                        $crud->setCrudLanguageKey($data['crud']["key"]);
                        $crud->setCrudLanguageValue($data['crud']["value"]);
                        $crud->setCrudLanguageValueDefault($data['crud']["valueDefault"] ?? $data['crud']["value"]);

                        $progressData["message"] = $crud->insert() . " |##|blue";

                        /*$after*/
                    },
                        [
                            'crud' => $csvItem
                        ]);
                }
            }
            else {
                foreach ($csvContainer as $csvLanguage => $csvData) {
                    if (is_array($csvData)) {
                        $csvItem              = $csvData;
                        $csvItem['rootClass'] = $rootClass;
                        $csvItem['key']       = $csvKey;
                        $csvItem['language']  = $csvLanguage;
                    }
                    else {
                        $csvItem = [
                            'rootClass' => $rootClass,
                            'key'       => $csvKey,
                            'language'  => $csvLanguage,
                            'value'     => $csvData,
                        ];
                    }

                    $this->installFunction(function () {
                        /** @var array $data */ /*$before*/

                        /** @var ContainerFactoryLanguage_crud $crud */
                        $crud = Container::get("ContainerFactoryLanguage_crud");
                        $crud->setCrudClass($data['crud']["rootClass"]);
                        $crud->setCrudLanguageLanguage($data['crud']["language"]);
                        $crud->setCrudLanguageKey($data['crud']["key"]);
                        $crud->setCrudLanguageValue($data['crud']["value"]);
                        $crud->setCrudLanguageValueDefault($data['crud']["valueDefault"] ?? $data['crud']["value"]);

                        $progressData["message"] = $crud->insert() . " |##|blue";

                        /*$after*/
                    },
                        [
                            'crud' => $csvItem
                        ]);
                }
            }
        }

    }

    public function readLanguageFromFile(string $filename): void
    {
//        {insert/language class="{$_selfClass}" path="/form/tag"}

        $rootClass        = Core::getRootClass(get_called_class());
        $languageTemplate = Container::get('ContainerFactoryFile',
                                           $rootClass . '.template.' . $filename . '.tpl');

        if (!$languageTemplate->exists()) {
//                d($fileLanguageImport);
            throw new DetailedException('languageTemplateNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'class' => $rootClass,
                                            ]
                                        ]);
        }

        $languageTemplate->load();

        preg_match_all('@\{insert\/language(.*?)\}@si',
                       $languageTemplate->get(),
                       $matches,
                       PREG_PATTERN_ORDER);

        foreach ($matches[1] as $match) {
            preg_match_all('@(.*?)="(.*?)"@si',
                           $match,
                           $matchLanguage,
                           PREG_SET_ORDER);

            $languageCollect      = [];
            $languageCollectValue = [];
            foreach ($matchLanguage as $item) {
                $key = trim($item[1]);

                if (
                    strpos($key,
                           'language') === false
                ) {
                    $languageCollect[$key] = trim($item[2]);
                }
                else {
                    $keyLanguage                           = explode('-',
                                                                     $key,
                                                                     2);
                    $languageCollectValue[$keyLanguage[1]] = $item[2];
                }

            }

            if (isset($languageCollect['class']) && isset($languageCollect['path']) && isset($languageCollect['class'])) {

                foreach ($languageCollectValue as $languageCollectKey => $languageCollectValueItem) {

                    $this->installFunction(function () {
                        /** @var array $data */ /*$before*/

                        /** @var ContainerFactoryLanguage_crud $crud */
                        $crud = Container::get("ContainerFactoryLanguage_crud");
                        $crud->setCrudClass($data['crud']["rootClass"]);
                        $crud->setCrudLanguageLanguage($data['crud']["language"]);
                        $crud->setCrudLanguageKey($data['crud']["key"]);
                        $crud->setCrudLanguageValue($data['crud']["value"]);
                        $crud->setCrudLanguageValueDefault($data['crud']["valueDefault"] ?? $data['crud']["value"]);

                        $progressData["message"] = $crud->insert() . " |##|blue";

                        /*$after*/
                    },
                        [
                            'crud' => [
                                'rootClass' => $languageCollect['class'],
                                'key'       => $languageCollect['path'],
                                'value'     => $languageCollectValueItem,
                                'group'     => ($languageCollect['import'] ?? ''),
                                'language'  => $languageCollectKey,
                            ]
                        ]);
                }
            }


        }

    }

    public function importConfig(): void
    {

        $rootClass        = Core::getRootClass(get_called_class());
        $fileConfigImport = Container::get('ContainerFactoryFile',
                                           [
                                               'filename' => $rootClass . '.install.config.json',
                                           ]);
        if (!$fileConfigImport->exists()) {
            throw new DetailedException('languageConfigFileNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'class' => $rootClass,
                                            ]
                                        ]);
        }

        $fileConfigImport->load();
        $fileConfigImport->decode();
        $list = $fileConfigImport->get();

        if (isset($list['content']) === false || is_array($list['content']) === false) {
//                d($fileLanguageImport);
            throw new DetailedException('configEmpty',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'class' => $rootClass,
                                            ]
                                        ]);
        }

        foreach ($list['content'] as $configKey => $configData) {
            $configData['rootClass'] = $rootClass;
            $configData['key']       = $configKey;

            $this->installFunction(function () {
                /** @var array $data */ /*$before*/

                /** @var Config_crud $configCrud */
                $configCrud = Container::get('Config_crud');
                $configCrud->setCrudClass($data['crud']['rootClass']);
                $configCrud->setCrudIdent('/' . $data['crud']['rootClass'] . $data['crud']['key']);
                $configCrud->setCrudConfigKey($data['crud']['key']);
                $configCrud->setCrudConfigValue($data['crud']['value']);
                $configCrud->setCrudConfigValueDefault($data['crud']['valueDefault'] ?? $data['crud']['value']);
                $configCrud->setCrudConfigGroup(($data['crud']['group'] ?? null));
                $configCrud->setCrudConfigLanguage(json_encode(($data['crud']['language'] ?? [])));
                $configCrud->setCrudConfigForm(json_encode(($data['crud']['form'] ?? [])));

                $progressData['message'] = $configCrud->insert() . ' |##|blue';

                /*$after*/
            },
                [
                    'crud' => $configData
                ]);

        }

    }

    public function importConfigUser(): void
    {
        $rootClass        = Core::getRootClass(get_called_class());
        $fileConfigImport = Container::get('ContainerFactoryFile',
                                           [
                                               'filename' => $rootClass . '.install.config.user.json',
                                           ]);
        if (!$fileConfigImport->exists()) {
            throw new DetailedException('languageConfigFileNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'class' => $rootClass,
                                            ]
                                        ]);
        }

        $fileConfigImport->load();
        $fileConfigImport->decode();
        $list = $fileConfigImport->get();

        if (isset($list['content']) === false || is_array($list['content']) === false) {
            throw new DetailedException('configUserEmpty',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'class' => $rootClass,
                                            ]
                                        ]);
        }

        foreach ($list['content'] as $configKey => $configData) {
            $configData['rootClass'] = $rootClass;
            $configData['key']       = $configKey;

            $this->installFunction(function () {
                /** @var array $data */ /*$before*/

                /** @var ContainerFactoryUserConfig_crud $configCrud */
                $configCrud = Container::get('ContainerFactoryUserConfig_crud');
                $configCrud->setCrudIdent('/' . $data['crud']['rootClass'] . $data['crud']['key']);
                $configCrud->setCrudClass($data['crud']['rootClass']);
                $configCrud->setCrudConfigKey($data['crud']['key']);
                $configCrud->setCrudConfigValueDefault($data['crud']['value']);
                $configCrud->setCrudConfigGroup(($data['crud']['group'] ?? null));
                $configCrud->setCrudConfigLanguage(json_encode(($data['crud']['language'] ?? [])));
                $configCrud->setCrudConfigForm(json_encode(($data['crud']['form'] ?? [])));

                $progressData['message'] = $configCrud->insert() . ' |##|blue';

                /*$after*/
            },
                [
                    'crud' => $configData
                ]);

        }

    }

    public function importRoute(): void
    {
        $rootClass = Core::getRootClass(get_called_class());
        /** @var ContainerFactoryFile $fileRouteImport */
        $fileRouteImport = Container::get('ContainerFactoryFile',
                                          [
                                              'filename' => $rootClass . '.install.route.json',
                                          ]);
        $fileRouteImport->load();
        $fileRouteImport->decode();
        $list = $fileRouteImport->get();

        foreach ($list as $type => $listItem) {

            foreach ($listItem as $route => $listData) {
                $listData['_rootClass'] = $rootClass;
                $listData['_type']      = $type;
                $listData['_route']     = $route;

                $this->installFunction(function () {
                    /** @var array $data */ /*$before*/

                    $crud = Container::get('ContainerFactoryRouter_crud');
                    $crud->setCrudClass($data['crud']['_rootClass']);

                    $crud->setCrudType($data['crud']['_type']);
                    $crud->setCrudRoute($data['crud']['_route']);
                    $crud->setCrudTarget(($data['crud']['target'] ?? 'index'));
                    $crud->setCrudPath($data['crud']['path']);

                    $progressData['message'] = $crud->insert() . '|##|blue';

                    /*$after*/
                },
                    [
                        'crud' => $listData
                    ]);

            }
        }

    }

    public function importMenu(): void
    {
        $rootClass       = Core::getRootClass(get_called_class());
        $fileRouteImport = Container::get('ContainerFactoryFile',
                                          [
                                              'filename' => $rootClass . '.install.menu.json',
                                          ]);

        $fileRouteImport->load();
        $fileRouteImport->decode();
        $list = $fileRouteImport->get()['content'];

        $languageMenuCrud = [
            'class'      => Core::getRootClass(get_called_class()),
            'link'       => $list['link'],
            'menuIcon'   => ($list['menuIcon'] ?? ''),
            'menuAccess' => ($list['menuAccess'] ?? Core::getRootClass(get_called_class())),
            'data'       => '',
        ];

//        if () {
//
//        } else {
//
//        }

        if (!empty($list['language'])) {

            foreach ($list['language'] as $language => $languageItem) {
                $languageMenuCrud['data'] .= '
                [' . $language . ']
                title = "' . $languageItem['menuTitle'] . '"
                description = "' . $languageItem['menuDescription'] . '"
                path = "' . ($languageItem['menuPath'] ?? '???') . '"
                ' . PHP_EOL;
            }

            $this->installFunction(function () {
                /** @var array $data */ /*$before*/

                /** @var ContainerFactoryMenu_crud $crud */
                $crud = Container::get('ContainerFactoryMenu_crud');

                $crud->setCrudClass($data['crud']['class']);
                $crud->setCrudData(trim($data['crud']['data']));
                $crud->setCrudMenuLink($data['crud']['link'] ?? '');
                $crud->setCrudMenuIcon($data['crud']['menuIcon'] ?? '');

                $crud->setCrudMenuAccess($data['crud']['menuAccess'] ?? $data['crud']['class']);

                $progressData['message'] = $crud->insert() . '|##|blue';

                /*$after*/
            },
                [
                    'crud' => $languageMenuCrud
                ]);


        }

    }

    public function importMeta(): void
    {
        $rootClass = Core::getRootClass(get_called_class());

        /** @var ContainerFactoryFile $fileRouteImport */
        $fileRouteImport = Container::get('ContainerFactoryFile',
                                          $rootClass . '.install.meta.ini');
        if (!$fileRouteImport->exists()) {
            $fileRouteImport = Container::get('ContainerFactoryFile',
                                              $rootClass . '.install.meta.ini',
                                              CMS_PATH_CUSTOM_LOCAL);
            if (!$fileRouteImport->exists()) {
                $fileRouteImport = Container::get('ContainerFactoryFile',
                                                  $rootClass . '.install.meta.ini',
                                                  CMS_PATH_CUSTOM_REPOSITORY);
            }
            else {
                $this->installFunction(function () {
                    /** @var array $data */ /*$before*/

                    $progressData['message'] = 'Import Meta: ' . $data['_rootClass'] . ' Fail ! - INI not Found|##|red';
                    /*$after*/
                },
                    [
                        '_rootClass' => $rootClass
                    ]);
            }
        }

        $fileRouteImport->load();
        $fileRouteImport->decode();
        $iniData = $fileRouteImport->get();

        if (empty($iniData)) {
            $this->installFunction(function () {
                /** @var array $data */ /*$before*/
                $progressData['message'] = 'Import Meta: ' . $data['_rootClass'] . ' Fail ! - INI Error|##|red';
                /*$after*/
            },
                [
                    '_rootClass' => $rootClass
                ]);
        }
        {

            $iniData['_rootClass'] = $rootClass;

            $this->installFunction(function () {
                /** @var array $data */ /*$before*/

                /** @var ContainerFactoryModul_crud $modulCrud */
                $modulCrud = Container::get('ContainerFactoryModul_crud');
                $modulCrud->setCrudModul($data['iniData']['_rootClass']);
                $modulCrud->setCrudParentModul($data['iniData']['information']['parentModul'] ?? '');
                $modulCrud->setCrudName($data['iniData']['information']['name'] ?? '');
                $modulCrud->setCrudDescription($data['iniData']['information']['description'] ?? '');
                $modulCrud->setCrudAuthor($data['iniData']['information']['author'] ?? '');
                $modulCrud->setCrudVersion($data['iniData']['information']['versionModul'] ?? '');
                $modulCrud->setCrudVersionRequiredSystem($data['iniData']['information']['versionRequiredSystem'] ?? '');
                $modulCrud->setCrudDependency(implode('',
                    ($data['iniData']['dependency'] ?? [])));
                $modulCrud->setCrudHasJavascript((int)($data['iniData']['has']['javascript'] ?? 0));
                $modulCrud->setCrudHasCss((int)($data['iniData']['has']['css'] ?? 0));
                $modulCrud->setCrudHasContent((int)($data['iniData']['has']['content'] ?? 0));
                $modulCrud->setCrudHasSearch((int)($data['iniData']['has']['search'] ?? 0));

                $modulCrud->setCrudActive($data['iniData']['active'] ?? 0);

                $modulCrud->insert();

                $progressData['message'] = 'Import Meta: ' . $data['iniData']['_rootClass'] . '|##|blue';

                /*$after*/
            },
                [
                    'iniData' => $iniData
                ]);

        }
    }

    /**
     * @param string $extension
     *
     * @throws DetailedException
     */
    public function importMetaFromModul(string $extension = ''): void
    {
        $rootClass = Core::getRootClass(get_called_class());

        /** @var ContainerFactoryReflection $commentThis */
        $commentThis = Container::get('ContainerFactoryReflection',
                                      $rootClass . $extension);

        $classComment = $commentThis->getReflectionClassComment();

        /** @var array $metaCollect */
        $metaCollect = $classComment['paramData']['@modul'];

        simpleDebugLog($rootClass);
        simpleDebugLog($metaCollect);

        if (!empty($classComment['title'])) {
            $metaCollect['name'] = trim($classComment['title']);
        }

        if (!empty($classComment['description'])) {
            $metaCollect['description'] = trim($classComment['description']);
        }

        $metaCollect['hasJavascriptFiles'] = '';
        $hasJavascript                     = isset($metaCollect['hasJavascript']);
        if ($hasJavascript === true) {
            if (empty($metaCollect['hasJavascript'])) {
                $metaCollect['hasJavascriptFiles'] = '';
            }
            else {
                $metaCollect['hasJavascriptFiles'] = $metaCollect['hasJavascript'];
            }
        }

        $metaCollect['hasCSSFiles'] = '';
        $hasCSS                     = isset($metaCollect['hasCSS']);
        if ($hasCSS === true) {
            if (empty($metaCollect['hasCSS'])) {
                $metaCollect['hasCSSFiles'] = '';
            }
            else {
                $metaCollect['hasCSSFiles'] = $metaCollect['hasCSS'];
            }
        }

        $metaCollect['hasContent'] = isset($metaCollect['hasContent']);
        $metaCollect['hasSearch']  = isset($metaCollect['hasSearch']);

        if (isset($metaCollect['groupAccess'])) {
            $this->setGroupAccess($rootClass,
                                  explode(',',
                                      ($metaCollect['groupAccess'] ?? [])));
        }


        $modulMeta = [
            'language' => []
        ];

        foreach ($metaCollect as $metaCollectKey => $metaCollectItem) {
            if (
                str_contains($metaCollectKey,
                             'language_') === true
            ) {
                $metaCollectKeyData                                                    = explode('_',
                                                                                                 $metaCollectKey);
                $modulMeta['language'][$metaCollectKeyData[1]][$metaCollectKeyData[2]] = $metaCollectItem;
            }


        }

        $modulMeta = [
            'modul'                 => $rootClass,
            'parentModul'           => ($metaCollect['parentModul'] ?? ''),
            'name'                  => ($metaCollect['name'] ?? ''),
            'description'           => ($metaCollect['description'] ?? ''),
            'meta'                  => json_encode($modulMeta),
            'author'                => ($metaCollect['author'] ?? ''),
            'version'               => ($metaCollect['version'] ?? ''),
            'versionRequiredSystem' => ($metaCollect['versionRequiredSystem'] ?? ''),
            'dependency'            => ($metaCollect['dependency'] ?? ''),
            'hasCSS'                => ($hasCSS ? 1 : 0),
            'hasCSSFiles'           => ($metaCollect['hasCSSFiles'] ?? ''),
            'hasJavascript'         => ($hasJavascript ? 1 : 0),
            'hasJavascriptFiles'    => ($metaCollect['hasJavascriptFiles'] ?? ''),
            'hasContent'            => (($metaCollect['hasContent'] ?? false) ? 1 : 0),
            'hasSearch'             => (($metaCollect['hasSearch'] ?? false) ? 1 : 0),
            'active'                => (($metaCollect['active'] ?? false) ? 0 : 1),
        ];

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryModul_crud $modulCrud */
            $modulCrud = Container::get('ContainerFactoryModul_crud');
            $modulCrud->setCrudModul($data['modulMeta']['modul']);
            $modulCrud->setCrudParentModul($data['modulMeta']['parentModul']);
            $modulCrud->setCrudHash(md5($data['modulMeta']['modul'] . (string)Config::get('/environment/secret/modul')));
            $modulCrud->setCrudName($data['modulMeta']['name']);
            $modulCrud->setCrudDescription($data['modulMeta']['description']);
            $modulCrud->setCrudMeta($data['modulMeta']['meta']);
            $modulCrud->setCrudAuthor($data['modulMeta']['author']);
            $modulCrud->setCrudVersion($data['modulMeta']['version']);
            $modulCrud->setCrudVersionRequiredSystem($data['modulMeta']['versionRequiredSystem']);
            $modulCrud->setCrudDependency($data['modulMeta']['dependency']);
            $modulCrud->setCrudHasJavascript((int)$data['modulMeta']['hasJavascript']);
            $modulCrud->setCrudHasJavascriptFiles($data['modulMeta']['hasJavascriptFiles']);
            $modulCrud->setCrudHasCss((int)$data['modulMeta']['hasCSS']);
            $modulCrud->setCrudHasCssFiles($data['modulMeta']['hasCSSFiles']);
            $modulCrud->setCrudHasContent((int)($data['modulMeta']['hasContent']));
            $modulCrud->setCrudHasSearch((int)($data['modulMeta']['hasSearch']));

            $modulCrud->setCrudActive($data['modulMeta']['active']);

            $modulCrud->insert();

            $progressData['message'] = 'Import Meta Class: ' . $data['modulMeta']['modul'] . '|##|blue';

            /*$after*/
        },
            [
                'modulMeta' => $modulMeta
            ]);

    }

    public function importDocumentationCode(string $type = ContainerFactoryModulInstall_abstract::DOCUMENTATION_TYPE_CODE): void
    {
        $rootClass = Core::getRootClass(get_called_class());

        $documentationWidgetPathFile = Core::getClassFileName($rootClass . '_install_documentation_' . $type . '_tpl');

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerExtensionDocumentation_crud $crud */
            $crud = Container::get("ContainerExtensionDocumentation_crud");
            $crud->setCrudClass($data['crud']["rootClass"]);
            $crud->setCrudType($data['crud']["type"]);
            $crud->setCrudContent($data['crud']["content"]);

            $progressData["message"] = $crud->insert() . " |##|blue";

            /*$after*/
        },
            [
                'crud' => [
                    'rootClass' => $rootClass,
                    'type'      => $type,
                    'content'   => file_get_contents($documentationWidgetPathFile),
                ]
            ]);
    }

    public function setGroupAccess(string $path, array $groups = []): void
    {
        $rootClass = Core::getRootClass(get_called_class());

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryUserGroupAccess_crud $crud */
            $crud = Container::get("ContainerFactoryUserGroupAccess_crud");
            $crud->setCrudClass($data['crud']["rootClass"]);
            $crud->setCrudPath($data['crud']["path"]);

            $progressData["message"] = $crud->insert() . " |##|blue";

            /*$after*/
        },
            [
                'crud' => [
                    'rootClass' => $rootClass,
                    'path'      => $path
                ]
            ]);

        foreach ($groups as $group) {
            $this->installFunction(function () {
                /** @var array $data */ /*$before*/

                /** @var ContainerFactoryUserGroup_crud_groupaccess $crud */
                $crud = Container::get("ContainerFactoryUserGroup_crud_groupaccess");
                $crud->setCrudUserGroupId((int)$data['crud']["group"]);
                $crud->setCrudAccess($data['crud']["path"]);

                $progressData["message"] = $crud->insert() . " |##|blue";

                /*$after*/
            },
                [
                    'crud' => [
                        'group' => $group,
                        'path'  => $path,
                    ]
                ]);
        }

    }

    public function removeStdEntitiesDeactivate(): void
    {
        $rootClass = Core::getRootClass(get_called_class());

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryRouter_crud $crud */
            $crud = Container::get("ContainerFactoryLanguage_crud");
            $crud->deleteFrom([
                                  'crudClass' => $data['rootClass']
                              ]);

            $progressData["message"] = "Remove Standard Router: " . $data['rootClass'] . "|##|blue";

            /*$after*/
        },
            [
                'rootClass' => $rootClass,
            ]);

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryMenu_crud $crud */
            $crud = Container::get("ContainerFactoryMenu_crud");
            $crud->deleteFrom([
                                  'crudClass' => $data['rootClass']
                              ]);

            $progressData["message"] = "Remove Standard Modul: " . $data['rootClass'] . "|##|blue";

            /*$after*/
        },
            [
                'rootClass' => $rootClass,
            ]);
    }

    public function removeStdEntities(): void
    {
        $rootClass = Core::getRootClass(get_called_class());

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var ContainerFactoryModul_crud $crud */
            $crud = Container::get("ContainerFactoryModul_crud");
            $crud->deleteFrom([
                                  'crudModul' => $data['rootClass']
                              ]);

            $progressData["message"] = "Remove Standard Modul: " . $data['rootClass'] . "|##|blue";

            /*$after*/
        },
            [
                'rootClass' => $rootClass,
            ]);

    }

    public function isCustom($class)
    {
        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var Custom_crud $crud */
            $crud = Container::get('Custom_crud');
            $crud->setCrudIdent($data['class']);
            $crud->findById(true);
            $crud->setCrudStatus('Active');
            $crud->update();

            $progressData["message"] = "Is Custom: " . $data['class'] . "";

            /*$after*/
        },
            [
                'class' => $class,
            ]);
    }

    public function installFunction(callable $function, array $data = []): void
    {

        $this->console->addProgressFunction($function,
                                            [
                                                '/*$before*/' => '
        $data = ' . var_export($data,
                               true) . '
        ;
        ',
                                                '/*$after*/'  => '

                                                return $progressData;
                                                ',
                                            ]);
    }

    public function getConsole(): Console_abstract
    {
        return $this->console;
    }


}
