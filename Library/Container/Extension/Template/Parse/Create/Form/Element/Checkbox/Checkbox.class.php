<?php

class ContainerExtensionTemplateParseCreateFormElementCheckbox extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get(): string
    {
        $checkbox = $this->getParameter(0);

        $attribut = $this->getStdAttribut();

        $attribut->set('type',
                       '',
                       'checkbox');

        $nameElement = $attribut->get('name',
                                      'name');

        $attributOld = clone $attribut;

        $this->doModifier($attribut);

        $value = $this->value;
        if (!is_array($value)) {
            $value = [$value];
        }


        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'container,checkbox');
        $templates     = $templateCache->get();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templates['container']);
        $template->assign('attribut',
                          $attribut->getHtml());

        $checkboxCollect = '';
        $checkboxCount   = 0;

        foreach ($checkbox as $checkboxValue => $checkboxContent) {

            if ($checkboxCount > 0) {
                $attributObject = $attributOld;
            }
            else {
                $attributObject = $attribut;
            }

            $checkboxCount++;

            $id = $nameElement . 'Checkbox' . $checkboxCount;

            $attributObject->set('id',
                                 null,
                                 $id);

            $name = $nameElement . '[]';

            $attributObject->set('name',
                                 null,
                                 $name);

            $attributObject->set('value',
                                 null,
                                 $checkboxValue);

            if (
                is_array($value) && in_array($checkboxValue,
                                             $value)
            ) {
                $attributObject->set('checked',
                                     null,
                                     'checked="checked"');
            }
            else {
                $attributObject->removeAttribute('checked');
            }

            /** @var ContainerExtensionTemplate $templateOption */
            $templateOption = Container::get('ContainerExtensionTemplate');
            $templateOption->set($templates['checkbox']);
            $templateOption->assign('attribut',
                                    $attributObject->getHtml());
            $templateOption->assign('id',
                                    $id);
            $templateOption->assign('content',
                                    $checkboxContent);

            $templateOption->parse();
            $checkboxCollect .= $templateOption->get();

        }

        $template->assign('radio',
                          $checkboxCollect);
        $template->parse();

        return $template->get();

    }

    public function response(): void
    {

    }

}
