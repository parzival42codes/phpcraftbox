<?php declare(strict_types=1);


final class  CoreDebugDumpDefault extends CoreDebugDump_abstract_api
{

    public function execute(): void
    {
        $dumpData      = htmlentities(var_export($this->dump,
                                                 true) ?? '');
        $this->content = '<pre>' . ((strlen($dumpData) < 2000) ? $dumpData : '<details><summary> - Dump too big - klick to view - </summary><div>' . $dumpData . '</div></details>') . '</pre>';

        $this->title .= ' | ' . ucfirst(gettype($this->dump)) . ' :: Default';
    }
}
