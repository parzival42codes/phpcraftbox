<?php declare(strict_types=1);


final class  CoreDebugDumpString extends CoreDebugDump_abstract_api
{

    public function execute(): void
    {
        $strLength = strlen($this->dump);
        $dumpData  = htmlentities($this->dump);

        $this->content = '<pre>' . (($strLength < 2000) ? $dumpData : '<details><summary> - Dump too big - klick to view - </summary><div>' . $dumpData . '</div></details>') . '</pre>';

        $strLengthNamed = ContainerHelperCalculate::calculateMemoryBytes($strLength);

        $this->title .= ' | String (' . $strLengthNamed . ')';
    }
}
