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
        $conLength = 200;

        $propertyData = $object->getStepProperty($i);

        if ($isFinal === true) {
            unlink(CMS_PATH_STORAGE_CACHE . '/class/console/console_' . $consoleID . '.php');
            echo "\n\n";
            return;
        }

        echo str_repeat("\x08",
                        $conLength);

        $processMainPercent   = ceil($i * 100 / $object->getProgressCounter());
        $processMain          = ceil($processMainPercent / 5);
        $processMainRight     = (20 - $processMain);
        $processMainIndicator = str_repeat('#',
                                           $processMain);
        $processMainIndicator .= str_repeat(' ',
                                            $processMainRight);

        $processPartPercent   = ceil($propertyData['identifyCounter'] * 100 / $object->getStepMax($propertyData['identify']));
        $processPart          = ceil($processPartPercent / 5);
        $processPartRight     = (20 - $processPart);
        $processPartIndicator = str_repeat('#',
                                           $processPart);
        $processPartIndicator .= str_repeat(' ',
                                            $processPartRight);

        $message = strip_tags(implode('',
                                      $messages));
        $message = str_replace("\n",
                               ' ',
                               $message);
        $message = str_replace("\r",
                               ' ',
                               $message);

        $message = strtr($message,
                         [
                             "\n" => ''
                         ]);

        $output = '|' . $processMainIndicator . '| - ' . $processMainPercent . ' % / |' . $processPartIndicator . '| - ' . $processPartPercent . ' % ' . $message;

        if (strlen($output) > $conLength) {
            $output = substr($output,
                             0,
                ($conLength - 5));
            $output .= ' ...';
        }

        $outputLen = strlen($output);
        $output    .= str_repeat(' ',
            ($conLength - $outputLen));

        echo $output;

    }

}
