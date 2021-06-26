<?php

class ContainerExtensionTemplateParseCreateFormModifyValidatorEmail extends
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
            if (
                !empty($this->responseValue) && !filter_var($this->responseValue,
                                                            FILTER_VALIDATE_EMAIL)
            ) {
                /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
                $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                                Core::getRootClass(__CLASS__),
                                                'default');

                /** @var ContainerExtensionTemplate $template */
                $template = Container::get('ContainerExtensionTemplate');
                $template->set($templateCache->getCacheContent()['default']);
                $template->assign('message',
                                  ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateFormModifyValidatorEmail/message/emailFail'));
                $template->parseString();

                $this->element->setError($this->responseKey,
                                         $template->get());
            }
        }

    }


}



