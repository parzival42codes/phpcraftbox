<?php

class ContainerFactoryClassdocumentreflection extends Base
{

    protected static array $reflectionData = [];

    public function __construct()
    {

    }

    public function getReflectionClass(string $class):bool|array
    {
        $this->createReflection($class);

        return ((isset(self::$reflectionData[$class])) ? self::$reflectionData[$class] : false);
    }

    public function getReflectionClassMethod(string $class, string $method)
    {
        $class = $this->createReflection($class);
        if (isset(self::$reflectionData[$class][$method])) {
            return self::$reflectionData[$class][$method];
        }
        else {
            return false;
        }
    }

    public function createReflection(string $class): bool
    {
        if (class_exists($class)) {
            if (!isset(self::$reflectionData[$class])) {
                $reflectionClass = new \ReflectionClass($class);

                $reflectionClassMethods       = $reflectionClass->getMethods();
                self::$reflectionData[$class] = [];

                foreach ($reflectionClassMethods as $reflectionClassMethodsItem) {
                    $reflectionMethodComment = $reflectionClass->getMethod($reflectionClassMethodsItem->name)
                                                               ->getDocComment();
                    if ($reflectionMethodComment !== false) {
                        self::$reflectionData[$class][$reflectionClassMethodsItem->name] = \Core::convertClassDocBlock($reflectionMethodComment);
                    }
                }
            }
            return true;
        }
        else {
            return false;
        }
    }

}
