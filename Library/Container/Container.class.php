<?php

class Container
{

    protected static array $globalContainer   = [];
    protected static array $instanceContainer = [];

    protected array             $instances = [];
    protected static ?Container $dic       = null;

    public function __construct()
    {

    }

    public function setDIC($index, $value)
    {
        $this->instances[$index] = $value;
        return $this->instances[$index];
    }

    public function getDIC($index, $parameter = [])
    {
        if (isset($this->instances[$index])) {
            if ($this->instances[$index] instanceof Closure) {
                return $this->instances[$index]($parameter);
            }

            return $this->instances[$index];
        }

        $reflector = new ReflectionClass($index);
        d($reflector->isInstantiable());
        $constructor = $reflector->getConstructor();

        d($constructor);

        d($constructor->getParameters());

        d($reflector);

    }

    public static function DIC(): Container
    {
        if (!self::$dic instanceof Container) {
            self::$dic = new Container();
        }

        return self::$dic;
    }

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
