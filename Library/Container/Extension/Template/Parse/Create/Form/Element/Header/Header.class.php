<?php declare(strict_types=1);

class ContainerExtensionTemplateParseCreateFormElementHeader extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{

    public function get(): string
    {

        /** @var ContainerFactoryRouter $router */
        $router = Container::get(ContainerFactoryRouter::class);

        /** @var ContainerIndexHtmlAttribute $attributeHeader */
        $attributeHeader = Container::get('ContainerIndexHtmlAttribute');
        $attributeHeader->set('class',
                              'ContainerExtensionTemplateParseCreateForm',
                              'ContainerExtensionTemplateParseCreateForm');
        $attributeHeader->set('class',
                              'header',
                              'header');
        $attributeHeader->set('id',
                              null,
                              $this->getFormId());
        $attributeHeader->set('action',
                              null,
                              $router->getUrlReadable());
        $attributeHeader->set('autocomplete',
                              null,
                              'off');
        $attributeHeader->set('method',
                              null,
                              'POST');
        $attributeHeader->set('enctype',
                              null,
                              'multipart/form-data');
        $attributeHeader->set('data-parsley-validate',
                              null,
                              '');

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'header');
        $templates     = $templateCache->get();

        /** @var ContainerExtensionTemplate $templateCacheHeader */
        $templateCacheHeader = Container::get('ContainerExtensionTemplate');
        $templateCacheHeader->set($templates['header']);
        $templateCacheHeader->assign('attribute',
                                     $attributeHeader->getHtml());
        $templateCacheHeader->parse();

        return $templateCacheHeader->get();


    }

    public function response(): void
    {

    }


}
