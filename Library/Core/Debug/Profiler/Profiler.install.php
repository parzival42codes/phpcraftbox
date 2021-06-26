<?php

class CoreDebugProfiler_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var Event_crud $crud */
            $crud = Container::get('Event_crud');
            $crud->setCrudPath('/__open');
            $crud->setCrudTriggerClass('CoreDebugProfiler_event');
            $crud->setCrudTriggerMethod('doProfilingOpen');

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var Event_crud $crud */
            $crud = Container::get('Event_crud');
            $crud->setCrudPath('/__close');
            $crud->setCrudTriggerClass('CoreDebugProfiler_event');
            $crud->setCrudTriggerMethod('doProfilingClose');

            $progressData['message'] = $crud->insert();

            /*$after*/
        });

    }



}
