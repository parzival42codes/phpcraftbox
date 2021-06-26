<?php

class ContainerExtensionTemplateParseCreateFormElementFile extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get(): string
    {
        $attribut = $this->getStdAttribut();

        $attribut->set('type',
                       null,
                       'file');

        $attribut->set('accept',
                       null,
                       '');

        $this->doModifier($attribut);

        return $this->getStdInputTemplate($attribut);

    }

    public function response(): void
    {

    }

}
