<?php
declare(strict_types=1);

abstract class Custom_abstract extends Base
{
    abstract function getName(): string;

    abstract function getDescription(): string;

    abstract function getDependencies(): array;
}
