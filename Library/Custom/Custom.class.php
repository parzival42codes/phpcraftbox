<?php
declare(strict_types=1);

class Custom extends Base
{
    public function __construct()
    {

    }

    /**
     * @return array[]
     * @throws DetailedException
     */
    public static function getCustomCLasses(): array
    {
        $prepareIndex = [
            'Local_Application'      => [],
            'Local_Plugin'           => [],
            'Local_Style'            => [],
            'Repository_Application' => [],
            'Repository_Plugin'      => [],
            'Repository_Style'       => [],
        ];

        $prepareIndexKeys = array_keys($prepareIndex);

        $rudIndex = $prepareIndex;

        /** @var Custom_crud $crud */
        /** @var Custom_crud $crudListItem */
        $crud     = Container::get('Custom_crud');
        $crudList = $crud->find();

        $rudIndexData = [];
        foreach ($crudList as $crudListItem) {
            $rudIndex[$crudListItem->getCrudSource() . '_' . $crudListItem->getCrudType()][$crudListItem->getCrudIdent()] = $crudListItem;
            $rudIndexData[$crudListItem->getCrudIdent()]                                                                  = $crudListItem;
        }

        $customIndex = $prepareIndex;

        if (is_dir(CMS_PATH_CUSTOM_LOCAL . 'Application')) {
            $directory = new DirectoryIterator(CMS_PATH_CUSTOM_LOCAL . 'Application');
            foreach ($directory as $directoryItem) {
                if (!$directoryItem->isDot() && $directoryItem->isDir()) {
                    $customClassName                                    = 'Application' . $directoryItem->getFilename();
                    $customObject                                       = Container::get($customClassName . '_custom');
                    $customIndex['Local_Application'][$customClassName] = true;
                }
            }
        }

        $customWork = [
            'remove' => $prepareIndex,
            'add'    => $prepareIndex,
            'equal'  => $prepareIndex,
        ];

        foreach ($prepareIndexKeys as $key) {
            $customWork['remove'][$key] = array_merge($customWork['remove'][$key],
                                                      array_diff_key($rudIndex[$key],
                                                                     $customIndex[$key]));
            $customWork['add'][$key]    = array_merge($customWork['add'][$key],
                                                      array_diff_key($customIndex[$key],
                                                                     $rudIndex[$key]));

            $customWork['equal'][$key] = array_merge($customWork['equal'][$key],
                                                     array_intersect_key($rudIndex[$key],
                                                                         $customIndex[$key]));

        }

        $customData = [
            'Install'   => [],
            'Active'    => [],
            'InActive'  => [],
            'UnInstall' => [],
        ];

        foreach ($customWork['add'] as $key => $add) {
            foreach ($add as $addKey => $addItem) {

                $customData['UnInstall'][$addKey] = self::getModulData($addKey,
                                                                       'UnInstall');
                $keyData                          = explode('_',
                                                            $key);
                /** @var Custom_crud $crud */
                $crud = Container::get('Custom_crud');
                $crud->setCrudIdent($addKey);
                $crud->setCrudSource($keyData[0]);
                $crud->setCrudType($keyData[1]);
                $crud->setCrudStatus('UnInstall');
                $crud->insertUpdate();

                $rudIndexData[$addKey] = $crud;
            }
        }

        foreach ($customWork['equal'] as $key => $add) {
            /** @var Custom_crud $addItem */
            foreach ($add as $addKey => $addItem) {
                $customData[$addItem->getCrudStatus()][$addKey] = self::getModulData($addKey,
                                                                       $addItem->getCrudStatus());

            }
        }

//        d($customData);
//        eol(true);

        return $customData;
    }

    /**
     * @param string $class
     * @param string $status
     *
     * @return array
     * @throws DetailedException
     */
    protected static function getModulData(string $class, string $status): array
    {
        /** @var ContainerFactoryReflection $commentThis */
        $commentThis = Container::get('ContainerFactoryReflection',
                                      $class . '_custom');

        /** @var Custom_abstract $customClassData */
        $customClassData = Container::get($class . '_custom');

        $classComment = $commentThis->getReflectionClassComment();

        $information = $classComment['paramData']['@modul'];

        return [
            'name'        => $customClassData->getName(),
            'description' => $customClassData->getDescription(),
            'version'     => ($information['version'] ?? '?'),
            'author'      => ($information['author'] ?? '?'),
            'status'      => $status,
            'class'       => $class,
        ];
    }

}
