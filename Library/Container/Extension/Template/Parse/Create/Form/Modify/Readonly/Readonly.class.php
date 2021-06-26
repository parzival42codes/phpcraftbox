<?php

/**
 * Class ContainerExtensionTemplateParseCreateFormModifyOptional
 */
class ContainerExtensionTemplateParseCreateFormModifyReadonly extends
    ContainerExtensionTemplateParseCreateFormModify_abstract
{

    public function modify(): void
    {
        if ($this->element instanceof ContainerExtensionTemplateParseCreateFormElement_abstract) {
            $this->attribute->set('readonly',
                                  null,
                                  'readonly');
        }
    }

}



