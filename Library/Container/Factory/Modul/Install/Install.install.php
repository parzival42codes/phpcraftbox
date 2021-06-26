<?php

class ContainerFactoryModulInstall_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $this->finalStep();
    }

    public function uninstall(): void
    {
        $this->finalStep();
    }

    public function update(): void
    {
        // TODO: Implement install() method.
    }

    public function refresh(): void
    {
        // TODO: Implement install() method.
    }

    public function activate(): void
    {
        $this->finalStep();
    }

    public function deactivate(): void
    {
        $this->finalStep();
    }

    public function repair(): void
    {
        // TODO: Implement install() method.
    }

    protected function finalStep(): void
    {
        $this->installFunction(function () {
            /** @var array $data */ /*$before*/

            $progressData['message'] = '* Final Step *';

            /*$after*/
        });
    }

}
