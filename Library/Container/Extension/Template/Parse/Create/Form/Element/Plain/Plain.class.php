<?php

class ContainerExtensionTemplateParseCreateFormElementPlain extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get():string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['default']);

        $attribut = $this->getStdAttribut();
        $this->doModifier($attribut);

        $template->assign('value',
                          $attribut->get('value'));

        $template->parse();

        return $template->get();
    }

}
