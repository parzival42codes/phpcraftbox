<?php

class ContainerExtensionTemplateParseCreateTableTable extends Base
{

    protected                                    $templates    = [];
    protected array                                    $data         = [];
    protected array                                    $configHeader = [];
    protected array                                    $configRow    = [];
    protected array                                    $parameter    = [];
    protected ContainerExtensionTemplateInternalAssign $assignObject;
    protected                                    $table        = '';
    protected string                                   $tableClass   = '';

    public function __construct(array $parameter, ContainerExtensionTemplateInternalAssign $assignObject, string $table = '')
    {
        $this->parameter    = $parameter;
        $this->assignObject = $assignObject;
        $this->table        = $table;

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache   = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                          Core::getRootClass(__CLASS__),
                                          'row,cell,container');
        $this->templates = $templateCache->get();

    }

    public function get(): string
    {
        $content = $this->createHeader();
        $content .= $this->createRow();

        /** @var ContainerExtensionTemplate $templateContainer */
        $templateContainer = Container::get('ContainerExtensionTemplate');
        $templateContainer->set($this->templates['container']);
        $templateContainer->assign('tableClass',
                                   'ContainerExtensionTemplateParseCreateTableTable ' . $this->tableClass);
        $templateContainer->assign('content',
                                   $content);

        $templateContainer->parseString();

        return $templateContainer->get();

    }


    /**
     * Helper for the Standard
     *
     * @param string $tableName Table Name in the Template
     * @param string $part      Part is Example header_row_row....
     * @param string $keyName   Key of the Row as id, name etc..
     * @param array  $classes   Thr classes for Attribute Html
     *
     * @throws DetailedException
     */
    protected function createAttribute(string $tableName, string $part, string $keyName, array $classes = []): void
    {
        $keyName = 'table_' . $tableName . '_' . $part . '_' . $keyName;

        /** @var ContainerIndexHtmlAttribute $attribute */
        $attribute = $this->assignObject->get($keyName);
        if ($attribute === null) {
            $attribute = Container::get('ContainerIndexHtmlAttribute');
        }

        foreach ($classes as $class) {
            $attribute->set('class',
                            null,
                            $class);
        }

        $this->assignObject->set($keyName,
                                 $attribute);


    }

    protected function createHeader(): string
    {
        $contentHeader = '';

        foreach ($this->configHeader as $configHeaderKey => $configHeaderItem) {


            /** @var ContainerExtensionTemplate $templateCell */
            $templateCell = Container::get('ContainerExtensionTemplate');
            $templateCell->set($this->templates['cell']);

            $templateCell->assign('type',
                                  'header');
            $templateCell->assign('configRowKey',
                                  $configHeaderKey ?? '');
            $templateCell->assign('content',
                ($configHeaderItem['titleHeader'] ?? ''));
            $templateCell->assign('classCell',
                ($configHeaderItem['classCell'] ?? ''));
            $templateCell->parse();
            $contentHeader .= $templateCell->get();
        }

        /** @var ContainerExtensionTemplate $templateRow */
        $templateRow = Container::get('ContainerExtensionTemplate');
        $templateRow->set($this->templates['row']);
        $templateRow->assign('type',
                             'header');
        $templateRow->assign('content',
                             $contentHeader);
        $templateRow->parse();

        return $templateRow->get();
    }

    protected function createRow(): string
    {
        $content    = '';
        $contentRow = '';

        foreach ($this->configRow as $configRowKey => $configRowItem) {

            /** @var ContainerExtensionTemplate $templateCell */
            $templateCell = Container::get('ContainerExtensionTemplate');
            $templateCell->set($this->templates['cell']);

            $templateCell->assign('configRowKey',
                                  $configRowKey);
            $templateCell->assign('type',
                                  'body');
            $templateCell->assign('classCell',
                ($configRowItem['classCell'] ?? ''));
            $templateCell->assign('content',
                                  '{$' . $configRowKey . '}');
            $templateCell->parseString();
            $contentRow .= $templateCell->get();

        }

        /** @var ContainerExtensionTemplate $templateRow */
        $templateRow = Container::get('ContainerExtensionTemplate');
        $templateRow->set($this->templates['row']);

        $templateRow->assign('content',
                             $contentRow);
        $templateRow->assign('type',
                             'body');
        $templateRow->parseString();
        $contentRowItem = $templateRow->get();

        foreach ($this->data as $dataItem) {

            /** @var ContainerExtensionTemplate $templateObject */
            $templateObject = Container::get('ContainerExtensionTemplate');
            $templateObject->set($contentRowItem);

            foreach ($this->configRow as $configRowKey => $configRowItem) {

                if (isset($configRowItem['rowParameter']) && !empty($configRowItem['rowParameter'])) {

                    foreach ($configRowItem['rowParameter'] as $modificationWorkItem) {

                        $modificationWorkItemParameter = explode(':',
                                                                 $modificationWorkItem);

                        $modificationWorkItemClass = array_shift($modificationWorkItemParameter);

                        /** @var ContainerExtensionTemplateParseCreateTableTable_abstract_modification $object */
                        $object = Container::getInstance(__CLASS__ . ucfirst($modificationWorkItemClass));
                        $templateObject->assign($configRowKey,
                                                $object->get($dataItem[$configRowKey],
                                                             $configRowItem,
                                                             $modificationWorkItemParameter));
                    }

                }
                else {
                    $templateObject->assign($configRowKey,
                                            $dataItem[$configRowKey]);
                }

            }

            $templateObject->parseString();
            $content .= $templateObject->get();

        }

        return $content;
    }

//        $contentRow = '';
//
//        foreach ($this->configKeys as $configKey) {
//            /** @var ContainerIndexHtmlAttribute $attribute */
//
//            $attribute = $attributeCollect[$configKey] ?? Container::get('ContainerIndexHtmlAttribute');
//
//            $attribute->set('class',
//                            null,
//                            'table-cell')
//                      ->set('class',
//                            null,
//                            'table-cell-key-' . $configKey);
//
//            $contentCell = ($row[$configKey] ?? '??');
//
//
//            //            simpleDebugDump($modification);
//
//            if (isset($modification[$configKey])) {
//                foreach ($modification[$configKey] as $modificationModul => $modificationParameter) {
//
//                    //                simpleDebugDump($modificationModul);
//                    //                simpleDebugDump($modificationParameter);
//
//                    $contentCell = Container::getInstance(__CLASS__ . ucfirst($modificationModul))
//                                             ->get($contentCell,
//                                                   $attribute,
//                                                   false,
//                                                   $modificationParameter);
//                }
//            }
//
//
//            //            die();
//
//            /** @var ContainerExtensionTemplate $templateCell */
//            $templateCell = $this->template->getKeyTemplate('cell');
//            $contentRow   .= $templateCell->assign('attribute',
//                                                   $attribute->getHtml())
//                                          ->assign('content',
//                                                   $contentCell)
//                                          ->parse()
//                                          ->get();
//        }
//
//        /** @var ContainerIndexHtmlAttribute $attributeRow */
//        if ($attributeRow === null) {
//            $attributeRow = Container::get('ContainerIndexHtmlAttribute');
//        }
//
//        $attributeRow->set('class',
//                           null,
//                           'table-row');
//
//        /** @var ContainerExtensionTemplate $templateRow */
//        $templateRow = $this->template->getKeyTemplate('row');
//        return $templateRow->assign('attribute',
//                                    $attributeRow->getHtml())
//                           ->assign('content',
//                                    $contentRow)
//                           ->parse()
//                           ->get();

    /**
     * @param string $key
     * @param array  $parameter
     *
     * @return void
     */
    public function setConfigHeader(string $key, array $parameter = []): void
    {
        $this->configHeader[$key] = $parameter;
    }

    /**
     * @return void
     */
    public function setConfigRow(string $key, array $parameter = []): void
    {
        $this->configRow[$key] = $parameter;
    }

    /**
     * @param array $value
     *
     * @return void
     */
    public function setData(array $value = []): void
    {
        $this->data = $value;
    }

    /**
     * @return string
     */
    public function getTableClass(): string
    {
        return $this->tableClass;
    }

    /**
     * @param string $tableClass
     */
    public function setTableClass(string $tableClass): void
    {
        $this->tableClass = $tableClass;
    }


}
