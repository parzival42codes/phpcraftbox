<?php

abstract class ConsoleOutput_abstract extends Base
{

    abstract function formatMessage(string $message, string $colorForeground, string $colorBackground): string;

    abstract function step(object $object, int $i, array $progressData, bool $step, float $ms, array $messages, bool $isFinal = false): void;

    abstract function error(Throwable $exception): void;

}
