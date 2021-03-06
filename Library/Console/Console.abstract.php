<?php

abstract class Console_abstract extends Base
{
    const OUTPUT_MODE_CONSOLE = 'Console';
    const OUTPUT_MODE_AJAX    = 'Ajax';

    protected string                $environment     = '';
    protected ContainerFactoryClass $classConstructor;
    protected int                   $progressCounter = 0;
    protected array                 $progressPartMax = [];

    protected string     $outputMode    = self::OUTPUT_MODE_CONSOLE;
    protected string     $action        = '';
    protected string     $methodPrepare = '';
    protected  $consoleID     = '';
    protected array      $parameter     = [];
    protected int        $progress      = 1;
    protected array      $progressData  = [];
    protected object     $outputObject;

    protected int    $progressIdentifyCounter = 0;
    protected string $progressIdentify        = '';

    public function __construct(string $consoleID, string $action, string $method, ...$parameter)
    {
        $this->consoleID = $consoleID;
        if ($this->consoleID == 0) {
            $this->consoleID = time();
        }

        $this->action    = $action;
        $this->parameter = $parameter;

        $this->methodPrepare = 'prepare' . ucfirst($method);

        if (
        !method_exists($this,
                       $this->methodPrepare)
        ) {
            echo 'Prepare for ' . $method . ' not found';
            exit();
        }

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __CLASS__ . '.create.console',
                                'cache');
        $query->query('CREATE TABLE IF NOT EXISTS `console` (
    `consoleId` VARCHAR (50)  NOT NULL,
    `progress` INT(11)  NOT NULL,
    `progressData` TEXT,
    `messages` TEXT,
    `dataVariableCreated` DATETIME
)');
        $query->execute();


        /** @var ContainerFactoryClass $classConstructor */
        $classConstructor       = Container::get('ContainerFactoryClass',
                                                 'console_' . $this->consoleID,
                                                 'console','
/**
/ * Class console_' . $this->consoleID.'
/ * @method mixed getStepProperty(string $key)
*/
        ');
        $this->classConstructor = $classConstructor;

        $this->classConstructor->addProperty('consoleID',
                                             '"' . $this->consoleID . '"',
                                             $this->classConstructor::ACCESS_PROTECTED);

        $this->classConstructor->addProperty('progress',
                                             0,
                                             $this->classConstructor::ACCESS_PROTECTED);
        $this->classConstructor->addProperty('progressMessages',
                                             '[]',
                                             $this->classConstructor::ACCESS_PROTECTED);

        $this->classConstructor->addProperty('outputObject',
                                             0,
                                             $this->classConstructor::ACCESS_PROTECTED);

        $this->classConstructor->addConst('OUTPUT_MODE_CONSOLE',
                                          '"Console"');

        $this->classConstructor->addConst('OUTPUT_MODE_AJAX',
                                          '"Ajax"');

        $this->classConstructor->addProperty('outputMode',
                                             'Console_abstract::OUTPUT_MODE_CONSOLE',
                                             $this->classConstructor::ACCESS_PROTECTED);

        $this->classConstructor->addMethod('setOutputMode',
            function (string $outputMode) {
                $this->outputMode = $outputMode;
            },
                                           'string $outputMode');

        $this->classConstructor->addMethod('getOutputMode',
            function () {
                return $this->outputMode;
            },
                                           '',
                                           'string');

        $this->classConstructor->addMethod('getProgressCounter',
            function () {
                return (int)$this->progressCounter;
            },
                                           '',
                                           'int');

        $this->classConstructor->addMethod('getStepProperty',
            function () {
                /** @var string $key */
                $propertyName = 'Step' . $key;
                if (
                property_exists($this,
                                $propertyName)
                ) {
                    return $this->$propertyName;
                }

                return null;
            },
                                           'string $key');

        $this->classConstructor->addMethod('getStepMax',
            function () {
                /** @var string $key */
                $propertyName = 'StepMax' . $key;
                if (
                property_exists($this,
                                $propertyName)
                ) {
                    return $this->$propertyName;
                }

                return null;
            },
                                           'string $key');

        $this->classConstructor->addMethod('__construct',
            function () {

                /** @var string $outputMethode */
                $this->outputObject = Container::get('ConsoleOutput' . $outputMethode);

                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __CLASS__ . '.insert.console.data',
                                        'cache',
                                        ContainerFactoryDatabaseQuery::MODE_SELECT);
                $query->setTable('console');
                $query->select('progress');
                $query->select('progressData');

                $query->setParameterWhere('consoleID',
                                          $this->consoleID);

                $query->construct();
                $smtp = $query->execute();

                $smtpData = $smtp->fetch();

                $this->progress = $smtpData['progress'];

                $this->progressDataQuery(unserialize($smtpData['progressData'] ?? []));
            },
                                           'string $outputMethode',
                                           null);

        $this->classConstructor->addMethod('writeDatabase',
            function () {

                /** @var ContainerFactoryDatabaseQuery $query */
                $query = Container::get('ContainerFactoryDatabaseQuery',
                                        __CLASS__ . '.insert.console.data',
                                        'cache',
                                        ContainerFactoryDatabaseQuery::MODE_UPDATE);
                $query->setTable('console');
                /** @var integer $i */
                $query->setUpdate('progress',
                    ($i + 2));
                /** @var array $progressData */
                $query->setUpdate('progressData',
                                  serialize($progressData));
                /** @var string $messages */

                $query->setParameter('messages',
                                     $messages);
                $query->setUpdate('messages',
                                  'messages || :messages_' . $query->getParameterCount(),
                                  true);

                $query->setParameterWhere('consoleID',
                                          $this->consoleID);

                $query->construct();
                $smtp = $query->execute();
                $smtp->closeCursor();
            },
                                           'array $progressData, int $i, string $messages');


        $this->classConstructor->addMethod('progressDataQuery',
            function () {

                $step                    = (int)$this->progress;
                $stepEnd                 = $step + Config::get('/environment/console/ajax_step');
                $msTime                  = microtime(true);
                $progressData['message'] = [];

                for ($i = $step; $i <= $this->progressCounter; $i++) {
                    try {
                        $progressData = call_user_func([
                                                           $this,
                                                           'Step' . $i
                                                       ],
                                                       $progressData);

                        $messageFormat = explode('|##|',
                            ($progressData['message'] ?? ''));

                        $messages[] = $i . ') ' . $this->outputObject->formatMessage($messageFormat[0],
                                ($messageFormat[1] ?? null),
                                ($messageFormat[2] ?? null));

//                        var_dump($i !== $stepEnd);
//                        var_dump($i !== $this->progressCounter);

                        $this->outputObject->step($this,
                                                  $i,
                                                  $progressData,
                                                  $i === $stepEnd,
                                                  $msTime,
                                                  $messages,
                                                  $i === $this->progressCounter,
                                                  $this->consoleID);

                        $this->writeDatabase($progressData,
                                             $i,
                                             implode("\n",
                                                     $messages));

                    } catch (Throwable $exception) {
                        $this->outputObject->error($exception,
                                                   $i,
                                                   $progressData);
                    }
                }
            },
                                           'array $progressData');

    }

    public function execute(): void
    {
        call_user_func_array([
                                 $this,
                                 $this->methodPrepare
                             ],
                             $this->parameter);

        ContainerFactoryDatabaseEngineSqlite::reInit();

        $this->classConstructor->addProperty('progressCounter',
                                             $this->progressCounter,
                                             $this->classConstructor::ACCESS_PROTECTED);

        $this->classConstructor->create();

        /** @var ContainerFactoryDatabaseQuery $query */
        $query = Container::get('ContainerFactoryDatabaseQuery',
                                __CLASS__ . '.insert.console.data',
                                'cache',
                                ContainerFactoryDatabaseQuery::MODE_INSERT);
        $query->setTable('console');
        $query->setInsertInto('consoleID',
                              $this->consoleID);
        $query->setInsertInto('progress',
                              1);
        $query->setInsertInto('progressData',
                              serialize([]));

        $query->construct();
        $smtp = $query->execute();
        $smtp->closeCursor();

        if ($this->getOutputMode() === self::OUTPUT_MODE_AJAX) {
            echo json_encode([
                                 'cguiMax'   => $this->progressCounter,
                                 'consoleID' => $this->consoleID
                             ]);
        }

    }

    /**
     *
     * @param string $progressFunction
     * @param array  $dataReplace
     *
     * @return int
     */
    public function addProgressFunction(closure $progressFunction, array $dataReplace = []): int
    {
        $this->progressCounter++;
        $this->progressIdentifyCounter++;

        $this->classConstructor->prepareMethod('Step' . $this->progressCounter,
                                               $dataReplace);

        $this->classConstructor->addProperty('Step' . $this->progressCounter,
                                             '[
            "counter" => "' . $this->progressCounter . '",
            "identifyCounter" => "' . $this->progressIdentifyCounter . '",
            "identify" => "' . $this->getProgressIdentify() . '",
       ]',
                                             ContainerFactoryClass::ACCESS_PROTECTED);

        $this->classConstructor->addMethod('Step' . $this->progressCounter,
                                           $progressFunction,
                                           '',
                                           'array',
                                           ContainerFactoryClass::ACCESS_PROTECTED);

        if (!isset($this->progressPartMax[$this->getProgressIdentify()])) {
            $this->progressPartMax[$this->getProgressIdentify()] = 0;
        }
        $this->progressPartMax[$this->getProgressIdentify()]++;

        $this->classConstructor->addProperty('StepMax' . $this->getProgressIdentify(),
                                             $this->progressPartMax[$this->getProgressIdentify()],
                                             ContainerFactoryClass::ACCESS_PROTECTED);


        return 1;
    }


    /**
     * @return array
     */
    public function getProgressData(): array
    {
        return $this->progressData;
    }

    /**
     * @return string
     */
    public function getOutputMode(): string
    {
        return $this->outputMode;
    }

    /**
     * @param string $outputMode
     */
    public function setOutputMode(string $outputMode): void
    {
        $this->outputMode = $outputMode;
    }

    public function getClassConstructor(): ContainerFactoryClass
    {
        return $this->classConstructor;
    }

    /**
     * @return string
     */
    public function getProgressIdentify(): string
    {
        return $this->progressIdentify;
    }

    /**
     * @param string $progressIdentify
     */
    public function setProgressIdentify(string $progressIdentify): void
    {
        $this->progressIdentifyCounter = 0;
        $this->progressIdentify        = $progressIdentify;
    }

}
