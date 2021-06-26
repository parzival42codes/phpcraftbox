<?php

class ContainerFactoryToken extends Base
{
    public function __construct()
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('token');
        $query->selectFunction('count(*) as c');

        $query->construct();
        $smtp = $query->execute();

        $smtpData = $smtp->fetch();

        if (!isset($smtpData['c']) || empty($smtpData['c']) || $smtpData['c'] <= 10) {
            $data = [];

            /** @var ContainerFactoryUuid $uuid */
            $uuid = Container::get('ContainerFactoryUuid');

            for ($i = 1; $i <= 1000; $i++) {
                $data[] = [
                    'uuid' => $uuid->create(),
                ];
            }

            ContainerFactoryDatabaseQuery::massInsertInto(__CLASS__ . '/massInsert',
                                                          true,
                                                          'token',
                                                          $data);

        }

    }

    public function get():void
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('token');
        $query->select('id');
        $query->select('uuid');

        $query->orderByRand();

        $query->construct();
        $smtp = $query->execute();
        //while ($fetchData = $smtp->fetch()) {
    }

}
