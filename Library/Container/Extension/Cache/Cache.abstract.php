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

    protected array        $parameter           = [];
    protected string       $ident               = '';
    protected              $cacheContent        = '';
    protected int          $target              = self::TARGET_INTERN;
    protected int          $ttl                 = 0;
    protected              $ttlDatetime;
    protected int          $size                = 0;
    protected bool         $persistent          = false;
    protected string       $dataVariableUpdated = '';
    protected bool         $isCreated           = false;
    protected static array $persistentCache     = [];


//if (
//(\Config::get('/environment/debug/active',
//CMS_DEBUG_ACTIVE) && !\Config::get('/environment/debug/cache',
//false))
//) {
//return;
//}


    public function __construct(...$parameter)
    {
        $this->parameter = $parameter;
        $this->prepare();

        if (
            ContainerFactoryDatabaseEngineSqlite::tableIsInDatabase('cache',
                                                                    'cache') === false
        ) {

            /** @var ContainerFactoryDatabaseEngineSqliteTable $queryStructure */
            $queryStructure = Container::get('ContainerFactoryDatabaseEngineSqliteTable',
                                             'cache');
            $queryStructure->setColumn('ident',
                                       'varchar;100');
            $queryStructure->setColumn('content',
                                       'text',
                                       true,
                                       ContainerFactoryDatabaseEngineMysqlTable::DEFAULT_NULL);
            $queryStructure->setColumn('target',
                                       'varchar;10');
            $queryStructure->setColumn('ttl',
                                       'int');
            $queryStructure->setColumn('ttlDatetime',
                                       'datetime');
            $queryStructure->setColumn('persistent',
                                       'tinyint;1');
            $queryStructure->setColumn('size',
                                       'int;11');
            $queryStructure->setColumn('dataVariableUpdated',
                                       'datetime');
            $queryStructure->setPrimary('ident');

            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __CLASS__ . '.create.console',
                                    'cache',
                                    ContainerFactoryDatabaseQuery::MODE_OTHER);

            foreach ($queryStructure->createQuery() as $queryItem) {
                $query->query($queryItem);
                $query->execute();
            }

            ContainerFactoryDatabaseEngineSqlite::reInit();

            /** @var ContainerFactoryDatabaseQuery $query */
            $query = Container::get('ContainerFactoryDatabaseQuery',
                                    __CLASS__ . '.create.console',
                                    'cache',
                                    ContainerFactoryDatabaseQuery::MODE_OTHER);

            $query->query('CREATE INDEX `persistent` ON `cache` (`persistent`)');
            $query->execute();

            ContainerFactoryDatabaseEngineSqlite::addTableDatabase('cache',
                                                                   'cache');
        }

        if (
        (!Config::get('/environment/debug/active',
                      CMS_DEBUG_ACTIVE) && Config::get('/environment/debug/cache',
                                                       false))
        ) {

            if (empty(self::$persistentCache)) {
                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __METHOD__ . '#select',
                                        'cache',
                                        ContainerFactoryDatabaseQuery::MODE_SELECT);

                $query->setTable('cache');
                $query->select('ident');
                $query->select('content');
                $query->select('target');
                $query->select('ttl');
                $query->select('ttlDatetime');
                $query->select('persistent');
                $query->select('size');
                $query->select('dataVariableUpdated');

                $query->setParameterWhere('persistent',
                                          1);

                $query->construct();
                $smtp = $query->execute();

                while ($smtpData = $smtp->fetch()) {
                    self::$persistentCache[$smtpData['ident']] = $smtpData;
                }
            }


            if (isset(self::$persistentCache[$this->ident]) && !empty(self::$persistentCache[$this->ident])) {
                $this->setCacheContent(unserialize(self::$persistentCache[$this->ident]['content']));
                $this->setTtl(self::$persistentCache[$this->ident]['ttl']);
                $this->setTarget(self::$persistentCache[$this->ident]['target']);
                $this->setPersistent((bool)self::$persistentCache[$this->ident]['persistent']);
            }

            if (empty($this->cacheContent)) {
                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __METHOD__ . '#select',
                                        'cache',
                                        ContainerFactoryDatabaseQuery::MODE_SELECT);

                $query->setTable('cache');
                $query->select('ident');
                $query->select('content');
                $query->select('target');
                $query->select('persistent');
                $query->select('ttl');
                $query->select('ttlDatetime');
                $query->select('dataVariableUpdated');

                $query->setParameterWhere('ident',
                                          $this->ident);
                $query->setLimit(1);

                $query->construct();
                $smtp = $query->execute();

                $smtpData = $smtp->fetch();

                if ($smtpData !== false) {
                    $this->setCacheContent(unserialize($smtpData['content']));
                    $this->setTtl($smtpData['ttl']);
                    $this->setTtlDatetime($smtpData['ttlDatetime']);
                    $this->setTarget($smtpData['target']);
                    $this->setPersistent((bool)$smtpData['persistent']);
                    $this->setDataVariableUpdated($smtpData['dataVariableUpdated']);
                }
            }
        }
        else {
            $this->setCacheContent(null);
            $this->setPersistent(false);
        }
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
        $cacheName = explode('_',
                             get_called_class(),
                             2);

        $scope['cacheClassName'] = $cacheName[0];
        $scope['cacheName']      = $cacheName[1];
        $scope['isCreated']      = false;

        if (
            empty($this->cacheContent) || $forceCreate === true
        ) {
            $this->cacheContent = null;
            $this->create();
            $this->setIsCreated(true);
            $scope['isCreated'] = true;

            if (empty($this->ident)) {
                throw new DetailedException('noIdent');
            }

            if (
            (!Config::get('/environment/debug/active',
                          CMS_DEBUG_ACTIVE) && Config::get('/environment/debug/cache',
                                                           false))
            ) {

                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __METHOD__ . '#insertUpdate',
                                        'cache',
                                        \ContainerFactoryDatabaseQuery::MODE_INSERT_UPDATE);

                $serializeData = serialize($this->cacheContent);
                $query->setTable('cache');
                $query->setTableKey('ident');
                $query->setInsertUpdate('ident',
                                        $this->ident);
                $query->setInsertUpdate('content',
                                        $serializeData,
                                        true);
                $query->setInsertUpdate('target',
                                        $this->target,
                                        true);
                $query->setInsertUpdate('ttl',
                                        $this->ttl,
                                        true);
                $query->setInsertUpdate('persistent',
                                        (int)$this->persistent,
                                        true);
                $query->setInsertUpdate('size',
                                        strlen($serializeData),
                                        true);

                $ttlDatetime = new \DateTime();

                if ($this->ttl > 0) {
                    $ttlDatetime->modify('' . $this->ttl . 's');
                }
                $query->setInsertUpdate('ttlDatetime',
                    ((empty($this->ttl)) ? '0000-00-00 00:00:00' : $ttlDatetime->format((string)Config::get('/cms/date/dbase'))),
                                        true);

                $query->construct();
                $query->execute();
            }
        }


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

}
