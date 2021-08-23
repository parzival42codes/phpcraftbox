<?php declare(strict_types=1);

class ContainerFactoryComment_report extends ApplicationAdministrationReport_abstract
{

    public function getCrud(): string
    {
        return 'ContainerFactoryComment_crud';
    }

    public function getContent(): string
    {
        return 'getCrudContent';
    }


}
