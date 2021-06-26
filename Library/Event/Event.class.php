<?php

class Event
{

    const TRIGGER_OPEN   = '/__open';
    const TRIGGER_INIT   = '__init';
    const TRIGGER_METHOD = '__method';
    const TRIGGER_EVERY  = '__every';
    const TRIGGER_CLOSE  = '/__close';

    const EVERY_STEP_AND_SYSTEM = 0;
    const EVERY_STEP            = 1;
    const EVERY_SYSTEM          = 2;

    protected static array $eventContainer
        = [
            '/__open'  => [],
            '/__close' => [],
        ];

    protected static bool $eventImportActive = false;

    /**
     * @param string      $path
     * @param $object
     * @param array       $scope
     * @param mixed       ...$parameter
     */
    public static function triggerBase(string $path, object $object = null, array &$scope = [], ...$parameter): void
    {
        if (!defined('EVENT_ENABLE') || EVENT_ENABLE === false) {
            return;
        }

        if (self::$eventImportActive === true) {
            if (isset(self::$eventContainer[$path])) {
                foreach (self::$eventContainer[$path] as $triggering) {

                    call_user_func_array([
                                             $triggering[0],
                                             $triggering[1]
                                         ],
                                         [
                                             $triggering,
                                             $object,
                                             &$scope,
                                             ...$parameter
                                         ]);
                }

                \CoreDebug::setRawDebugData(__CLASS__,
                                            [
                                                'path' => $path,
                                            ],
                                            'trigger',
                                            true);

            }
        }
    }

    public static function trigger(string $path, ...$parameter): void
    {
        if (!defined('EVENT_ENABLE') || EVENT_ENABLE === false) {
            return;
        }

        if (self::$eventImportActive === true) {
            if (isset(self::$eventContainer[$path])) {
                foreach (self::$eventContainer[$path] as $triggering) {

                    call_user_func([
                                       $triggering[0],
                                       $triggering[1]
                                   ],

                        ...
                                   $parameter);
                }

                \CoreDebug::setRawDebugData(__CLASS__,
                                            [
                                                'path' => $path,
                                            ],
                                            'trigger',
                                            true);

            }
        }
    }

    /**
     * Attachment
     *
     * @param string   $path
     * @param callable $callable Attachment Function
     */
    public static function attach(string $path, callable $callable): void
    {
        self::$eventContainer[$path][]
            = $callable;

    }

    public static function importEvent(): void
    {
        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __METHOD__ . '#select',
                                true,
                                ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('event_attach');
        $query->select('crudPath',
                       'crudTriggerClass',
                       'crudTriggerMethod');

        $query->construct();
        $smtp = $query->execute();
        /** @var ePDOStatement $smtp */

        while ($smtpData = $smtp->fetch()) {
            self::attach($smtpData['crudPath'],
                         [
                             $smtpData['crudTriggerClass'],
                             $smtpData['crudTriggerMethod']
                         ]);
        }

        self::$eventImportActive = true;

    }


}

