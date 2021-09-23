<?php

class ContainerExtensionTemplateParseHelperDialog_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->importMeta();
        $this->setEvent('/ContainerIndexPage/Template/Positions',
                        'ContainerExtensionTemplateParseHelperDialog_event',
                        'insertTemplateDialog');

    }


}
