<?php

class ContainerIndexPageBreadcrumb extends Base
{
    protected array $breadcrumb = [];

    public function __construct()
    {

    }
//
//    public function getJsonLd(): string
//    {
//        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
//        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
//                                               Core::getRootClass(__CLASS__),
//                                               'breadcrumb.jsonld.item,breadcrumb.jsonld');
//        $templateCacheContent = $templateCache->get();
//
//        $templateItemList = '';
//        foreach ($this->breadcrumb as $breadcrumb) {
//            /** @var ContainerExtensionTemplate $template */
//            $template = Container::get('ContainerExtensionTemplate');
//            $template->set($templateCacheContent['breadcrumb.jsonld.item']);
//            $template->assign('name',
//                              $breadcrumb['name']);
//            $template->assign('link',
//                              $breadcrumb['link']);
//
//            $template->replaceSave(ContainerExtensionTemplate::REPLACE_SAVE_START);
//            $template->parse();
//            $template->replaceSave(ContainerExtensionTemplate::REPLACE_SAVE_STOP);
//
//            $templateItemList .= $template->get();
//        }
//
//        return $templateItemList;
//    }

    public function getHtmlList(): string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               Core::getRootClass(__CLASS__),
                                               'breadcrumb.html.item,breadcrumb.html.nolink,breadcrumb.html');
        $templateCacheContent = $templateCache->get();

        $templateItemList = '';

        foreach ($this->breadcrumb as $breadcrumb) {
            if (!empty($breadcrumb['link'])) {
                /** @var ContainerExtensionTemplate $template */
                $template = Container::get('ContainerExtensionTemplate');
                $template->set($templateCacheContent['breadcrumb.html.item']);
                $template->assign('name',
                                  $breadcrumb['name']);
                $template->assign('link',
                                  $breadcrumb['link']);
            }
            else {
                $template = Container::get('ContainerExtensionTemplate');
                $template->set($templateCacheContent['breadcrumb.html.nolink']);
                $template->assign('name',
                                  $breadcrumb['name']);
            }

            $template->assign('separator',
                              ContainerFactoryLanguage::get('/ContainerIndexPageBreadcrumb/separator'));

            $template->parseString();
            $templateItemList .= $template->get();
        }

        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['breadcrumb.html']);
        $template->assign('content',
                          $templateItemList);

        $template->parse();

        return $template->get();
    }

    public function addBreadcrumbItem(string $name, string $link = ''): void
    {
        $this->breadcrumb[] = [
            'name' => $name,
            'link' => $link,
        ];
    }

}
