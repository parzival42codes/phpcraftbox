<?php

/**
 * Class ContainerExtensionTemplateParseCreateFormModify_abstract
 */
abstract class ContainerExtensionTemplateParseCreateFormModify_abstract extends Base
{
    protected  $element;
    protected ContainerIndexHtmlAttribute                                                                                 $attribute;
    protected                                                                                                       $parameter;
    protected                                                                                                       $responseKey;
    protected                                                                                                       $responseValue;

    public function __construct( $element, ?ContainerIndexHtmlAttribute $attribut, $parameter = null, string $responseKey = null, $responseValue = null)
    {
        if ($attribut === null) {
            $attribut = Container::get('ContainerIndexHtmlAttribute');
        }

        $this->element       = $element;
        $this->attribute     = $attribut;
        $this->parameter     = $parameter;
        $this->responseKey   = $responseKey;
        $this->responseValue = $responseValue;

//        d(static::class);
//        d($this->element);
//        d($this->attribut);
//        d($this->parameter);
//        d($this->responseKey);
//        d($this->responseValue);

    }

    abstract public function modify(): void;


}
