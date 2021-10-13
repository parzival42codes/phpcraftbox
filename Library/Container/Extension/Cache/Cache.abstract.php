<?php
declare(strict_types=1);

/**
 * Class ContainerExtensionCache_abstract
 * @method mixed getCacheContent()
 */
abstract class ContainerExtensionCache_abstract extends Base
{
    const TARGET_INTERN = 1;
    const TARGET_EXTERN = 2;

    protected array                              $parameter           = [];
    protected string                             $ident               = '';
    protected                                    $cacheContent        = '';
    protected int                                $target              = self::TARGET_INTERN;
    protected int                                $ttl                 = 0;
    protected                                    $ttlDatetime;
    protected int                                $size                = 0;
    protected bool                               $persistent          = false;
    protected string                             $dataVariableUpdated = '';
    protected bool                               $isCreated           = false;
    protected ?ContainerExtensionCache_interface $cacheResource       = null;

    public function __construct(...$parameter)
    {
        $this->parameter = $parameter;
        $this->prepare();

        $this->cacheResource = new ContainerExtensionCacheSqlite($this);
        d($this->cacheResource);
        eol();
    }

    /**
     * @return int
     */
    public function getTtl(): int
    {
        return $this->ttl;
    }

    /**
     * @param int $ttl
     */
    public function setTtl(int $ttl): void
    {
        $this->ttl = $ttl;
    }

    /**
     * @return string
     */
    public function getTtlDatetime(): string
    {
        return (empty($this->ttlDatetime) ? '0000-00-00 00:00:00' : $this->ttlDatetime);
    }

    /**
     * @param string $datetime
     *
     * @return void
     */
    public function setTtlDatetime(string $datetime): void
    {
        $this->ttlDatetime = $datetime;
    }

    /**
     * @param array $scope
     * @param false $forceCreate
     *
     * @return mixed
     * @throws DetailedException
     * @CMSprofilerSet          _class ContainerExtensionCache
     * @CMSprofilerSetFromScope cacheClassName
     * @CMSprofilerSetFromScope cacheName
     * @CMSprofilerSetFromScope isCreated
     */
    public function _getCacheContent(array &$scope, bool $forceCreate = false)
    {
        $this->cacheResource->getCacheContent($this,
                                              $scope,
                                              $forceCreate);

//        $cacheName = explode('_',
//                             get_called_class(),
//                             2);
//
//        $scope['cacheClassName'] = $cacheName[0];
//        $scope['cacheName']      = $cacheName[1];
//        $scope['isCreated']      = false;
//
//        if (
//            empty($this->cacheContent) || $forceCreate === true
//        ) {
//            $this->cacheContent = null;
//            $this->create();
//            $this->setIsCreated(true);
//            $scope['isCreated'] = true;
//
//            if (empty($this->ident)) {
//                throw new DetailedException('noIdent');
//            }
//
//            if (
//                (!PAGE_REFRESH_DETECT_DEBUG)
//            ) {
//
//                /** @var ContainerFactoryDatabaseQuery $query */
//                $query = Container::get('ContainerFactoryDatabaseQuery',
//                                        __METHOD__ . '#insertUpdate',
//                                        'cache',
//                                        \ContainerFactoryDatabaseQuery::MODE_INSERT_UPDATE);
//
//                $serializeData = serialize($this->cacheContent);
//                $query->setTable('cache');
//                $query->setTableKey('ident');
//                $query->setInsertUpdate('ident',
//                                        $this->ident);
//                $query->setInsertUpdate('content',
//                                        $serializeData,
//                                        true);
//                $query->setInsertUpdate('target',
//                                        $this->target,
//                                        true);
//                $query->setInsertUpdate('ttl',
//                                        $this->ttl,
//                                        true);
//                $query->setInsertUpdate('persistent',
//                                        (int)$this->persistent,
//                                        true);
//                $query->setInsertUpdate('size',
//                                        strlen($serializeData),
//                                        true);
//
//                $ttlDatetime = new \DateTime();
//
//                if ($this->ttl > 0) {
//                    $ttlDatetime->modify('' . $this->ttl . 's');
//                }
//                $query->setInsertUpdate('ttlDatetime',
//                    ((empty($this->ttl)) ? '0000-00-00 00:00:00' : $ttlDatetime->format((string)Config::get('/cms/date/dbase'))),
//                                        true);
//
//                $query->construct();
//                $query->execute();
//            }
//        }


        return $this->cacheContent;
    }

    abstract function prepare(): void;

    abstract function create(): void;

    /**
     * @return int
     */
    public function getTarget(): int
    {
        return $this->target;
    }

    /**
     * @param int $target
     */
    public function setTarget(int $target): void
    {
        $this->target = $target;
    }

    /**
     * @param $cacheContent
     */
    public function setCacheContent($cacheContent): void
    {
        $this->cacheContent = $cacheContent;
    }

    /**
     * @return bool
     */
    public function getPersistent(): bool
    {
        return $this->persistent;
    }

    /**
     * @param bool $persistent
     */
    public function setPersistent(bool $persistent): void
    {
        $this->persistent = $persistent;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize(int $size): void
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getDataVariableUpdated(): string
    {
        return $this->dataVariableUpdated;
    }

    /**
     * @param string $dataVariableUpdated
     */
    public function setDataVariableUpdated(string $dataVariableUpdated): void
    {
        $this->dataVariableUpdated = $dataVariableUpdated;
    }

    /**
     * @return bool
     */
    public function isCreated(): bool
    {
        return $this->isCreated;
    }

    /**
     * @param bool $isCreated
     */
    public function setIsCreated(bool $isCreated): void
    {
        $this->isCreated = $isCreated;
    }

    /**
     * @return string
     */
    public function getIdent(): string
    {
        return $this->ident;
    }

}
