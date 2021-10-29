<?php declare(strict_types=1);

/**
 * Cache Ansicht
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 4
 * @modul    hasCSS
 * @modul    language_path_de_DE /Administration/System
 * @modul    language_name_de_DE Cache
 * @modul    language_path_en_US /Administration/System
 * @modul    language_name_en_US Administration
 */
class ApplicationAdministrationSystemCache_app extends Application_abstract
{
    public function setContent(): string
    {
        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'default');

        $template = new ContainerExtensionTemplate();
        $template->set($templateCache->get()['default']);

        $this->createPageData();

        $tableTcs = [];

        $cacheSource = 'sqlite';
        if (Config::get('/ContainerExtensionCache/source') === 'redis') {
            if (ContainerExtensionCacheRedis::connection()) {
                $cacheSource = 'redis';
            };
        }
        elseif (Config::get('/ContainerExtensionCache/source') === 'memcached') {
            if (ContainerExtensionCacheMemcached::connection()) {
                $cacheSource = 'memcached';
            };
        }

        if ($cacheSource === 'redis') {
            $cacheContent = ContainerExtensionCacheRedis::getCache();
        }
        elseif ($cacheSource === 'memcached') {

        }
        else {
            $cacheContent = ContainerExtensionCacheSqlite::getCache();
        }

        array_walk($cacheContent,
            function (&$content) {
                $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                                   'box,ttl');

                $template = new ContainerExtensionTemplate();
                $template->set($templateCache->get()['box']);

                $content['key'] = strtr($content['key'],
                                        [
                                            '/' => '&shy;',
                                        ]);

                $content['content'] = strtr(($content['content'] ?? ''),
                    [
                        '{' => '&#123;',
                        '}' => '&#125;',
                        '<' => '&lt;',
                        '>' => '&gt;',
                    ]);

                $template->assign('content',
                                  $content['content']);
                $template->parse();

                $content['content'] = $template->get();

                $templateDateTime = new ContainerExtensionTemplate();
                $templateDateTime->set($templateCache->get()['ttl']);

                $ttlDateTime = ((empty($content['ttlDateTime']) || $content['ttlDateTime'] === '0000-00-00 00:00:00') ? 'now' : $content['ttlDateTime']);

                $ttlDiff = ContainerHelperDatetime::calculateDifference(new DateTime(),
                                                                        new DateTime($ttlDateTime));

                $templateDateTime->assign('sec',
                                          $ttlDiff['s']);
                $templateDateTime->assign('min',
                                          $ttlDiff['i']);
                $templateDateTime->assign('hours',
                                          $ttlDiff['h']);
                $templateDateTime->assign('days',
                                          $ttlDiff['d']);
                $templateDateTime->assign('month',
                                          $ttlDiff['m']);
                $templateDateTime->assign('years',
                                          $ttlDiff['y']);
                $templateDateTime->assign('negative',
                                          (int)$ttlDiff['negative']);

                $templateDateTime->parse();
                $content['ttl'] = $templateDateTime->get();

            });

        $template->assign('Table_Table',
                          $cacheContent);

        $template->parse();

        return $template->get();
    }

    public function pageData(): void
    {

    }

    public static function createMenu(string $class): void
    {
        /** @var ContainerFactoryMenu $menu */
        $menu = Container::get('ContainerFactoryMenu',
                               ContainerFactoryMenu::MENU_HORIZONTAL);
        $menu->setIsTab(true);
        $menu->setMenuAccessList();

        /** @var ContainerFactoryMenuItem $menuItemOverview */
        $menuItemOverview = Container::
        get('ContainerFactoryMenuItem');
        $menuItemOverview->setAccess('');
        $menuItemOverview->setLink('index.php?application=ApplicationAdministrationUser');
        $menuItemOverview->setPath('/');
        $menuItemOverview->setTitle('1|' . ContainerFactoryLanguage::get('/ApplicationAdministrationUser/meta/title'));

        $menu->addMenuItem($menuItemOverview);

        /** @var ContainerFactoryMenuItem $menuItemEdit */
        $menuItemEdit = Container::get('ContainerFactoryMenuItem');
        $menuItemEdit->setAccess('');
        $menuItemEdit->setLink('index.php?application=ApplicationAdministrationUserEdit');
        $menuItemEdit->setPath('/');
        $menuItemEdit->setTitle('2|' . sprintf(ContainerFactoryLanguage::get('/ApplicationAdministrationUserEdit/meta/title'),
                                               ''));

        $menu->addMenuItem($menuItemEdit);

        ContainerExtensionTemplateParseInsertPositions::insert('/page/box/main/header',
                                                               $menu->createMenu('/',
                                                                                 sprintf(ContainerFactoryLanguage::get('/' . $class . '/meta/title'),
                                                                                         '')));

    }

    protected function getFilterDataCategoryPath(): array
    {

//        $crud      = new ApplicationBlog_crud_category();
//        $crudItems = $crud->find();
//
//        $filterData = [];
//
//        /** @var ApplicationBlog_crud_category $crudItem */
//        foreach ($crudItems as $crudItem) {
//            $text                               = ContainerFactoryLanguage::getLanguageText($crudItem->getCrudLanguage());
//            $filterData[$crudItem->getCrudId()] = $text;
//        }
//
//        return $filterData;
        return [];
    }

}
