<?php

class ContainerExtensionTemplateParseCreateFormElementDate extends
    ContainerExtensionTemplateParseCreateFormElement_abstract
{
    public function get():string
    {
        $attribut = $this->getStdAttribut();

        $attribut->set('type',
                       null,
                       'date');

        $this->doModifier($attribut);

        return $this->getStdInputTemplate($attribut);
    }

}
