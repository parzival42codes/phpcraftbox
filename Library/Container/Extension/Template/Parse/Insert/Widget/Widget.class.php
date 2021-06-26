<?php declare(strict_types=1);

class ContainerExtensionTemplateParseInsertWidget extends ContainerExtensionTemplateParseInsert_abstract
{
    function parse(): string
    {
        $parameter = $this->getParameter();

        $widget = Container::get($parameter['class'] . '_widget_' . $parameter['widget'],
                                 $parameter);
        return $widget->get();
    }
}
