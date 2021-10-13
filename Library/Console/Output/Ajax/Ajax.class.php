<?php

class ConsoleOutputAjax extends ConsoleOutput_abstract
{
    function formatMessage(string $message, string $colorForeground = null, string $colorBackground = null): string
    {
        return '<span style="color:' . ($colorForeground ?? 'black') . ';background:' . ($colorBackground ?? 'transparent') . '">' . $message . '</span>';
    }

    function error(Throwable $exception): void
    {
        /** @var DetailedException $exception */
        if ($exception instanceof DetailedException) {
            $exceptionParameter = $exception->getParameter();
        }

        simpleDebugDump($exception);
        header('HTTP/1.1 500 Internal Server Error');
        exit();
    }

    public function step(object $object, int $i, array $progressData, bool $step, float $ms, array $messages, bool $isFinal = false, string $consoleID = ''): void
    {
        if ($step === true || $isFinal === true) {

            if ($isFinal === true) {
//              unlink(CMS_PATH_STORAGE_CACHE . '/class/console/console_' . $consoleID . '.php');
            }

            $propertyData = $object->getStepProperty($i);

            $output = [
                'status'      => (($isFinal === false) ? 'step' : 'final'),
                'messages'    => implode('<br />',
                                         array_reverse($messages)) . '<br />',
                'partCount'   => $propertyData['identifyCounter'],
                'partMax'     => $object->getStepMax($propertyData['identify']),
                'partPercent' => ceil($propertyData['identifyCounter'] * 100 / $object->getStepMax($propertyData['identify'])),
                'cguiCount'   => $i,
                'cguiMax'     => $object->getProgressCounter(),
                'cguiPercent' => ceil($i * 100 / $object->getProgressCounter()),
                'debug'       => [
                    'progressData' => $progressData,
                ]
            ];


            if (empty($object->getStepMax($propertyData['identify'])) || empty($propertyData['identifyCounter'])) {
                $output['partPercent'] = 0;
            }

            $outputJson = json_encode($output);

            if (json_last_error() !== 0) {
                echo json_encode([
                                     'status'  => 'error',
                                     'message' => json_last_error_msg(),
                                 ]);
                exit;
            }

//            var_dump($output);
//            var_dump(json_encode($output));
//            var_dump(json_last_error());
//            var_dump(json_last_error_msg());

            echo $outputJson;

            exit;
        }
    }

}
