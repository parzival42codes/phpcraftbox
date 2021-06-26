<?php

/**
 * Class ContainerExtensionTemplateParseCreateFormModifyDisabled
 */
class ContainerExtensionTemplateParseCreateFormModifyDisabled extends
    ContainerExtensionTemplateParseCreateFormModify_abstract
{

    public function modify(): void
    {
        if ($this->element instanceof ContainerExtensionTemplateParseCreateFormElement_abstract) {
            $this->attribute->set('disabled',
                                  null,
                                  'disabled');
        }
    }

}



