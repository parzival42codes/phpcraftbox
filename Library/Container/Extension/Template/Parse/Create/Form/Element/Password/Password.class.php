<?php

class ContainerExtensionTemplateParseCreateFormElementPassword extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get():string
    {
        $attribut = $this->getStdAttribut();

        $attribut->set('type',
                       null,
                       'password');

        $this->doModifier($attribut);

        return $this->getStdInputTemplate($attribut);

    }

}
