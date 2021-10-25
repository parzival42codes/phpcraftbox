<?php

class ContainerFactoryLanguage_cache_entries extends ContainerExtensionCache_abstract
{

    public function prepare(): void
    {
        $this->ident = __CLASS__;
        $this->setPersistent(true);
    }

    public function create(): void
    {
        $this->cacheContent = [];

        $query = new ContainerFactoryDatabaseQuery(__METHOD__ . '#select',
                                                   true,
                                                   ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('language');
        $query->select('crudClass',
                       'crudLanguageKey',
                       'crudLanguageValue');
        $query->select('crudLanguageLanguage');

        $query->construct();
        $smtp = $query->execute();

        while ($smtpData = $smtp->fetch()) {
            $this->cacheContent['/' . $smtpData['crudClass'] . $smtpData['crudLanguageKey']][$smtpData['crudLanguageLanguage']] = $smtpData['crudLanguageValue'];
        }

        if (empty($this->cacheContent)) {
            $this->cacheContent = null;
        }

    }


}
