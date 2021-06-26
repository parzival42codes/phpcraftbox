<?php

/**
 * Class ContainerExtensionTemplateParseCreateFormModifyValidatorRequired
 */
class ContainerExtensionTemplateParseCreateFormModifyValidatorRequired extends
    ContainerExtensionTemplateParseCreateFormModify_abstract
{

    public function modify(): void
    {
        if ($this->element instanceof ContainerExtensionTemplateParseCreateFormElement_abstract) {
            $this->attribute->set('required',
                                  null,
                                  '');

        }
        elseif ($this->element instanceof ContainerExtensionTemplateParseCreateFormResponse) {
            if (empty($this->responseValue)) {
                $this->element->setError($this->responseKey,
                                         ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateFormModifyValidatorRequired/decorator/error/element'));
            }
        }
    }


}



