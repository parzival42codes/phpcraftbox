<?php

class ContainerExtensionTemplateParseCreateFormElementInteger extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get(): string
    {
        $attribut = $this->getStdAttribut();

        $attribut->set('type',
                       null,
                       'int');

        $this->doModifier($attribut);

        return $this->getStdInputTemplate($attribut);

    }

}
