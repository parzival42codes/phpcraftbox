<?php

class ContainerFactoryExceptioncatch extends Base
{

    protected bool       $isException = false;
    protected ?Throwable $exception;
    protected      $return      = [];

    public function __construct(callable $callback, ...$parameter)
    {
        $eData = self::tryCatch($callback,
            ...
                                $parameter);

        $this->isException = $eData['isException'];
        $this->exception   = $eData['exception'];
        $this->return      = $eData['return'];

        if ($eData['isException'] === true) {

            // Haut Class raus ???
            CoreDebug::setRawDebugData(__CLASS__,
                                       [
                                           'class'     => get_class($this->exception),
                                           'message'   => $this->exception->getMessage(),
                                           'parameter' => (method_exists($this->exception,
                                                                         'getParameter') ? $this->exception->getParameter() : ''),
                                           'file'      => $this->exception->getFile(),
                                           'line'      => $this->exception->getLine(),
                                           'backtrace' => $this->exception->getTrace(),
                                       ]);

        }
    }

    public static function tryCatch(callable $callback, ...$parameter): array
    {
        $return = [
            'isException' => false,
            'exception'   => null,
            'return'      => null,
        ];

        try {
            $return['return'] = call_user_func_array($callback,
                                                     $parameter);
        } catch (Exception $e) {
            $return['isException'] = true;
            $return['exception']   = $e;
            throw $e;
            //simpleDebugDump($return);
            //eol();

            // simpleDebugDump(get_class($e));
            //simpleDebugDump($e->getMessage());
            //echo \ContainerHelperView::convertBacktraceView($e->getTrace());
            //die();
        } catch (Error $e) {
            $return['isException'] = true;
            $return['exception']   = $e;
            throw $e;
        }

        return $return;
    }

    public function hasException(): bool
    {
        return $this->isException;
    }

    public function getException(): Throwable
    {
        return $this->exception;
    }

    public function getReturn(): array
    {
        return $this->return;
    }

    public function getFile(): ?string
    {
        if ($this->isException === true) {
            return $this->exception->getFile();
        }
        else {
            return null;
        }
    }

    public function getLine(): ?int
    {
        if ($this->isException === true) {
            return $this->exception->getLine();
        }
        else {
            return null;
        }
    }

    public function getTrace(): ?array
    {
        if ($this->isException === true) {
            return $this->exception->getTrace();
        }
        else {
            return null;
        }
    }

}

//Database::_construct($Database);

