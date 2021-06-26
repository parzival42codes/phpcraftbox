<?php

use JetBrains\PhpStorm\NoReturn;

function simpleDebugDump($Data, bool $backtrace = false): void
{
    $Backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

    if (
    class_exists('ContainerHelperView',
                 false)
    ) {
        $BacktraceView = ContainerHelperView::convertBacktraceView($Backtrace,
                                                                   false);
    }
    else {
        $BacktraceView = '<pre>' . var_export($Backtrace,
                                              true) . '</pre>';
        $BacktraceView = preg_replace('@([\/])@si',
                                      '$1&shy;',
                                      $BacktraceView);
    }

    $type = gettype($Data);
    switch ($type) {
        case 'object':
            $typeContent = simpleDebugDumpObject($Data);
            break;
        default:
            $typeContent = '<pre>' . htmlentities(var_export($Data,
                                                             true)) . '</pre>';
            break;
    }

    $simpleDebugTable = '<div style="width: 99%; border: 1px solid #000;padding: 2px;margin: 2px;overflow: hidden;font-family: verdana, arial, helvetica, sans-serif; font-size: small;">';
    $simpleDebugTable .= '    <div style="width: 100%;background: blue;color: white;border: 1px solid #000;padding: 4px;margin: 4px;font-weight: bold;font-size: medium;">';
    $simpleDebugTable .= $Backtrace[0]['file'] . ' # ' . $Backtrace[0]['line'];
    $simpleDebugTable .= '    </div>';
    $simpleDebugTable .= '    <div style="width: 100%; display: flex;">';
    $simpleDebugTable .= '        <div style="width: 100%;border: 1px solid #000;padding: 4px;margin: 4px;max-height: 250px;overflow: auto; flex: 3">';
    $simpleDebugTable .= $typeContent;
    $simpleDebugTable .= '         </div>';
    $simpleDebugTable .= '          <div style="width: 100%;border: 1px solid #000;padding: 4px;margin: 4px;max-height: 250px;overflow: auto;flex: 1;">';
    $simpleDebugTable .= $BacktraceView;
    $simpleDebugTable .= '          </div>';
    $simpleDebugTable .= '    </div>';
    $simpleDebugTable .= '</div>';

    echo $simpleDebugTable;


}

function simpleDebugLog($content): void
{
    $Backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

    if (
    class_exists('ContainerHelperView',
                 false)
    ) {
        $BacktraceView = ContainerHelperView::convertBacktraceView($Backtrace,
                                                                   false);
    }
    else {
        $BacktraceView = '<pre>' . var_export($Backtrace,
                                              true) . '</pre>';
        $BacktraceView = preg_replace('@([\/])@si',
                                      '$1&shy;',
                                      $BacktraceView);
    }

    $type = gettype($content);
    switch ($type) {
        case 'object':
            $typeContent = simpleDebugDumpObject($content);
            break;
        default:
            $typeContent = '<pre>' . htmlentities(var_export($content,
                                                             true)) . '</pre>';
            break;
    }

    $simpleDebugTable = '<div style="width: 99%; border: 1px solid #000;padding: 2px;margin: 2px;overflow: hidden;font-family: verdana, arial, helvetica, sans-serif; font-size: small;">';
    $simpleDebugTable .= '    <div style="width: 100%;background: blue;color: white;border: 1px solid #000;padding: 4px;margin: 4px;font-weight: bold;font-size: medium;">';
    $simpleDebugTable .= $Backtrace[0]['file'] . ' # ' . $Backtrace[0]['line'];
    $simpleDebugTable .= '    </div>';
    $simpleDebugTable .= '    <div style="width: 100%; display: flex;">';
    $simpleDebugTable .= '        <div style="width: 100%;border: 1px solid #000;padding: 4px;margin: 4px;max-height: 250px;overflow: auto; flex: 3">';
    $simpleDebugTable .= $typeContent;
    $simpleDebugTable .= '         </div>';
    $simpleDebugTable .= '          <div style="width: 100%;border: 1px solid #000;padding: 4px;margin: 4px;max-height: 250px;overflow: auto;flex: 1;">';
    $simpleDebugTable .= $BacktraceView;
    $simpleDebugTable .= '          </div>';
    $simpleDebugTable .= '    </div>';
    $simpleDebugTable .= '</div>';

    /** @var ContainerFactoryLogDebug_crud $log */
    $log = Container::get('ContainerFactoryLogDebug_crud');
    $log->setCrudContent($simpleDebugTable);
    $log->insert();
}

function simpleDebugDumpObject(object $dump): string
{
    /** @var ReflectionClass $reflectionClass */
    $reflectionClass = new \ReflectionClass(get_class($dump));

    $reflectionClassProperties = $reflectionClass->getProperties();

    $result = '<div>'.get_class($dump).'</div>';
    $result .= '<div style="display: flex;"><div style="flex: 4;">';



    /** @var ReflectionProperty $reflectionClassPropertiesItem */
    foreach ($reflectionClassProperties as $reflectionClassPropertiesItem) {

        if (
            strpos($reflectionClassPropertiesItem->getName(),
                   '___') === false
        ) {

            $result .= '<p><b style="margin-left: 1em;">';

            $result .= $reflectionClassPropertiesItem->getName() . ' ';

            $propertyType = '?';
            if ($reflectionClassPropertiesItem->isPublic() === true) {
                $result .= '(public)';
            }
            elseif ($reflectionClassPropertiesItem->isProtected() === true) {
                $result .= '(protected)';
            }
            elseif ($reflectionClassPropertiesItem->isPrivate() === true) {
                $result .= '(private)';
            }

            $reflectionClassPropertiesItem->setAccessible(true);


            $result .= ($reflectionClassPropertiesItem->isStatic() ? ' (static) ' : '');
            $result .= '</b>: <pre>' . var_export($reflectionClassPropertiesItem->getValue($dump),
                                                  true) . '</pre>';

            $result .= '</p>';

        }
    }

    $result .= '</div><div style="flex: 1;">';

    $reflectionClassMethods = $reflectionClass->getMethods();

    foreach ($reflectionClassMethods as $reflectionClassMethodsItem) {

        if (
        str_contains($reflectionClassMethodsItem->getName(),
                     '___')
        ) {

            $result .= '<p><b>' . $reflectionClassMethodsItem->getName() . '</b> ';

            if ($reflectionClassMethodsItem->isPublic() === true) {
                $result .= '(public)';
            }
            elseif ($reflectionClassMethodsItem->isProtected() === true) {
                $result .= '(protected)';
            }
            elseif ($reflectionClassMethodsItem->isPrivate() === true) {
                $result .= '(private)';
            }

            $reflectionMethod = $reflectionClass->getMethod($reflectionClassMethodsItem->getName());

            $reflectionMethodParametersCollect = [];
            $reflectionMethodParameters        = $reflectionMethod->getParameters();
            foreach ($reflectionMethodParameters as $reflectionMethodParameter) {
                $reflectionMethodParametersCollect[] = $reflectionMethodParameter->getName();
            }#

            $result .= ' ' . (!empty($reflectionMethodParametersCollect) ? '$' . implode(', $',
                                                                                         $reflectionMethodParametersCollect) : '');
            $result .= '</p>';
        }
    }

    $result .= '</div></div>';

    return $result;

}

function simpleGetError(): void
{
    if (
    class_exists('CoreDebug',
                 false)
    ) {
        $errors = CoreDebug::getRawDebugData('CoreErrorhandler');
    }
    else {
        $errors = [];
    }

    echo '<table style="width: 99%; border: 1px solid #000;padding: 2px;margin: 2px;overflow: hidden;font-family: verdana, arial, helvetica, sans-serif; font-size: small;">';

    foreach ($errors as $errorCount => $error) {
        if (
        class_exists('ContainerHelperView',
                     false)
        ) {
            $BacktraceView = ContainerHelperView::convertBacktraceView($error['backtrace'],
                                                                       false);
        }
        else {
            $BacktraceView = '<pre>' . var_export($error['backtrace'],
                                                  true) . '</pre>';
            $BacktraceView = preg_replace('@([\/])@si',
                                          '$1&shy;',
                                          $BacktraceView);
        }

        echo '<tr style="border: 1px #000 solid;">
<td style="border: 1px #000 solid;padding: 5px;">' . $errorCount . '</td>
<td style="border: 1px #000 solid;padding: 5px;">' . $error['level'] . '</td>
<td style="border: 1px #000 solid;padding: 5px;">' . $error['message'] . '</td>
<td style="border: 1px #000 solid;padding: 5px;">' . $error['file'] . '</td>
<td style="border: 1px #000 solid;padding: 5px;">' . $error['line'] . '</td>
<td style="border: 1px #000 solid;padding: 5px;overflow: auto;height: 100px;"><details><summary>Backtrace</summary>' . $BacktraceView . '</details></td>
</tr>';

    }
    echo '</table>';

}

function simpleViewTime(): void
{
    $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

    if (
    class_exists('ContainerHelperCalculate',
                 false)
    ) {
        echo ContainerHelperCalculate::calculateMicroTimeDisplay(
                                   microtime(true) - CMS_SYSTEM_START_TIME) . ' sec. # ' . ($backtrace[0]['file'] ?? '?') . ' @ ' . ($backtrace[0]['line'] ?? '?') . ' <hr />';
    }
    unset($backtrace);
}

function eol(bool $viewError = false): void
{
    echo '<hr /><hr />';

    simpleViewTime();

    if ($viewError === true) {
        simpleGetError();
    }

    $Backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    if (php_sapi_name() !== 'cli') {

        echo '
<style>
.ViewhelperBacktraceView {
font-size: small;
}
</style>
<div style="font-size: small;">
            <hr />    <hr />
        End of Line !
    <hr />
    ' . ($Backtrace[0]['file'] ?? '') . ' # ' . ($Backtrace[0]['line'] ?? '') . '
    <hr style="clear: left;"/>
    ';

        if (
            class_exists('ContainerHelperView',
                         false) && method_exists('ContainerHelperView',
                                                 'convertBacktraceView')
        ) {
            echo ContainerHelperView::convertBacktraceView($Backtrace);
        }
        else {
            echo '<hr /><pre>' . var_export($Backtrace,
                                            true) . '</pre><hr />';
        }

        echo '</div>';

    }
    else {
        fwrite(STDERR,
               strip_tags('-------------------------------------------------------' . "\n"));
        fwrite(STDERR,
               strip_tags('End @ Line: ' . $Backtrace[0]['file'] . ' # ' . $Backtrace[0]['line'] . "\n"));
        fwrite(STDERR,
               strip_tags('-------------------------------------------------------' . "\n"));

    }
    exit();
}
