<?php

class Custom_console extends Console_abstract
{

    public function prepareInstall()
    {
        $this->doWork('install',
                      'InActive');
    }

    public function prepareUninstall()
    {
        $this->doWork('uninstall',
                      'UnInstall');
    }

    public function prepareDeactivate()
    {
        $this->doWork('deactivate',
                      'InActive');
    }

    public function prepareActivate()
    {
        $this->doWork('activate',
                      'Active');
    }

    protected function doWork($action, $status)
    {
        /** @var ContainerFactoryRequest $requestClass */
        $requestClass = Container::get('ContainerFactoryRequest',
                                       ContainerFactoryRequest::REQUEST_POST,
                                       'parameter');

        if ($requestClass->exists()) {
            /** @var Custom_abstract $requestClassContainer */
            $requestClassContainer = Container::get($requestClass->get() . '_custom');

            $dependencies   = $requestClassContainer->getDependencies();
            $dependencies[] = 'ContainerFactoryModulInstall';

            foreach ($dependencies as $dependency) {

                $installModule = Container::get($dependency . '_install',
                                                $this);
                $this->setProgressIdentify($dependency);
                $installModule->$action();
            }

            /** @var Custom_crud $customCrud */
            $customCrud = Container::get('Custom_crud');
            $customCrud->setCrudIdent($requestClass->get());
            $customCrud->findById();
            $customCrud->setCrudStatus($status);
            $customCrud->update();
        }
        else {
            Container::getInstance('ContainerFactoryHeader')
                     ->set('#',
                           'HTTP/1.1 500 Internal Server Error');
            exit();
        }
    }

}
