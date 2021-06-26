<?php

class CoreDebugProfiler_event
{
    public static function doProfilingOpen(array $triggering, object $object, array &$scope): void
    {

        if (
        class_exists($triggering[0],
                     false)
        ) {
            if (
            in_array($triggering[1],
                     get_class_methods($triggering[0]))
            ) {
                $profilerScope                = Container::get('CoreDebugProfilerScope',
                                                               $triggering,
                                                               $object,
                                                               $scope);
                $scope                        = $profilerScope->getScope();
                $scope['_profiler']['object'] = $profilerScope;
            }

        }
    }

    public static function doProfilingClose(array $triggering, object $object, array &$scope): void
    {
        if (isset($scope['_profiler']['object'])) {
            $scope['_profiler']['object']->get($scope);

            unset($scope['_profiler']['object']);
            \CoreDebug::setRawDebugData(($scope['_profiler']['data']['_class'] ?? $scope['_meta']['class']),
                $scope['_profiler'],
                ($scope['_scope_raw_key'] ?? null),
                ($scope['_scope_raw_key_array'] ?? false));
        }

    }

}
