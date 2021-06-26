<?php

class ePDO extends pdo
{

    public function __construct(string $dsn, string $user = NULL, string $pass = NULL, ?array $options = NULL)
    {
        parent::__construct($dsn,
                            $user,
                            $pass,
                            $options);
        $this->setAttribute(PDO::ATTR_STATEMENT_CLASS,
                            array(
                                'ePDOStatement',
                                array($this)
                            ));
    }

    public function exec($query)
    {
        $args  = func_get_args();
        $query = call_user_func_array(array(
                                          $this,
                                          'parent::exec'
                                      ),
                                      $args);
        return $query;
    }

}


class ePDOException extends Exception
{

    public function __construct(string $message, int $code = 0)
    {
        parent::__construct($message,
                            $code);
    }

}

class ePDOStatement extends PDOStatement
{

    private static ?Container $container     = null;
    private ePDO              $pdo;
    private array             $bindValueData = [];

    protected function __construct(ePDO $pdo)
    {
        if (self::$container === null) {
            self::$container = new \Container();
        }

        $this->pdo = $pdo;
    }

    public function execute($params = [])
    {
        $args = func_get_args();

        try {
            $query = call_user_func_array(array(
                                              $this,
                                              'parent::execute'
                                          ),
                                          $args);

        } catch (Throwable $e) {

            /** @var Exception $e */

            $Backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            $DebugTemp = [
                'getCode'    => $e->getCode(),
                'getMessage' => $e->getMessage(),
                'getQuery'   => $this->queryString,
                'getFile'    => \ContainerFactoryFile::getReducedFilename($Backtrace[0]['file']),
                'getLine'    => $Backtrace[0]['line'],
                'getArgs'    => var_export($this->bindValueData,
                                           true),
                'getTrace'   => var_export($Backtrace,
                                           true),
                'Info'       => '',
            ];

            if (class_exists('CoreErrorhandler') === true) {
                $DebugTemp['getTrace'] = '';

                simpleDebugDump($DebugTemp);

                throw new DetailedException('databaseExecuteError',
                                            0,
                                            null,
                                            [
                                                'debug' => $DebugTemp
                                            ]);
            }

            else {
                echo '<hr />DatabaseError<hr /><pre>' . stripslashes(var_export($DebugTemp,
                                                                                true)) . '</pre><hr />';
                //simpleGetError();
                die();
            }
        }

        $this->bindValueData
            = [];
        return $query;
    }

    public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
    {
        $args                            = func_get_args();
        $this->bindValueData[$parameter] = $value;

        if (is_array($value)) {
            throw new DetailedException('valueIsArray',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                $value,
                                                $this->queryString
                                            ]
                                        ]);
        }

        return call_user_func_array(array(
                                 $this,
                                 'parent::bindValue'
                             ),
                             $args);
    }

}
