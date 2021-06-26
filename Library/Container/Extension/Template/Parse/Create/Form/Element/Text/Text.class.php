<?php

class ContainerExtensionTemplateParseCreateFormElementText extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get():string
    {
        $attribut = $this->getStdAttribut();

        $attribut->set('type',
                       null,
                       'text');

        $this->doModifier($attribut);

        return $this->getStdInputTemplate($attribut);
    }

}
