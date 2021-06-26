<?php

/**
 * Class ContainerExtensionTemplateParseCreateFormModifyValidatorPassword
 */
class ContainerExtensionTemplateParseCreateFormModifyValidatorPassword extends
    ContainerExtensionTemplateParseCreateFormModify_abstract
{

    public function modify(): void
    {
        if ($this->element instanceof ContainerExtensionTemplateParseCreateFormElement_abstract) {
            $info      = $this->element->getInfo();
            $addToInfo = [];
            if (($this->parameter['length'] ?? false)) {
                $addToInfo[] = sprintf(ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateFormModifyValidatorPassword/password/require/length'),
                                       $this->parameter['length']);
            }
            if (($this->parameter['uppercase'] ?? false)) {
                $addToInfo[] = ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateFormModifyValidatorPassword/password/require/uppercase');
            }
            if (($this->parameter['lowercase'] ?? false)) {
                $addToInfo[] = ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateFormModifyValidatorPassword/password/require/lowercase');
            }
            if (($this->parameter['spezial'] ?? false)) {#
                $addToInfo[] = ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateFormModifyValidatorPassword/password/require/spezial');
            }
            if (($this->parameter['number'] ?? false)) {#
                $addToInfo[] = ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateFormModifyValidatorPassword/password/require/number');
            }
            $this->element->setInfo($info . ' ' . implode(' ',
                                                          $addToInfo));
        }
        elseif ($this->element instanceof ContainerExtensionTemplateParseCreateFormResponse) {

            $passwordCheck = true;

            $regexTest = [];
            if (($this->parameter['uppercase'] ?? false)) {
                if (
                    preg_match("~([A-Z]+)~",
                               $this->responseValue) === 0
                ) {
                    $passwordCheck = false;
                }
            }
            if (($this->parameter['lowercase'] ?? false)) {
                if (
                    preg_match("~([a-z]+)~",
                               $this->responseValue) === 0
                ) {
                    $passwordCheck = false;
                }
                $regexTest[] = 'a-z';
            }
            if (($this->parameter['spezial'] ?? false)) {
                if (
                    preg_match("~([\$\%\§\_\-\.\:\+\#\!\@]+)~",
                               $this->responseValue) === 0
                ) {
                    $passwordCheck = false;
                }
            }
            if (($this->parameter['number'] ?? false)) {
                if (
                    preg_match("~([0-9]+)~",
                               $this->responseValue) === 0
                ) {
                    $passwordCheck = false;
                }
            }

            if ($passwordCheck === false) {
                $this->element->setError($this->responseKey,
                                         ContainerFactoryLanguage::get('/ContainerExtensionTemplateParseCreateFormModifyValidatorPassword/decorator/error/element'));
            }

        }
    }


}



