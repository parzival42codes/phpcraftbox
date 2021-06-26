<?php

/**
 * Class ContainerFactoryFile
 * @method createTab(string $key) Creating a Tab
 * @method setConfig(string $key, $value) Setting the Config
 * @method get()
 */

class ContainerIndexTab extends Base
{


    protected static array $tabJsCollect = [];
    protected array        $tabs         = [];
    protected string       $idMain       = '';
    protected string       $class        = '';
    protected array        $parameter    = [];
    protected array        $config       = [];

    public function __construct(string $idMain)
    {
        $this->idMain = $idMain;
    }

    static function getCollect(): string
    {
        return '_globalFunctions[\'tabCollectedJs\'] = function () {' . implode('',
                                                                                self::$tabJsCollect) . '};
                                                                                _globalFunctions[\'tabCollectedJs\'].call(this);';
    }

    public function _setClass(array &$scope, string $class): void
    {
        $this->class = $class;
    }

    public function _createTab(array &$scope,?string  $key = null): ContainerIndexTabItem
    {
        $key              = ($id ?? uniqid('tabUnique'));
        $this->tabs[$key] = Container::get(__CLASS__ . 'Item',
                                           $key);
        return $this->tabs[$key];
    }

    public function _setConfig(array &$scope, string $key, $value): void
    {
        $this->config[$key] = $value;
    }

    public function _get(array &$scope): string
    {
        if (($this->config['collect'] ?? false) === false) {

            /** @var ContainerIndexPage $page */
            $page = Container::getInstance('ContainerIndexPage');

            $page->addPageJavascript('
            jQuery("#' . $this->idMain . '").CMSTab(' . json_encode($this->config) . ');
        ');
        }
        else {
            self::$tabJsCollect[] = ' jQuery("#' . $this->idMain . '").CMSTab(' . json_encode($this->config) . ');';
        }

        $tabContent = '';

        foreach ($this->tabs as $tabsItem) {
            $tabContent .= $tabsItem->get();
        }

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        __CLASS__,
                                        'tab');
        $templates     = $templateCache->getCacheContent();

        /** @var ContainerExtensionTemplate $returnTabs */
        $returnTabs = Container::get('ContainerExtensionTemplate');
        $returnTabs->set($templates['tab']);
        $returnTabs->assign('idMain',
                            $this->idMain);
        $returnTabs->assign('class',
                            $this->class);
        $returnTabs->assign('tabContent',
                            $tabContent);
        $returnTabs->parse();

        return $returnTabs->get();
    }

}
