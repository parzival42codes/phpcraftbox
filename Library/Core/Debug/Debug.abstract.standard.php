<?php

abstract class CoreDebug_abstract_standard extends CoreDebug_abstract
{

    public function getHtml():string
    {        $returnOutput = '';
        foreach ($this->data as $elem) {
            $returnOutput .= var_export($elem,
                                        true);
        }

        return '<pre>' . $returnOutput . '</pre>';
    }

    public function getTitle(): string
    {
        return 'Standard: ' . get_called_class();
    }

}
