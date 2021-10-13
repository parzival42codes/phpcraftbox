<?php

class ContainerIndexPageBox extends Base
{
    public function get(string $assigment): string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'row,rowitem');

        $templateCacheContent = $templateCache->get();

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('page_box');
        $query->select('crudId');
        $query->select('crudRow');
        $query->select('crudFlex');
        $query->select('crudContent');
        $query->setParameterWhere('crudAssignment',
                                  $assigment);
        $query->setParameterWhere('crudActive',
                                  1);
        $query->orderBy('crudRow ASC, crudPosition ASC');

        $query->construct();
        $smtp = $query->execute();

        $rowCollect = [];
        while ($smtpData = $smtp->fetch()) {
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCacheContent['rowitem']);

            $template->assign('flex',
                              $smtpData['crudFlex']);
            $template->assign('content',
                              $smtpData['crudContent']);
            $template->parse();

            if (!isset($rowCollect[$smtpData['crudRow']])) {
                $rowCollect[$smtpData['crudRow']] = '';
            }
            $rowCollect[$smtpData['crudRow']] .= $template->get();
        }

        $rowContainer = '';

        foreach ($rowCollect as $rowCollectItem) {
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCacheContent['row']);
            $template->assign('container',
                              $rowCollectItem);
            $template->parse();

            $rowContainer .= $template->get();
        }

        return $rowContainer;

    }

}
