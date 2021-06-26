<?php
declare(strict_types=1);

class ContainerExtensionTemplateInternalAssign extends Base
{
    protected array $data = [];

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;
    }


    public function get(?string $key = null)
    {
        return (($key === null) ? $this->data : ($this->data[$key] ?? null));
    }


    public function remove(string $key): void
    {
        unset($this->data[$key]);
    }

    public function clear(string $key): void
    {
        $this->data = [];
    }

    public function array(array $array): void
    {
        $this->data = array_merge($this->data,
                                  $array);
    }

}
