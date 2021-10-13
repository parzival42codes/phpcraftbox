<?php

class CoreDebugProfiler_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->setEvent('/__open',
                        'CoreDebugProfiler_event',
                        'doProfilingOpen');

        $this->setEvent('/__close',
                        'CoreDebugProfiler_event',
                        'doProfilingClose');

    }


}
