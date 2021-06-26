<?php declare(strict_types=1);

class ContainerExtensionTemplateParseCreatePaginationHelper extends Base
{
    /**
     * @var array
     */
    static array $paginationContainer = [];

    /**
     * @var string
     */
    protected string $ident = '';

    /**
     * @var int
     */
    protected int $pageCount;

    /**
     * @var int
     */
    protected int $page = 1;

    /**
     * @var int
     */
    protected int $pagesView;

    /**
     * @var int
     */
    protected int $pageActive = 1;

    /**
     * @var int
     */
    protected int $pageOffset = 1;

    /**
     * @var int
     */
    protected int $pageMax = 1;

    protected int $pagesItemsDisplay;

    public function __construct(string $ident, int $pageCount, int $pagesItemsDisplay = 0)
    {
        $this->ident             = $ident;
        $this->pageCount         = (int)$pageCount;
        $this->pagesView         = 25;
        $this->pagesItemsDisplay = $pagesItemsDisplay;

        /** @var ContainerFactoryRequest $pageActiveGet */
        $pageActiveGet = Container::get('ContainerFactoryRequest',
                                        ContainerFactoryRequest::REQUEST_GET,
                                        '_page');

        if ($pageActiveGet->exists()) {
            $this->pageActive = (int)$pageActiveGet->get();
        }

        $this->pageOffset = (($this->pageActive * $this->pagesView) - $this->pagesView);

        $this->pageMax = intval(floor($this->pageCount / $this->pagesView));
        if ($this->pageMax === 0) {
            $this->pageMax = 1;
        }
    }

    public function getTemplate():string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache  = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                         Core::getRootClass(__CLASS__),
                                         'container,first,last,next,prev,step');
        $templateOutput = '';

        if ($this->pageActive > 1) {
            /** @var ContainerFactoryRouter $router */
            $router = clone Container::getInstance('ContainerFactoryRouter');
            $router->setQuery('_page',
                              1);

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->getCacheContent()['first']);
            $template->assign('firstPage',
                              $router->getUrlReadable());


            $template->parseString();
            $templateOutput .= $template->get();

            /** @var ContainerFactoryRouter $router */
            $router = clone Container::getInstance('ContainerFactoryRouter');
            $router->setQuery('_page',
                ($this->pageActive - 1));

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->getCacheContent()['prev']);
            $template->assign('prevPage',
                              $router->getUrlReadable());
            $template->parseString();
            $templateOutput .= $template->get();
        }

        $pagesCollect = [];
        for ($i = 1; $i <= $this->pageMax; $i++) {
            /** @var ContainerFactoryRouter $router */
            $router = clone Container::getInstance('ContainerFactoryRouter');
            $router->setQuery('_page',
                              $i);

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->getCacheContent()['step']);
            $template->assign('active',
                (($i !== $this->pageActive) ? '' : 'active'));
            $template->assign('step',
                              $i);
            $template->assign('stepPage',
                              $router->getUrlReadable());

            $template->parseString();
            $pagesCollect[] = $template->get();
        }

        if ($this->pagesItemsDisplay > 0) {
            $pagesCollectOffset = $this->pageActive - $this->pagesItemsDisplay - 1;

            if ($pagesCollectOffset < 0) {
                $pagesCollectOffset = 0;
            }


            $templateOutput .= implode('',
                                       array_splice($pagesCollect,
                                                    $pagesCollectOffset,
                                           ($this->pagesItemsDisplay + 1 + $this->pagesItemsDisplay)));
        }
        else {
            $templateOutput .= implode('',
                                       $pagesCollect);
        }

        if ($this->pageActive < $this->pageMax) {
            /** @var ContainerFactoryRouter $router */
            $router = clone Container::getInstance('ContainerFactoryRouter');
            $router->setQuery('_page',
                ($this->pageActive + 1));

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->getCacheContent()['next']);
            $template->assign('nextPage',
                              $router->getUrlReadable());

            $template->parseString();
            $templateOutput .= $template->get();

            /** @var ContainerFactoryRouter $router */
            $router = clone Container::getInstance('ContainerFactoryRouter');
            $router->setQuery('_page',
                              $this->pageMax);

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->getCacheContent()['last']);
            $template->assign('lastPage',
                              $router->getUrlReadable());
            $template->parseString();
            $templateOutput .= $template->get();

        }

        $title = sprintf(ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreatePaginationHelper/title'),
                         $this->pageActive,
                         $this->pageMax);

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['container']);
        $template->assign('title',
                          $title);
        $template->assign('content',
                          $templateOutput);

        $template->parseString();

        return $template->get();
    }

    public function create(): void
    {
        self::$paginationContainer[$this->ident] = $this;
    }

    public static function getPaginationContainer(string $ident)
    {
        return (self::$paginationContainer[$ident] ?? null);
    }

    /**
     * @return int
     */
    public function getPageOffset(): int
    {
        return $this->pageOffset;
    }

    /**
     * @return int
     */
    public function getPageMax(): int
    {
        return $this->pageMax;
    }

    /**
     * @return int
     */
    public function getPagesView(): int
    {
        return $this->pagesView;
    }

}

