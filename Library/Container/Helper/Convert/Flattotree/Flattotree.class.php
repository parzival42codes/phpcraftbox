<?php

class ContainerHelperConvertFlattotree
{

    public static function convert(array $flat, string $parentIdField = 'parentId', string $childNodesField = 'childNodes'): array
    {
        if (isset($flat[0])) {
            throw new DetailedException('zeroIdNotAllowed',
                                        0,
                                        null,
                                        [
                                            'array' => var_export($flat,
                                                                  true),
                                        ],
                                        0);
        }
        $treeIndex = [];
        foreach ($flat as $flatId => $flatItem) {
            $treeIndex[$flatItem[$parentIdField]][] = $flatId;
        }
        return self::flat2treeHelper($flat,
                                     $treeIndex,
                                     0,
                                     $parentIdField,
                                     $childNodesField);
    }

    protected static function flat2treeHelper(array $flat, array $treeIndex, int $id = 0, string $idField = 'id', string $parentIdField = 'parentId', string $childNodesField = 'childNodes'): array
    {
        $tree = [];
        foreach ($treeIndex[$id] as $flatItem) {
            if (isset($flat[$flatItem]) === true) {
                if (isset($treeIndex[$flatItem]) === true) {
                    $flat[$flatItem][$childNodesField] = self::flat2treeHelper($flat,
                                                                               $treeIndex,
                                                                               $flatItem,
                                                                               $idField,
                                                                               $parentIdField,
                                                                               $childNodesField);
                }
                $tree[$flatItem] = $flat[$flatItem];
            }
        }
        return $tree;
    }

}
