<?php

class CoreErrorhandlerExceptionArgumentcounterror extends Base
{
    public function get(ArgumentCountError $exception): array
    {
        preg_match('/Too few arguments to function ([a-zA-Z].*?)\:\:([a-zA-Z0-9\_].*?)\(\), ([0-9].*?) passed/i',
                   $exception->getMessage(),
                   $matches);

        if (!empty($matches[1])) {
            $exceptionClassTarget = trim($matches[1]);
        }
        else {
            $exceptionClassTarget = $exception->getMessage();
        }

        /** @var ReflectionClass $exceptionClassTargetReflection */
        $exceptionClassTargetReflection = new \ReflectionClass($exceptionClassTarget);

        $exceptionClassTargetReflectionMethod = $exceptionClassTargetReflection->getMethod(trim($matches[2]));
        $docData                              = $exceptionClassTargetReflectionMethod->getDocComment();

        $sourceCode = \CoreDebug::getSourceCodeInFile($exceptionClassTargetReflection->getFileName(),
                                                      $exceptionClassTargetReflectionMethod->getStartLine(),
                                                      20);

        preg_match('/function(.*?)\{/si',
                   $sourceCode,
                   $matchesSourceCode);

        $matchesSourceCodeStrippedTags               = strip_tags($matchesSourceCode[0]);
        $matchesSourceCodeStrippedTagsRoeNumberClean = preg_replace('/(\s[0-9]+\s)/si',
                                                                    '',
                                                                    $matchesSourceCodeStrippedTags);

        return [
            'info'  => $exception->getMessage() . '<hr />' . (!empty($docData) ? $docData . '<hr />' : '') . $matchesSourceCodeStrippedTagsRoeNumberClean,
            'class' => __CLASS__,
            'ident' => 'ArgumentCountError',
            'key'   => 'ArgumentCountError'
        ];
    }
}
