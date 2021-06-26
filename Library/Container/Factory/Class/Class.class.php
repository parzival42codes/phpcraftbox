<?php
declare(strict_types=1);

/**
 * Class ContainerFactoryClass
 */
class ContainerFactoryClass extends Base
{

    const ACCESS_PUBLIC    = 'public';
    const ACCESS_PROTECTED = 'protected';
    const ACCESS_PRIVATE   = 'private';

    protected string $className = '';
    protected string $dirname   = '';
    protected string $extends   = 'Base';
    protected string $classDoc  = '';

    protected array $const          = [];
    protected array $methods        = [];
    protected array $methodsPrepare = [];
    protected array $properties     = [];


    public function __construct(string $className, string $dirname = '', string $classDoc = '')
    {
        $this->className = $className;
        $this->dirname   = $dirname;
        $this->classDoc  = $classDoc;
    }

    /**
     * @param string $key     Constant Name
     * @param string $content Constant Code
     * @param string $doc     Doc
     */
    public function addConst(string $key, string $content, string $doc = ''): void
    {
        $this->const[$key] = (($doc === '') ? '' : PHP_EOL . $doc . PHP_EOL) . 'const ' . $key . ' = ' . $content . ';' . PHP_EOL;
    }

    public function removeConst(string $key): void
    {
        unset($this->const[$key]);
    }

    /**
     * @param string $key    Method Name
     * @param        $content
     * @param string $access Public | Protected | Private
     * @param false  $static is Static
     * @param string $doc    Doc
     */
    public function addProperty(string $key, $content, string $access = self::ACCESS_PUBLIC, bool $static = false, string $doc = ''): void
    {
        $this->properties[$key] = PHP_EOL . $doc . PHP_EOL . PHP_EOL . $access . (($static === false) ? '' : ' static ') . ' $' . $key . ' = ' . $content . ';';
    }

    public function removeProperty(string $key): void
    {
        unset($this->methods[$key]);
    }

    /**
     * @param string $key       Method Name
     * @param        $content
     * @param string $parameter Method Parameter
     * @param        $return
     * @param string $access    Public | Protected | Private
     * @param false  $static    Static Method
     * @param string $doc       DocEW
     *
     * @throws DetailedException
     * @throws ReflectionException
     */
    public final function addMethod(string $key, $content, string $parameter = '', ?string $return = 'void', string $access = self::ACCESS_PUBLIC, bool $static = false, string $doc = ''): void
    {
        if (is_string($content)) {
            $executeCode = $content;
        }
        elseif ($content instanceof Closure) {
            $reflection = new ReflectionFunction($content);

            if ($reflection === false) {
                throw new DetailedException('fileReflectionError');
            }

            $file = file($reflection->getFileName());

            if ($file === false) {
                throw new DetailedException('fileReadError',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    $reflection->getFileName()
                                                ]
                                            ]);
            }

            $length = $reflection->getEndLine() - $reflection->getStartLine() - 1;
            $slice  = array_slice($file,
                                  $reflection->getStartLine(),
                                  $length);

            if ($slice === false) {
                throw new DetailedException('sliceError',
                                            0,
                                            null,
                                            [
                                                'debug' => [
                                                    $file,
                                                    $reflection->getStartLine(),
                                                    $length,
                                                ]
                                            ]);
            }

            $executeCode = implode('',
                                   $slice);
        }
        else {
            throw new DetailedException('noExecuteCode',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'content' => $content,
                                            ]
                                        ]);
        }

        $executeCode = strtr($executeCode,
            (isset($this->methodsPrepare[$key]['replace']) ? $this->methodsPrepare[$key]['replace'] : []));

        // . (($return !== null) ? ': ' . $return : '')
        $this->methods[$key] = PHP_EOL . $doc . PHP_EOL . $access . (($static === false) ? '' : ' static ') . ' function ' . $key . '(' . $parameter . ')'  . '{' . PHP_EOL . $executeCode . PHP_EOL . '}';
    }

    public function prepareMethod(string $key, array $replace = []): void
    {

        if (isset($this->methods[$key])) {
            throw new DetailedException('methodExists',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'content' => $key,
                                            ]
                                        ]);
        }

        $this->methodsPrepare[$key] = [
            'replace' => $replace,
        ];

    }

    public function removeMethod(string $key): void
    {
        unset($this->methodsPrepare[$key]);
        unset($this->methods[$key]);
    }

    /**
     * @return string
     * @throws DetailedException
     */
    public function create(): string
    {
        $output = '<?php declare(strict_types=1);' . PHP_EOL;
        $output .= $this->classDoc . PHP_EOL;
        $output .= PHP_EOL . 'class ' . $this->className . ' extends ' . $this->extends . ' {';


        $output .= PHP_EOL . implode('',
                                     $this->const);

        $output .= PHP_EOL . implode('',
                                     $this->properties);

        $output .= PHP_EOL . implode('',
                                     $this->methods);

        $output .= PHP_EOL . '}' . PHP_EOL;

        $savePath = CMS_PATH_STORAGE_CACHE . '/class/' . $this->dirname . '/' . $this->className . '.php';
        Core::checkAndGenerateDirectoryByFilePath($savePath);

        $file = Container::get('ContainerFactoryFile',
                               $savePath,
                               true);
        $file->set($output);
        $file->setFileRights(0777);

        $file->save();

        return $output;
    }

    /**
     * @return string
     */
    public function getExtends(): string
    {
        return $this->extends;
    }

    /**
     * @param string $extends
     */
    public function setExtends(string $extends): void
    {
        $this->extends = $extends;
    }

}

