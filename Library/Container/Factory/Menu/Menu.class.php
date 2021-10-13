<?php

class ContainerFactoryMenu extends Base
{
    protected static string $source = '';
    /**
     * @var mixed
     */
    protected               $additionalPath = [];
    protected static string $ident          = '';

    /**
     * @var array
     */
    protected array $menu = [];

    protected string $menuClassMain = '';

    protected string $menuType   = '';
    protected string $menuId     = '';
    protected array  $index      = [];
    protected bool   $activ      = false;
    protected array  $accessPath = [];
    protected array  $pathfinder = [];

    protected array $menuAccessList = [];

    protected array $template = [];

    private bool $isTab = false;

    const MENU_HORIZONTAL = 'horizontal';
    const MENU_VERTICAL   = 'vertical';

    public function __construct(string $menuType = self::MENU_VERTICAL)
    {
        $this->menuType = $menuType;
    }

    /**
     * @param $additionalPath
     */
    public static function addAdditionalPath(string $additionalPath): void
    {
        self::$additionalPath[] = $additionalPath;
    }

    public function get(): array
    {
        return $this->menu;
    }

    public function setActive(bool $class): void
    {
        $this->activ = $class;
    }

    public function addMenuItem(ContainerFactoryMenuItem $item): void
    {
        $this->menu[$item->getPath()][] = $item;
    }

    public function removeMenuItem(string $path, string $key): void
    {
        unset($this->menu[$path][$key]);
    }

    public function setId(string $id): void
    {
        $this->menuId = $id;
    }

    public function getId(): string
    {
        return $this->menuId;
    }

    public function createMenu(string $path = '', string $title = ''): string
    {

        if (
        str_contains($title,
                     '|')
        ) {
            $title = explode('|',
                             $title,
                             2);
            $title = $title[1];
        }

        $serverPathExplodeItemLast = '';

        $pathExplode = explode('/',
                               $path);

        array_shift($pathExplode);

        foreach ($pathExplode as $serverPathExplodeItem) {
            $serverPathExplodeItemLast                    = $serverPathExplodeItemLast . '/' . $serverPathExplodeItem;
            $this->pathfinder[$serverPathExplodeItemLast] = true;
        }

        $serverPathExplodeItemLast                    = $serverPathExplodeItemLast . '/' . $title;
        $this->pathfinder[$serverPathExplodeItemLast] = true;

        $switchMenuAccessList = array_flip($this->menuAccessList);

        /** @var ContainerFactoryMenuItem $menuItem */
        foreach ($this->menu as $menuPath => $menuContainer) {
            foreach ($menuContainer as $menuKey => $menuItem) {
                if (
                    !isset($this->menuAccessList[$menuItem->getAccess()]) && !isset($switchMenuAccessList[$menuItem->getAccess()])
                ) {
                    $this->removeMenuItem($menuPath,
                                          $menuKey);
                }
            }
        }

        $this->menu = array_filter($this->menu);

        $path2array = Container::get('ContainerHelperConvertPath2array',
                                     $this->menu);

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'menu.' . $this->menuType . '.main,menu.' . $this->menuType . '.item,menu.' . $this->menuType . '.sub');

        $this->template = $templateCache->get();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($this->template['menu.' . $this->menuType . '.main']);
        $template->assign('content',
                          $this->createMenuHelper($path2array->get(),
                                                  0,
                                                  $serverPathExplodeItemLast,
                                                  ''));

        if ($this->isTab() === false) {
            $template->assign('class',
                              '');
        }
        else {
            $template->assign('class',
                              'menu-horizontal-tab');
        }

        $template->parse();

        return $template->get();

    }

    /**
     * @return array
     */
    public function getMenuAccessList(): array
    {
        return $this->menuAccessList;
    }

    /**
     * @param array $menuAccessList
     */
    public function setMenuAccessList(array $menuAccessList = ['']): void
    {
        $this->menuAccessList = $menuAccessList;
    }

    protected function createMenuHelper(array $tree, int $level, string $serverPathExplodeItemLast, string $key): string
    {
        asort($tree);

        $return = '';
        foreach ($tree as $treeKey => $treeItem) {

            if ($treeItem instanceof ContainerFactoryMenuItem) {
                $return .= $this->createMenuHelperItem($treeItem,
                                                       $serverPathExplodeItemLast);
            }
            elseif (is_array($treeItem)) {

                $pathCurrent = $key . '/' . $treeKey;

                if (
                in_array($pathCurrent,
                         $this->accessPath)
                ) {
                    continue;
                }

                /** @var ContainerExtensionTemplate $template */
                $template = Container::get('ContainerExtensionTemplate');
                $template->set($this->template['menu.' . $this->menuType . '.sub']);

                if (isset($this->pathfinder[$pathCurrent])) {
                    $template->assign('subDirShowHide',
                                      'block');
                    $template->assign('menuActive',
                                      'font-weight: bold;');
                    $template->assign('subDirStatus',
                                      'open');
                    $template->assign('subDirStatusFolderOpen',
                                      'block');
                    $template->assign('subDirStatusFolderClose',
                                      'none');
                }
                else {
                    $template->assign('subDirShowHide',
                                      'none');
                    $template->assign('menuActive',
                                      'font-weight: inherit;');
                    $template->assign('subDirStatus',
                                      'close');
                    $template->assign('subDirStatusFolderOpen',
                                      'none');
                    $template->assign('subDirStatusFolderClose',
                                      'block');
                }

                $template->assign('title',
                                  $treeKey);
                $template->assign('subDir',
                                  $this->createMenuHelper($treeItem,
                                                          ++$level,
                                                          $serverPathExplodeItemLast,
                                                          $pathCurrent));
                $template->parse();

                $return .= $template->get();
            }
        }

        return $return;
    }

    /**
     * @param ContainerFactoryMenuItem $treeItem
     * @param string                   $serverPathExplodeItemLast
     *
     * @return string
     * @throws DetailedException
     */
    protected function createMenuHelperItem(ContainerFactoryMenuItem $treeItem, string $serverPathExplodeItemLast): string
    {
        if (!empty($treeItem->getAccess())) {
            /** @var ContainerFactoryUser $user */
            $user = Container::getInstance('ContainerFactoryUser');

            if (!$user->checkUserAccess($treeItem->getAccess())) {
                return '';
            }
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($this->template['menu.' . $this->menuType . '.item']);

        $title = $treeItem->getTitle();

        if (
        str_contains($title,
                     '|')
        ) {
            $title = explode('|',
                             $title,
                             2);
            $title = $title[1];
        }

        $template->assign('icon',
                          $treeItem->getIcon());
        $template->assign('title',
                          $title);
        $template->assign('link',
                          $treeItem->getLink());
        $template->assign('menuActive',
                          ($treeItem->getPath() . '/' . $title == $serverPathExplodeItemLast) ? 'class="active-item"' : '');

        $template->parse();

        return $template->get();
    }

    /**
     * @return string
     */
    public function getMenuClassMain(): string
    {
        return $this->menuClassMain;
    }

    /**
     * @param string $menuClassMain
     */
    public function setMenuClassMain(string $menuClassMain): void
    {
        $this->menuClassMain = $menuClassMain;
    }

    /**
     * @return bool
     */
    public function isTab(): bool
    {
        return $this->isTab;
    }

    /**
     * @param bool $isTab
     */
    public function setIsTab(bool $isTab): void
    {
        $this->isTab = $isTab;
    }

}
