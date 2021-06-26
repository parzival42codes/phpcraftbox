<?php

class ContainerIndexHtmlAttribute
{

    protected array $attribute = [];

    public function setSpecialData(string $key, string $value): void
    {
        $keyData  = 'data-' . $key;
        $keyClass = 'data-' . $key;

        $this->set($keyData,
                   $keyData,
                   $value);
        $this->set('class',
                   $keyClass,
                   $keyClass);
    }

    public function set(string $attribute,  $key, $value = ''): void
    {
        if ($key === null) {
            $key = $attribute;
        }

        if ($key === true) {
            $key = $value;
        }

        $this->attribute[$attribute][$key] = $value;
    }

    public function get($key = false, string $attribute = '')
    {
        return ($key === false ? $this->attribute : ($this->attribute[$attribute][$key]) ?? $this->attribute[$key][$key]);
    }

    public function remove(string $attribute, string $key): void
    {
        unset($this->attribute[$attribute][$key]);
    }

    public function removeAttribute(string $attribute): void
    {
        unset($this->attribute[$attribute]);
    }

    public function clear(string $attribute): void#
    {
        $this->attribute[$attribute] = [];
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        $attributesReturn = '';

        foreach ($this->attribute as $attributeKey => $attribute) {

            $attributeCollect = [];
            foreach ($attribute as $attributeItemKey => $attributeItem) {

                if (!is_array($attributeItem)) {

                    if ($attributeKey == $attributeItemKey) {
                        $attributeCollect[] = $attributeItem;
                    }
                    elseif ($attributeItemKey == $attributeItem) {
                        $attributeCollect[] = $attributeItem;
                    }
                    else {
                        $attributeCollect[] = $attributeItemKey . $attributeItem;
                    }

                }
            }

            $attributesReturn .= ' ' . $attributeKey . '="' . implode(' ',
                                                                      $attributeCollect) . '"';
        }

        return $attributesReturn;
    }

    public function scanHtmlAttributes(string $html): void
    {
        $htmlAttributes = [];
        preg_match_all('@([\_a-zA-Z].*?)=[\"\'](.*?)[\"\']@is',
                       $html,
                       $htmlAttributes,
                       PREG_SET_ORDER);

        foreach ($htmlAttributes as $htmlAttribute) {
            $this->set($htmlAttribute[1],
                       null,
                       $htmlAttribute[2]);
        }

    }

}
