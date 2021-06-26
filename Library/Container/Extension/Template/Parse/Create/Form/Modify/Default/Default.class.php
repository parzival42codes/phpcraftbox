<?php

class ContainerExtensionTemplateParseCreateFormModifyDefault extends
    ContainerExtensionTemplateParseCreateFormModify_abstract
{

    public function modify(): void
    {
        if ($this->element instanceof ContainerExtensionTemplateParseCreateFormElement_abstract) {
            if (empty($this->element->getValue())) {

                $this->element->setValue($this->parameter);
                $this->attribute->set('value',
                                      null,
                                      $this->parameter);
            }
        }


    }


}



