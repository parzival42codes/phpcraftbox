<?php

class ContainerExternResourcesJavascript_cache_js extends ContainerExtensionCache_abstract
{

    protected int   $ttl = 3600;
    protected array $prio
                         = [
            1 => [],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
        ];

    public function prepare(): void
    {
        $this->ident = __CLASS__;
        $this->setPersistent(true);
    }

    public function create(): void
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('index_module');
        $query->select('crudModul');
        $query->setParameterWhere('crudHasJavascript',
                                  '1');

        $query->construct();
        $smtp = $query->execute();
        /** @var ePDOStatement $smtp */

        while ($smtpData = $smtp->fetch()) {
            $this->fileInclude($smtpData['crudModul'],
                               'script');
        }

        ksort($this->prio);

        $this->setCacheContent(array_merge($this->prio[1],
                                           $this->prio[2],
                                           $this->prio[3],
                                           $this->prio[4],
                                           $this->prio[5]));
    }

    protected function fileInclude(string $class, string $filename): void
    {
        /** @var ContainerFactoryFile $file */
        $file = Container::get('ContainerFactoryFile',
                               $class . '_' . $filename . '_js');

        if ($file->exists() === true) {

            $file->load();
            $fileLoad = $file->get();

            if (is_string($fileLoad) === false) {
                throw new DetailedException('fileContentNotString',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    $fileLoad
                                                ]
                                            ]);
            }

            $metaStrEnd = strrpos($fileLoad,
                                  '</meta>*/');
            if ($metaStrEnd !== false) {

                $metaStrStart = strpos($fileLoad,
                                       '/*<meta>');
                if ($metaStrStart !== false) {
                    $metaStrStartPos = $metaStrStart + 8;
                }
                else {
                    $metaStrStartPos = 0;
                }

                $meta = json_decode(trim(substr($fileLoad,
                                                $metaStrStartPos,
                    ($metaStrEnd - $metaStrStartPos))),
                                    true);

                $fileLoad = substr($fileLoad,
                    ($metaStrEnd + 9));
            }

            if (!empty($fileLoad)) {
                $this->prio[($meta['prio'] ?? 3)][] = $fileLoad;
            }

            if (isset($meta['include'])) {
                foreach ($meta['include'] as $jsInclude) {
                    $this->fileInclude($class,
                                       $jsInclude);
                }
            }

        }

    }


}
