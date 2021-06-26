<?php declare(strict_types=1);


final class  CoreDebugDumpNull extends CoreDebugDump_abstract_api
{

    public function execute(): void
    {
        $this->title .= ' | Null';
    }
}
