<?php declare(strict_types=1);

class ApplicationUser_widget_link extends ContainerExtensionTemplateParseInsertWidget_abstract
{
    public function get(): string
    {
        if (ContainerFactorySession::check()) {
            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            Core::getRootClass(__CLASS__),
                                            'widget.link.session');

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->get()['widget.link.session']);
            $template->parse();

            return $template->get();
        }
        else {
            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            Core::getRootClass(__CLASS__),
                                            'widget.link.login');

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->get()['widget.link.login']);
            $template->parse();

            return $template->get();
        }
    }

}
