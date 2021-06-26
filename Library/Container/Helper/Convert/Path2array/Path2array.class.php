<?php

class ContainerHelperConvertPath2array extends Base
{
    protected array $pathArray = [];

    public function __construct(array $path)
    {
        foreach ($path as $pathKey => $pathItem) {
            $pathKeyExploded = explode('/',
                                       $pathKey);

            array_shift($pathKeyExploded);

            if (!empty($pathKeyExploded[0])) {
                $this->createArray($pathKeyExploded);
                $this->arrayValue($pathKeyExploded,
                                  $pathItem);
            }
            else {
                $this->arrayValueRoot($pathItem);
            }

        }

    }

    public function get():array
    {
        return $this->pathArray;
    }

    protected function createArray(array $data):void
    {
        $dataCount = count($data);

        if ($dataCount > 0 && !isset($this->pathArray[$data[0]])) {
            $this->pathArray[$data[0]] = [];
        }
        if ($dataCount > 1 && !isset($this->pathArray[$data[0]][$data[1]])) {
            $this->pathArray[$data[0]][$data[1]] = [];
        }
        if ($dataCount > 2 && !isset($this->pathArray[$data[0]][$data[1]][$data[2]])) {
            $this->pathArray[$data[0]][$data[1]][$data[2]] = [];
        }
        if ($dataCount > 3 && !isset($this->pathArray[$data[0]][$data[1]][$data[2]][$data[3]])) {
            $this->pathArray[$data[0]][$data[1]][$data[2]][$data[3]] = [];
        }
        if ($dataCount > 4 && !isset($this->pathArray[$data[0]][$data[1]][$data[2]][$data[3]][$data[4]])) {
            $this->pathArray[$data[0]][$data[1]][$data[2]][$data[3]][$data[4]] = [];
        }
        if ($dataCount > 5 && !isset($this->pathArray[$data[0]][$data[1]][$data[2]][$data[3]][$data[4]][$data[5]])) {
            $this->pathArray[$data[0]][$data[1]][$data[2]][$data[3]][$data[4]][$data[5]] = [];
        }

    }

    protected function arrayValue(array $pathKeyExploded, $pathItem):void
    {
        switch (count($pathKeyExploded)) {
            case 1:
                if (is_array($pathItem)) {
                    $this->pathArray[$pathKeyExploded[0]] = array_merge($this->pathArray[$pathKeyExploded[0]],
                                                                        $pathItem);
                }
                else {
                    $this->pathArray[$pathKeyExploded[0]][] = $pathItem;
                }
                break;
            case 2:
                if (is_array($pathItem)) {
                    $this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]] = array_merge($this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]],
                                                                                             $pathItem);
                }
                else {
                    $this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]][] = $pathItem;
                }
                break;
            case 3:
                if (is_array($pathItem)) {
                    $this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]][$pathKeyExploded[2]] = array_merge($this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]][$pathKeyExploded[2]],
                                                                                                                  $pathItem);
                }
                else {
                    $this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]][$pathKeyExploded[2]][] = $pathItem;
                }
                break;
            case 4:
                if (is_array($pathItem)) {
                    $this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]][$pathKeyExploded[2]][$pathKeyExploded[3]] = array_merge($this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]][$pathKeyExploded[2]][$pathKeyExploded[3]],
                                                                                                                                       $pathItem);
                }
                else {
                    $this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]][$pathKeyExploded[2]][$pathKeyExploded[3]][] = $pathItem;
                }
                break;
            case 5:
                if (is_array($pathItem)) {
                    $this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]][$pathKeyExploded[2]][$pathKeyExploded[3]][$pathKeyExploded[4]] = array_merge($this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]][$pathKeyExploded[2]][$pathKeyExploded[3]][$pathKeyExploded[4]],
                                                                                                                                                            $pathItem);
                }
                else {
                    $this->pathArray[$pathKeyExploded[0]][$pathKeyExploded[1]][$pathKeyExploded[2]][$pathKeyExploded[3]][$pathKeyExploded[4]][] = $pathItem;
                }
                break;
        }
    }

    protected function arrayValueRoot($pathItem):void
    {
        if (is_array($pathItem)) {
            $this->pathArray = array_merge($this->pathArray,
                                           $pathItem);
        }
        else {
            $this->pathArray = $pathItem;
        }
    }

}
