<?php
//
//final class CoreDebugProfiler
//{
//
//    const PROFILER_PATH_START = 'PROFILER_PATH_START';
//    const PROFILER_PATH_STAGE = 'PROFILER_PATH_STAGE';
//    const PROFILER_PATH_END   = 'PROFILER_PATH_END';
//    protected static $profilerStackCheck             = [];
//    protected static $profilerLevelCounter           = 0;
//    protected static $profilerPathCounter            = 0;
//    protected static $profilerMicrotimeHelper        = [];
//    protected static $profilerMeHelper               = [];
//    protected static $profilerCounterMain;
//    protected static $profilerCounterMainLast        = 2;
//    protected static $profilerCounterMainCounter     = 0;
//    protected static $profilerCounterMainLevel       = 0;
//    protected static $profilerCounterMainId          = '';
//    protected static $profilerCounterFunction;
//    protected static $profilerCounterFunctionCounter = 0;
//    protected static $profilerCounterFunctionLevel   = 0;
//    protected static $lastProfiler                   = [];
//    protected static $lastProfilerLimit              = 50;
//    protected static $reflectionData                 = [];
//    protected static $profilerCallCounter            = 0;
//    protected        $profilerClass                  = [];
//    protected        $profilerData                   = [];
//    protected        $profilerIDCounter              = 0;
//    //--------------------
//    protected $profilerID                 = '';
//    protected $profilerStack              = [];
//    protected $profilerLevel              = 0;
//    protected $profilerIsFunction         = false;
//    protected $profilerMicrotime          = 0;
//    protected $profilerMicrotimeAll       = 0;
//    protected $profilerMemory             = 0;
//    protected $profilerBacktraceFile      = '';
//    protected $profilerBacktraceLine      = '';
//    protected $profilerStackLastMicrotime = '';
//    //--------------------
//    protected $profilerStackStartMicrotime = '';
//    protected $dontTrack                   = false;
//    //--------------------
//    protected $profilerCounterMainLastThis   = 0;
//    protected $profilerCounterFunctionMainId = '';
//
//    /**
//     * Create a profiling
//     *
//     * Creates from the Infomation from the Comment of a function an the Parameter a profiling
//     *
//     * @example CMSprofilerCatchParameter <paramNr> <name> // Catch a Value from a Method Parameter
//     * @example CMSprofilerSet <key> <value> // Set a value
//     * @example CMSprofilerOption <key> <value> // set an Option
//     * @example CMSprofilerTest <settetValue> <compare> <value> <type:successful|info|warning|error> <message>*
//     *
//     * @param string $class
//     * @param string $id
//     * @param array  $data
//     * @param array  $options
//     */
//    public function __construct(string $class, string $id, array $data = [], array $options = [])
//    {
//        if (($id === 'Container::get' && ($data['index'] === __CLASS__ || $data['index'] === 'DebugProfiler')) || ($id === 'Container::callStatic' && $data['methodName'] === 'setRawDebugData')) {
//            $this->dontTrack = true;
//            return false;
//        }
//
//        $this->profilerIDCounter = ++self::$profilerPathCounter;
//
//        $this->profilerClass = $class;
//        $this->profilerID    = $id;
//        $this->profilerData  = $data;
//
//        $microtime     = microtime(true);
//        $Backtrace     = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
//        $BacktraceDeph = (($options['deph']) ?? 0);
//
//        $this->profilerMicrotime           = microtime(true);
//        $this->profilerMicrotimeAll        = ($this->profilerMicrotime - CMS_SYSTEM_START_TIME);
//        $this->profilerMemory              = memory_get_usage();
//        $this->profilerBacktraceFile       = \Core::getReducedFilename((isset($Backtrace[$BacktraceDeph]['file']) === true) ? $Backtrace[$BacktraceDeph]['file'] : '?');
//        $this->profilerBacktraceLine       = ((isset($Backtrace[$BacktraceDeph]['line']) === true) ? $Backtrace[$BacktraceDeph]['line'] : '?');
//        $this->profilerStackLastMicrotime  = $microtime;
//        $this->profilerStackStartMicrotime = $microtime;
//
//        $this->profilerIsFunction = ((isset($options['isFunction']) === true && $options['isFunction'] === true) ? true : false);
//
//        if ($this->profilerIsFunction === true) {
//            $this->profilerCounterLast = $this->profilerCounterFunctionMainId = self::$profilerCounterMainId;
//            $this->profilerLevel       = ++self::$profilerCounterFunctionLevel;
//        }
//        else {
//            $this->profilerCounterLast         = $this->profilerCounterFunctionMainId = self::$profilerCounterMainId;
//            $this->profilerCounterMainLastThis = self::$profilerCounterMainLast;
//            self::$profilerCounterMainLast     = self::$profilerPathCounter;
//            self::$profilerCounterMainId       = $id;
//            $this->profilerLevel               = ++self::$profilerCounterMainLevel;
//        }
//
//        unset($Backtrace);
//    }
//
//    public static function doProfilingOpen($triggering, $object, &$scope)
//    {
//
////        if ($triggering[0] === 'CoreDebugProfiler') {
////            return;
////        }
//
//        if (
//        class_exists($triggering[0],
//                     false)
//        ) {
//            if (
//            in_array($triggering[1],
//                     get_class_methods($triggering[0]))
//            ) {
//
//                $scope['_profiler'] = Container::get('CoreDebugProfilerScope',
//                                                     $triggering,
//                                                     $object,
//                                                     $scope);
//
//                $scope = array_merge($scope,
//                                     $scope['_profiler']->getScope());
//
//            }
//
//        }
//
//    }
//
//    public static function doProfilingClose($triggering, $object, &$scope)
//    {
//        if (isset($scope['_profiler'])) {
//            $scope['_profiler']->get($scope);
//        }
//
//    }
//
//    static public function getLastClass()
//    {
//        return self::$lastProfiler;
//    }
//
//    public function close(array $data = [])
//    {
//
//        if ($this->dontTrack === true) {
//            return false;
//        }#
//
//        //        $profilerCounterPart = self::$profilerCounterPart[self::$profilerCounterMainLevel];
//        $profilerCounterPart = self::$profilerCounterMainLevel;
//
//        if ($this->profilerIsFunction === true) {
//            --self::$profilerCounterFunctionLevel;
//        }
//        else {
//            --self::$profilerCounterMainLevel;
//        }
//
//        $microtime = microtime(true);
//        $memUsage  = memory_get_usage();
//
//        $this->profilerData = array_merge($this->profilerData,
//                                          $data);
//
//        if ($this->profilerIsFunction === false || ($this->profilerIsFunction === true && \Config::get('/debug/profiler/functions/list') === '1')) {
//
//            $profilerData = $this->profilerData;
//
//            unset($profilerData['backtrace']);
//            CoreDebug::setRawDebugData(__CLASS__,
//                                       [
//                                           'iDCounter'    => $this->profilerIDCounter,
//                                           'id'           => $this->profilerID,
//                                           'part'         => $this->profilerCounterLast ?? '',
//                                           'isFunction'   => $this->profilerIsFunction,
//                                           'level'        => $this->profilerLevel,
//                                           'mainLast'     => $this->profilerCounterMainLastThis,
//                                           'memoryEnd'    => $memUsage,
//                                           'memoryPeak'   => memory_get_peak_usage(),
//                                           'microtimeAll' => $this->profilerMicrotimeAll,
//                                           'microtime'    => $this->profilerMicrotime,
//                                           'stagesCount'  => count($this->profilerStack),
//                                           'stages'       => $this->profilerStack,
//                                           'data'         => $profilerData,
//                                           '_'            => [
//                                               'backtraceFile'  => $this->profilerBacktraceFile,
//                                               'backtraceLine'  => $this->profilerBacktraceLine,
//                                               'microtimeStart' => $this->profilerMicrotime,
//                                               'memoryStart'    => $this->profilerMemory,
//                                               'microtimeEnd'   => $microtime,
//                                               'memoryEnd'      => $memUsage,
//                                           ],
//                                       ],
//                                       $this->profilerIDCounter);
//        }
//
//        if ($this->profilerIsFunction === true) {
//
//            \CoreDebug::setRawDebugData($this->profilerClass,
//                                         array_merge($this->profilerData,
//                                                     [
//                                                         '_' => [
//                                                             'id'             => $this->profilerID,
//                                                             'backtraceFile'  => $this->profilerBacktraceFile,
//                                                             'backtraceLine'  => $this->profilerBacktraceLine,
//                                                             'microtimeStart' => $this->profilerMicrotime,
//                                                             'memoryStart'    => $this->profilerMemory,
//                                                             'microtimeEnd'   => $microtime,
//                                                             'memoryEnd'      => $memUsage,
//                                                         ],
//                                                     ]));
//        }
//    }
//
//}
////
////\Event::attachSystem(\Event::TRIGGER_OPEN,
////                     'CoreDebugProfiler',
////                     'doProfilingOpen',
////    function ($data, $object, &$scope) {
////        return \CoreDebugProfiler::doProfilingOpen(get_class($object),
////                                                    $data['systemMethodName'],
////                                                    $object,
////                                                    $scope);
////    });
////
////\Event::attachSystem(\Event::TRIGGER_CLOSE,
////                     'CoreDebugProfiler',
////                     'doProfilingClose',
////    function ($data, $object, $scope) {
////        return \CoreDebugProfiler::doProfilingClose(get_class($object),
////                                                     $data['systemMethodName'],
////                                                     $object,
////                                                     $scope);
////    });
