<?php

abstract class ApplicationSearch_abstract extends Base
{
    public abstract function getForm(ContainerExtensionTemplateParseCreateForm_helper $formHelper): string;
}
