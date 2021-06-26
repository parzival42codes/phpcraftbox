<?php

/**
 * Class ContainerExtensionTemplateParseCreateFormModifyValidatorRequired
 */
class ContainerExtensionTemplateParseCreateFormModifyValidatorEqual extends
    ContainerExtensionTemplateParseCreateFormModify_abstract
{

    public function modify(): void
    {
        if ($this->element instanceof ContainerExtensionTemplateParseCreateFormElement_abstract) {
//            $this->attribut->set('data-parsley-equalto',
//                                 null,
//                                 '#' . $this->parameter);

        }
        elseif ($this->element instanceof ContainerExtensionTemplateParseCreateFormResponse) {
               if ($this->responseValue != $this->element->get($this->parameter[0])) {
                $this->element->setError($this->responseKey,
                                         sprintf(ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateFormModifyValidatorEqual/decorator/error/element'),
                                                 $this->parameter[1]));
            }

        }
    }


}



