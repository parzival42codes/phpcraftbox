<?php

class EventItem
{

    static array $eventItemContainer = [];

    public static function attach(string $event, callable $eventFunction): void
    {
        self::$eventItemContainer[$event][] = $eventFunction;
    }

    public static function trigger(object $object, array &$scope, string $event, array ...$parameter): void
    {
        if (isset(self::$eventItemContainer[$event])) {
            foreach (self::$eventItemContainer[$event] as $eventItem) {
                $exceptioncatch = Container::get('Exceptioncatch',
                    function (string $eventItem, object $object, array &$scope, array $parameter) {
                        array_unshift($parameter,
                                      $object);
                        array_unshift($parameter,
                                      $scope);
                        call_user_func_array($eventItem,
                                             $parameter);
                    },
                                                 $eventItem,
                                                 $object,
                                                 $scope,
                                                 $parameter);

                if ($exceptioncatch->hasException() === true) {
                    CoreErrorhandler::trigger(__METHOD__,
                                              'eventItemFail',
                                              ['event' => $event]);
                }
            }
        }
    }

}
