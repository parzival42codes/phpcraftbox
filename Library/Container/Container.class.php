<?php

class Container extends Base
{

    protected static array $globalContainer   = [];
    protected static array $instanceContainer = [];
    protected array        $container         = [];

    public static function getInstance(string $index, ...$parameter): object
    {
        if (isset(self::$instanceContainer[$index]) === false) {
            self::$instanceContainer[$index] = self::get($index,
                ...
                                                         $parameter);
        }
        return self::$instanceContainer[$index];
    }

    public static function get(string $index, ...$parameter)
    {
        if (isset(self::$globalContainer[$index])) {
            return self::$globalContainer[$index];
        }

        if (class_exists($index)) {

            $object = new $index(...
                $parameter);

            if ($object instanceof Base) {
                $object->___setRootClass(Core::getRootClass(get_class($object)));
            }

            if (
            !method_exists($object,
                           '___get')
            ) {
                return $object;
            }
            else {
                return $object->___get(...
                    $parameter);
            }
        }
        else {
//            var_dump($index);
//            var_dump(Core::getPHPClassFileName($index));
//            var_dump(is_file(Core::getPHPClassFileName($index)));
//            eol();

            throw new DetailedException('classNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'class' => $index,
                                                'path'  => Core::getPHPClassFileName($index),
                                            ]
                                        ]);
        }
    }

    public static function set(string $key, $content): void
    {
        self::$globalContainer[$key] = $content;
    }

}
