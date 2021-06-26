<?php declare(strict_types=1);


final class  CoreDebugDumpInteger extends CoreDebugDump_abstract_api
{

    public function execute(): void
    {
        $this->content = $this->dump;
        $this->title   .= ' | Integer';
    }
}
