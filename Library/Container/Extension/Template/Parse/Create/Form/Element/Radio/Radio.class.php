<?php

class ContainerExtensionTemplateParseCreateFormElementRadio extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get(): string
    {
        $text = $this->getParameter(0);

        d($text);
        d($this);
        eol();

        $radio = (string)$this->getParameter(1);

        $attribut = $this->getStdAttribut();

        $attribut->set('type',
                       '',
                       'radio');

        $attribut->set('value',
                       'value',
                       $radio);

        $this->doModifier($attribut);

        if ($this->value === $radio) {
            $attribut->set('checked',
                           null,
                           'checked="checked"');
        }
        else {
            $attribut->removeAttribute('checked');
        }

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'container');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['container']);
        $template->assign('text',
                          $text);
        $template->assign('attribut',
                          $attribut->getHtml());
        $template->assign('id',
                          $attribut->get('id',
                                         'id'));

        $template->parse();

        return $template->get();

    }

}
