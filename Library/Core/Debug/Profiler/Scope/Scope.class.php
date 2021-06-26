<?php

final class CoreDebugProfilerScope
{
    protected $microtime     = 0;
    protected int       $memory        = 0;
    protected     $docDataMethod = [];
    protected array     $data          = [];
    protected array     $option        = [];
    protected array     $scope         = [];

    protected static int $counter = 0;

    public function __construct(array $triggering, object $object, array &$scope)
    {

        $this->scope     = $scope;
        $this->memory    = memory_get_usage();
        $this->microtime = microtime(true);

        $docData = Container::get('ContainerFactoryReflection',
                                  $scope['_meta']['class']);

        $this->docDataMethod = ($docData->getMethods()[$scope['_meta']['method']] ?? '???');

        $this->scope['_profiler']['option']     = [];
        $this->scope['_profiler']['data']       = [];
        $this->scope['_profiler']['file']       = ($scope['_meta']['backtrace'][0]['file'] ?? '??');
        $this->scope['_profiler']['line']       = ($scope['_meta']['backtrace'][0]['line'] ?? '??');
        $this->scope['_profiler']['backtrace']  = ($scope['_meta']['backtrace'] ?? []);
        $this->scope['_profiler']['counter']    = ++self::$counter;
        $this->scope['_profiler']['microtime']  = microtime(true);
        $this->scope['_profiler']['memory']     = memory_get_usage();
        $this->scope['_profiler']['memoryPeak'] = memory_get_peak_usage();

        if (isset($this->docDataMethod['paramData']['@CMSprofilerOption'])) {
//            foreach ($this->docDataMethod['paramData']['@CMSprofilerOption'] as $optionItem) {
//                $optionItemSata = explode(' ',
//                                          trim($optionItem),
//                                          2);
//                $valueData      = ($optionItemSata[1] ?? null);
//                if ($valueData === 'true') {
//                    $valueData = true;
//                }
//                elseif ($valueData === 'false') {
//                    $valueData = false;
//                }
//
//
//                $this->scope['_profiler']['option'][$optionItemSata[0]] = $valueData;
//            }

            $this->scope['_profiler']['option'] = $this->docDataMethod['paramData']['@CMSprofilerOption'];

            array_walk($this->scope['_profiler']['option'],
                function (&$item) {
                    if ($item === 'true') {
                        $item = true;
                    }
                    elseif ($item === 'false') {
                        $item = false;
                    }
                });
        }

        if (isset($this->docDataMethod['paramData']['@CMSprofilerSet'])) {
//
//            d($this->docDataMethod['paramData']['@CMSprofilerSet']);
//
//            foreach ($this->docDataMethod['paramData']['@CMSprofilerSet'] as $valueSet) {
//                $valueSetData = explode(' ',
//                                        trim($valueSet),
//                                        2);
//                $valueData    = ($valueSetData[1] ?? null);
//
//                d($valueSet);
//                d($valueSetData[0]);
//                d($valueData);
//
//                $this->scope['_profiler']['data'][$valueSetData[0]] = $valueData;
//            }
            $this->scope['_profiler']['data'] = $this->docDataMethod['paramData']['@CMSprofilerSet'];

            array_walk($this->scope['_profiler']['data'],
                function (&$item) {
                    if ($item === 'true') {
                        $item = true;
                    }
                    elseif ($item === 'false') {
                        $item = false;
                    }
                });

        }

        if (isset($this->docDataMethod['paramData']['@CMSprofilerCatchParameter'])) {

        }
    }

    public function get(array &$scope): array
    {
        if (isset($this->docDataMethod['paramData']['@CMSprofilerSetFromScope'])) {
            foreach ($this->docDataMethod['paramData']['@CMSprofilerSetFromScope'] as $valueKey => $valueSet) {
                $scope['_profiler']['data'][$valueKey] = $scope[$valueKey];
            }
        }

        $scope['_profiler']['microtimeEnd']  = microtime(true);
        $scope['_profiler']['memoryEnd']     = memory_get_usage();
        $scope['_profiler']['memoryPeakEnd'] = memory_get_peak_usage();

        $scope['_profiler']['microtimeDiff']  = $scope['_profiler']['microtimeEnd'] - $scope['_profiler']['microtime'];
        $scope['_profiler']['memoryDiff']     = $scope['_profiler']['memoryEnd'] - $scope['_profiler']['memory'];
        $scope['_profiler']['memoryPeakDiff'] = $scope['_profiler']['memoryPeakEnd'] - $scope['_profiler']['memoryPeak'];

        return $scope;

    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getScope(): array
    {
        return $this->scope;
    }

    /**
     * @param array $scope
     */
    public function setScope(array $scope): void
    {
        $this->scope = $scope;
    }


}
