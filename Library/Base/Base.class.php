<?php

abstract class Base
{
    protected string $rootClass = '';

    public function __call(string $name, array $arguments)
    {
        $methodeName = '_' . $name;

        if (
        method_exists($this,
                      $methodeName)
        ) {
            $scope = [];

            array_unshift($arguments,
                          false);
            $argumentsScope = $arguments;
            array_shift($argumentsScope);

            $arguments[0]               = &$scope;
            $arguments[0]['_arguments'] = $argumentsScope;
            $arguments[0]['_meta']      = [
                'class'     => get_class($this),
                'method'    => $methodeName,
                'backtrace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),
            ];
            unset($argumentsScope);

            \Event::triggerBase(\Event::TRIGGER_OPEN,
                            $this,
                            $scope);

            $returnValue = call_user_func_array([
                                                    $this,
                                                    $arguments[0]['_meta']['method']
                                                ],
                                                $arguments);

            \Event::triggerBase(\Event::TRIGGER_CLOSE,
                            $this,
                            $scope);

            return $returnValue;
        }
        else {
            throw new DetailedException('classMethodNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'name'       => $name,
                                                'arguments ' => $arguments,
                                            ]
                                        ]);

        }
    }

    /**
     * @return string
     */
    public function ___getRootClass(): string
    {
        return $this->rootClass;
    }

    /**
     * @param string $rootClass
     */
    public function ___setRootClass(string $rootClass): void
    {
        $this->rootClass = $rootClass;
    }

}
