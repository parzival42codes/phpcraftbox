<?php declare(strict_types=1);

abstract class ApplicationAdministrationReport_abstract extends Base
{

    public function __construct()
    {

    }

    abstract function getCrud (): string;
    abstract function getContent (): string;

}
