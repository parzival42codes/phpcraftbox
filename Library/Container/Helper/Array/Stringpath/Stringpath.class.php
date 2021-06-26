<?php

class ContainerHelperArrayStringpath extends Base
{

    /**
     * @var array
     */
    private array $stringPath =[];

    private array $content = [];

    public function __construct(array $stringPath)
    {
        ksort($stringPath);
        $this->stringPath = $stringPath;
        $this->getStructure();
    }

    protected function getStructure():void
    {
        $idCounter             = 0;
        $structureIndex        = [];
        $structureIndexParent  = [];
        $structureIndexLvl     = [];
        $structureKeyContainer = [];
        $structureKeys         = array_keys($this->stringPath);

           foreach ($structureKeys as $structureKeyCount => $structureKey) {
            $structureKeyData = explode('/',
                                        $structureKey);
            array_shift($structureKeyData);
            array_pop($structureKeyData);
            $structurePath     = '';
            $structurePathLvl  = 0;
            $structurePathHash = [];

            foreach ($structureKeyData as $structureKeyDataItem) {
                ++$structurePathLvl;
                $structurePath .= $structureKeyDataItem;
                if (!isset($structureIndex[$structurePath])) {
                    $structurePathHash[]                                    = md5($structurePath);
                    $structureIndex[$structurePath]                         = ++$idCounter;
                    $structureIndexLvl[$structurePath]                      = $structurePathLvl;
                    $structureKeyContainer[$structureIndex[$structurePath]] = [
                        'name'          => $structureKeyDataItem,
                        'structurePath' => $structurePath,
                        'content'       => '',
                    ];

                }
            }
        }

        foreach ($structureKeys as $structureKeyCount => $structureKey) {
            $structureKeyData = explode('/',
                                        $structureKey);
            array_shift($structureKeyData);
            array_pop($structureKeyData);
            $structurePath = '';

            foreach ($structureKeyData as $structureKeyDataItem) {
                $structurePathBefore                                              = $structurePath;
                $structurePath                                                    .= $structureKeyDataItem;
                $structureKeyContainer[$structureIndex[$structurePath]]['parent'] = ($structureIndex[$structurePathBefore] ?? 0);
            }
        }

        foreach ($structureKeys as $structureKeyCount => $structureKey) {
            $structureKeyData = explode('/',
                                        $structureKey);
            $last             = array_pop($structureKeyData);
            $structurePath    = '';

            foreach ($structureKeyData as $structureKeyDataItem) {
                $structurePath .= $structureKeyDataItem;
                if (empty($structurePath)) {
                    continue;
                }
            }

            $idCounter++;
            $structureIndexParent[$structurePath][] = $idCounter;

            $structureKeyContainer[$idCounter] = [
                'name'    => $last,
                'parent'  => ($structureIndex[$structurePath] ?? 0),
                'content' => $this->stringPath[$structureKey],
            ];

        }

        $this->content = [
            'container' => $structureKeyContainer
        ];

    }

    /**
     * Export the Data from Execute.
     *
     * @return array
     */
    public function getContainer():array
    {
        return $this->content['container'];
    }

}
