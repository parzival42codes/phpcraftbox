<?php

class ContainerExtensionTemplateParseHelperTooltip_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
//        $this->importMeta();
        $this->importMetaFromModul();

        $this->setEvent('/ContainerIndexPage/Template/Positions',
                        'ContainerExtensionTemplateParseHelperTooltip_event',
                        'insertTemplateDialog');

    }


}
