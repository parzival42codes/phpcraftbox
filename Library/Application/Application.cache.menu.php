<?php

class Application_cache_menu extends ContainerExtensionCache_abstract
{

    public function prepare(): void
    {
        $this->ident = __CLASS__ . '/' . \Config::get('/environment/language');
        $this->setPersistent(true);
    }

    public function create(): void
    {
        $this->cacheContent = Container::get('ContainerFactoryMenu');

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('menu');
        $query->select('crudClass');
        $query->select('crudData');
        $query->select('crudMenuIcon');
        $query->select('crudMenuLink');
        $query->select('crudMenuAccess');

        $query->construct();
        $smtp = $query->execute();

        $menuDB = $smtp->fetchAll();

        if ($menuDB != false) {
            foreach ($menuDB as $menuDBItem) {
                /** @var ContainerFactoryLanguageParseIni $iniParser */
                $iniParser = Container::get('ContainerFactoryLanguageParseIni',
                                            $menuDBItem['crudData']);

                $iniParserLanguages = $iniParser->getLanguages();
                if (isset($iniParserLanguages[Config::get('/environment/language')])) {
                    $iniParserLanguagesMenu = $iniParserLanguages[Config::get('/environment/language')];
                }
                else {
                    $iniParserLanguagesMenu = reset($iniParserLanguages);
                }

                /** @var ContainerFactoryMenuItem $menuItem */
                $menuItem = Container::get('ContainerFactoryMenuItem');
                $menuItem->setPath($iniParserLanguagesMenu['path']);
                $menuItem->setDescription($iniParserLanguagesMenu['description']);
                $menuItem->setIcon($menuDBItem['crudMenuIcon']);
                $menuItem->setTitle($iniParserLanguagesMenu['title']);
                $menuItem->setLink($menuDBItem['crudMenuLink']);
                $menuItem->setAccess(($menuDBItem['crudMenuAccess'] ?? $menuDBItem['crudClass']));

                $this->cacheContent->addMenuItem($menuItem);
            }
        }

//        d($this->cacheContent);
//        eol();
    }


}
