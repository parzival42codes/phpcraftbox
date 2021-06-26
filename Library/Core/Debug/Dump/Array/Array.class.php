<?php declare(strict_types=1);


final class  CoreDebugDumpArray extends CoreDebugDump_abstract_api
{

    public function execute(): void
    {
        $this->content = \ContainerHelperCode::viewArrayAsTable((array)$this->dump);
        $count         = substr_count($this->content,
                                      'data-row="key"');

        $this->title .= ' | ' . $count . ' | Array';
    }
}
