<?php declare(strict_types=1);


final class  CoreDebugDumpBoolean extends CoreDebugDump_abstract_api
{

    public function execute(): void
    {
        $this->title .= ' | ' . (($this->dump === true) ? 'true' : 'false') . ' | Boolean';
    }
}
