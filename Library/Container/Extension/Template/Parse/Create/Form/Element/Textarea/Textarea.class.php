<?php

class ContainerExtensionTemplateParseCreateFormElementTextarea extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get():string
    {
        $attribut = $this->getStdAttribut();

        $this->doModifier($attribut);

        $value = $attribut->get('value',
                                'value');
        $attribut->removeAttribute('value');

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');
        $templates     = $templateCache->getCacheContent();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templates['default']);
        $template->assign('attribut',
                          $attribut->getHtml());
        $template->assign('value',
                          $value);

        $template->parse();

        return $template->get();

    }

    public function response():void
    {
        // TODO: Implement response() method.
    }

}
