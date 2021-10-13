<?php

class ContainerExtensionTemplateParseCreateFormElementSelect extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get(): string
    {
        $attribut = $this->getStdAttribut();

        $attribut->set('class',
                       true,
                       'ContainerExtensionTemplateParseCreateForm-select');

        $this->doModifier($attribut);

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'select,option');
        $templates     = $templateCache->get();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templates['select']);
        $template->assign('attribut',
                          $attribut->getHtml());

        $options = $this->getParameter(0);

        $optionsCollect = '';
        foreach ($options as $optionKey => $optionValue) {
            /** @var ContainerExtensionTemplate $templateOption */
            $templateOption = Container::get('ContainerExtensionTemplate');
            $templateOption->set($templates['option']);
            $templateOption->assign('value',
                                    $optionKey);
            $templateOption->assign('content',
                                    $optionValue);

            if ($this->value == $optionKey) {
                $templateOption->assign('selected',
                                        'selected="selected"');
            }
            else {
                $templateOption->assign('selected',
                                        '');
            }

            $templateOption->parse();
            $optionsCollect .= $templateOption->get();

        }

        $template->assign('options',
                          $optionsCollect);
        $template->parse();

        return $template->get();
    }

    public function response(): void
    {

    }

}
