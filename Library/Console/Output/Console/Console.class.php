<?php

class ConsoleOutputConsole extends ConsoleOutput_abstract
{
    function formatMessage(string $message, string $colorForeground = null, string $colorBackground = null): string
    {
        return $message;
    }

    function error(Throwable $exception): void
    {
        d($exception);
    }

    public function step(object $object, int $i, array $progressData, bool $step, float $ms, array $messages, bool $isFinal = false, string $consoleID = ''): void
    {
        simpleDebugDump($propertyData = $object->getStepProperty($i));
    }

}
