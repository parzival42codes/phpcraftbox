<?php

class ApplicationIndexErrorNotfound_install extends ContainerFactoryModulInstall_abstract
{

    public function install():void
    {
          $this->readLanguageFromFile('default');
        $this->setGroupAccess(Core::getRootClass(__CLASS__),
                              [
                                  1,
                                  2,
                                  3,
                                  4
                              ]);
    }



}
