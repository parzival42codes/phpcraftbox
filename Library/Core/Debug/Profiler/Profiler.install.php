<?php

class CoreDebugProfiler_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        simpleDebugLog('1');

        $this->setEvent('/__close',
                        'CoreDebugProfiler_event',
                        'doProfilingClose');

        simpleDebugLog('2');

        $this->setEvent('/__open',
                        'CoreDebugProfiler_event',
                        'doProfilingOpen');

        simpleDebugLog('3');
    }



}
