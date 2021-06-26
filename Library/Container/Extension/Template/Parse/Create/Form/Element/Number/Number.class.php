<?php

class ContainerExtensionTemplateParseCreateFormElementNumber extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get():string
    {
        $attribut = $this->getStdAttribut();

        $attribut->set('type',
                       null,
                       'number');

        $this->doModifier($attribut);

        return $this->getStdInputTemplate($attribut);

    }

}
