<?php
require(dirname(__FILE__) . '/../Library/config.inc.php');

//$query = Container::get('ContainerFactoryDatabaseQuery',
//                        __METHOD__ . '#showTables',
//                        'log',
//                        ContainerFactoryDatabaseQuery::MODE_OTHER);
//$query->query('SELECT * FROM debug');
//$query->construct();
//$query->execute();

/** @var ContainerFactoryLogDebug_crud $log */
$log     = Container::get('ContainerFactoryLogDebug_crud');
$findAll = $log->find([],
                      [
                          'crudId DESC',
                      ],
                      [],
                      100);

/** @var ContainerFactoryLogDebug_crud $findAllItem */
foreach ($findAll as $findAllItem) {
    echo $findAllItem->getCrudContent();
//    d($findAllItem->getCrudId());
//    d($findAllItem->getCrudContent());
//    d($findAllItem->getDataVariableCreated());
}
