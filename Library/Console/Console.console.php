<?php

class Console_console extends Console_abstract
{

    public function prepareInstall()
    {

        $this->console->addProgressFunction($function,
                                            [
                                                '/*$before*/' => '

        $data = ' . var_export($data,
                               true) . '
        ;
        ',
                                                '/*$after*/'  => '

                                                return $progressData;
                                                ',
                                            ]);


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
