<?php

class ContainerExtensionTemplateParseHelperDialog_install extends ContainerFactoryModulInstall_abstract
{

   public function install(): void
    {
        $this->importMeta();

        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            /** @var Event_crud $crud */
            $crud = Container::get('Event_crud');
            $crud->setCrudPath('/ContainerIndexPage/Template/Positions');
            $crud->setCrudTriggerClass('ContainerExtensionTemplateParseHelperDialog_event');
            $crud->setCrudTriggerMethod('insertTemplateDialog');

            $progressData['message'] = $crud->insert();;


            /*$after*/
        });

    }



}
