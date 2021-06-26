<?php

/**
 * Class ContainerIndexTabItem
 * @method object setTitle (string $title) set the title
 * @method object setContent (string $content) set the content
 * @method string get () get the parsed content
 */


class ContainerIndexTabItem extends ContainerExtensionTemplate_abstract
{
    protected string                             $title           = '';
    protected string                             $content         = '';
    protected                         $id              = null;
    protected static ?ContainerExtensionTemplate $templateContent = null;
    protected ContainerExtensionTemplate         $template;

    public function __construct(?string $id = null)
    {
        $this->id = ($id ?? uniqid('tabUnique'));
    }

    public function _setTitle(array &$scope, string $title): void
    {
        $this->title = $title;
    }

    public function _setContent(array &$scope, string $content): void
    {
        $this->content = $content;
    }

    public function _get(array &$scope): string
    {

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'tab');
        $templates     = $templateCache->getCacheContent();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templates['tab']);

        $template->assign('tabID',
                          $this->id);
        $template->assign('tabTitle',
                          $this->title);
        $template->assign('tabContent',
                          $this->content);
        $template->parse();
        return $template->get();
    }

}
