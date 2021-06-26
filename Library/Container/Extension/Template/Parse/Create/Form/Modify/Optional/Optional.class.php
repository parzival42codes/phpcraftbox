<?php

/**
 * Class ContainerExtensionTemplateParseCreateFormModifyOptional
 */
class ContainerExtensionTemplateParseCreateFormModifyOptional extends
    ContainerExtensionTemplateParseCreateFormModify_abstract
{

    public function modify(): void
    {
        if ($this->element instanceof ContainerExtensionTemplateParseCreateFormElement_abstract) {
            $label = $this->element->getLabel();
            $label .= ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateFormModifyOptional/decorator/label/after');
            $this->element->setLabel($label);
        }
    }


}



