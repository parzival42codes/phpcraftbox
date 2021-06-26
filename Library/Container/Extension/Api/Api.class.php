<?php

class ContainerExtensionApi extends Base
{

    protected string $modul       = '';
    protected  $modulObject = null;

    public function __construct(string $modul, string $part, ?array $parameter = null, array $data = [])
    {
        $this->modul       = $modul;
        $this->modulObject = Container::get(ucfirst($modul) . '_api' . '_' . strtolower($part),
                                            $parameter,
                                            $data);
    }

    public function get()
    {
        return $this->modulObject;
    }

}
