<?php

class ContainerFactoryReflection extends Base
{

    protected static array $reflectionData         = [];
    protected string       $class                  = '';
    protected array        $reflectionClassComment = [];

    public function __construct(string $class)
    {
        $this->class = $class;

        if (class_exists($class)) {

            if (isset(self::$reflectionData[$class])) {
                return;
            }

            self::$reflectionData[$class] = [
                'properties' => [],
                'methods'    => [],
                'class'      => [],
            ];

            $reflectionClass = new \ReflectionClass($class);

            $this->reflectionClassComment = Core::convertClassDocBlock($reflectionClass->getDocComment());

            $reflectionClassMethods = $reflectionClass->getMethods();

            foreach ($reflectionClassMethods as $reflectionClassMethodsItem) {
                $reflectionMethodComment                                                    = $reflectionClass->getMethod($reflectionClassMethodsItem->name)
                                                                                                              ->getDocComment();
                self::$reflectionData[$class]['methods'][$reflectionClassMethodsItem->name] = \Core::convertClassDocBlock($reflectionMethodComment);
            }

            $reflectionClassProperties = $reflectionClass->getProperties();

            foreach ($reflectionClassProperties as $reflectionClassPropertiesItem) {
                $reflectionMethodComment                                                          = $reflectionClass->getProperty($reflectionClassPropertiesItem->name)
                                                                                                                    ->getDocComment();
                self::$reflectionData[$class]['properties'][$reflectionClassPropertiesItem->name] = \Core::convertClassDocBlock($reflectionMethodComment);

            }

            self::$reflectionData[$class]['class'] = \Core::convertClassDocBlock($reflectionClass->getDocComment());
        }
        else {
            throw new DetailedException('reflectionClassNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'class' => $class,
                                            ]
                                        ]);
        }

    }

    public function getProperties():array
    {
        return self::$reflectionData[$this->class]['properties'];
    }

    public function getMethods():array
    {
        return self::$reflectionData[$this->class]['methods'];
    }

    public function getClass():string
    {
        return self::$reflectionData[$this->class]['class'];
    }

    /**
     * @return array
     */
    public function getReflectionClassComment(): array
    {
        return $this->reflectionClassComment;
    }

    /**
     * @param array$reflectionClassComment
     */
    public function setReflectionClassComment(array $reflectionClassComment): void
    {
        $this->reflectionClassComment = $reflectionClassComment;
    }

}
