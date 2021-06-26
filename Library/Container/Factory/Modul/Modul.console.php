<?php

class ContainerFactoryModul_console extends Console_abstract
{

    public function prepareInstall()
    {

        simpleDebugLog($_POST);
        eol();


//        foreach ($this->module as $parameterKey => $parameterItem) {
//            /** @var ContainerFactoryModulInstall_abstract $installModule */
//
//            $installModule = Container::get($parameterItem . '_install',
//                                            $this);
//            $this->setProgressIdentify($parameterItem);
//            $installModule->install();
//
////            d($this->getClassConstructor());
//        }

    }


}
