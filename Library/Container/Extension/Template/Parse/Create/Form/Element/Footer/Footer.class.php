<?php

class ContainerExtensionTemplateParseCreateFormElementFooter extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{

    public function get(): string
    {

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'footer');

        /** @var ContainerFactoryCrypt $crypt */
        $crypt = Container::get('ContainerFactoryCrypt');
        $crypt->setText(serialize([
                                      'modify'      => ContainerExtensionTemplateParseCreateFormRequest::getModify($this->formId),
                                      'metaData'    => ContainerExtensionTemplateParseCreateFormRequest::getMetaData($this->formId),
                                      'requestData' => ContainerExtensionTemplateParseCreateFormRequest::getRequestDataAll($this->formId),
                                  ]));

        $crypt->setKey((string)Config::get('/environment/secret/form'));

        /** @var ContainerExtensionTemplate $templateCacheFooter */
        $templateCacheFooter = Container::get('ContainerExtensionTemplate');
        $templateCacheFooter->set($templateCache->get()['footer']);

        $templateCacheFooter->assign('modifyEncrypt',
                                     $crypt->getEnCrypt());

        $templateCacheFooter->assign('formId',
                                     $this->formId);

        $templateCacheFooter->parse();

        return $templateCacheFooter->get();
    }

    public function response(): void
    {

    }

}
