<?php

require(dirname(__FILE__) . '/Core/Errorhandler/Errorhandler.class.php');

class MessageException extends Exception
{

    protected string $class     = '';
    protected array  $parameter = [];
    protected int    $deph      = 0;

    public function __construct(string $message, int $code = 0, Exception $previous = null, array $parameter = [], int $deph = 0)
    {
        $this->parameter = $parameter;
        $this->deph      = $deph;
        $this->class     = Core::identifyFileClass($this->getFile());

        parent::__construct($message,
                            $code,
                            $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getParameter(): array
    {
        return $this->parameter;
    }

    public function getDeph(): int
    {
        return $this->deph;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

}

class DetailedException extends MessageException
{

}

function simpleCaptureException(Throwable $exception): void
{
    require_once CMS_PATH_LIBRARY_CONTAINER . '/Helper/View/View.class.php';
    require_once CMS_PATH_LIBRARY_CONTAINER . '/Factory/File/File.class.php';

//    simpleDebugDump($exception);

//    d(CMS_PATH_LIBRARY_CONTAINER . '/Extension/Extern/Style/Style.error.css');
    d($exception);

    $output = '<style>';
    $output .= file_get_contents(CMS_PATH_LIBRARY_CONTAINER . '/Extern/Style/Style.error.css');
    $output .= '</style>';

    echo $output;
    echo CoreErrorhandler::doExceptionView($exception);

    die();

}

set_exception_handler('simpleCaptureException');
