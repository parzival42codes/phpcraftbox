<?php

final class  CoreDebugDump
{
    static bool $showError   = false;
    static int  $memoryLimit = 0;

    public static function dump($dump, string $info = ''): void
    {

        CoreDebug::setRawDebugData('CoreDebugDump',
                                   [
                                       'dump'      => $dump,
                                       'title'     => '',
                                       'info'      => $info,
                                       'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)
                                   ]);


    }
}

function debugDump($dump, string $info = ''): void
{
    CoreDebugDump::dump($dump,
                        $info);
}
