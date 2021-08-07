<?php

class Container
{

    private static array $globalContainer   = [];
    private static array $instanceContainer = [];

    private array             $container = [];
    private static ?Container $dic       = null;

    public function __construct(array $container)
    {
        $this->container = $container;
    }

    public function setDIC(string $index, $value)
    {
        $this->container[$index] = $value;
        return $this->container[$index];
    }

    public function getDIC($index, $parameter = [])
    {
        if (isset($this->container[$index])) {
            if ($this->container[$index] instanceof Closure) {
                return $this->container[$index]($parameter);
            }

            return $this->container[$index];
        }

        $reflector = new ReflectionClass($index);
        d($reflector->isInstantiable());
        $constructor = $reflector->getConstructor();

        $parameter = $constructor->getParameters();
        foreach ($parameter as $parameterItem) {
            d($parameterItem->getName());
            d($parameterItem->getClass());
            d($parameterItem->getDeclaringClass());
            d($parameterItem->getDeclaringFunction());
//            d($parameterItem->getDefaultValue());
//            d($parameterItem->getDefaultValueConstantName());
            d($parameterItem->getPosition());

            $type = $parameterItem->getType();
            d($type->getName());
        }


        d($reflector);

    }

    public static function DIC(array $initDIC = []): Container
    {
        if (!self::$dic instanceof Container) {
            self::$dic = new Container($initDIC);
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
