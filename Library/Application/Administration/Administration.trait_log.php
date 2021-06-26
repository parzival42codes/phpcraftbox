<?php

trait ApplicationAdministration_trait_log {

    public function getTableContent( $class, $selectedLog, $pageNr = null) {


        $classRoot = Core::getRootClass(__CLASS__);
        $language  = Container::get('ContainerExtensionLanguage', $classRoot, 'log');


        $log           = Container::getShared('Log');
        $logClass      = Container::get($class . '_log');
        $languageClass = Container::get('ContainerExtensionLanguage', $class, 'meta');
        $lngLogMeta    = $languageClass->getMetaSelected('/log');

        if ($selectedLog === null) {
            $selectedLog = reset($lngLogMeta['/log']['parts']);
        }

        if (method_exists($logClass, 'get' . ucfirst($selectedLog))) {
            $logMethod = 'get' . ucfirst($selectedLog);
        } else {
            $logMethod = 'get';
        }

        $logOnPage = 2;

        $countLogFind = [
            'class'        => $class,
            'messageIdent' => $selectedLog,
        ];

        $logCountAll = $log->getData()->countBy($countLogFind);
        $logCount    = $log->getData()->countBy($countLogFind);

        $pagination = Container::get('IndexPagination', 'log', $logCount, $logOnPage, $pageNr, $logCountAll);
        $logData    = $log->getData()->findBy($countLogFind, ['order'       => [
                'dataVariableCreated' => 'DESC'
            ],
            'limit'       => $logOnPage,
            'limitOffset' => $pagination->getPageLimitOffset(),
        ]);



        $tableTcs = [];
        foreach ($logData as $logDataItem) {
            $tableTcs[] = call_user_func_array([$logClass, $logMethod], [$logDataItem]);
        }

        $table = Container::get('ContainerIndexTable', [
                    'logId'     => [
                        'title' => $language->get('/header/id'),
                        'width' => 150,
                    ],
                    'logType'   => [
                        'title' => $language->get('/header/type'),
                        'width' => 150,
                    ],
                    'logView'   => [
                        'title' => $language->get('/header/view'),
                        'width' => 500,
                    ],
                    'logDetail' => [
                        'title' => $language->get('/header/view'),
                        'width' => 100,
                    ],
                    'logTime'   => [
                        'title' => $language->get('/header/time'),
                        'width' => 200,
                    ]
                        ], [
                    'logId-logType-logView-logDetail',
                        ], $tableTcs);
        $table->setUniqid($classRoot . 'tablelog');

        return [
            'table'       => $table,
            'pagination'  => $pagination,
            'selectedLog' => $selectedLog,
        ];
    }

}
