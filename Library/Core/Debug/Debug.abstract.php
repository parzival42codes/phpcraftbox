<?php

abstract class CoreDebug_abstract extends Base
{

    protected string $title = '';
    protected string $html  = '';
    protected array  $data  = [];

    abstract public function getTitle(): string;

    abstract public function getHtml(): string;

    protected function getData(): array
    {
        return $this->data;
    }

    protected function prepare(): void
    {

    }

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->prepare();
    }


    protected function getStandard(string $class, array $data, string $templates = ''): void
    {
//        $this->data = $data;
//        $className  = Core::getRootClass($class);
//        if ($templates === '') {
//            $this->template = null;
//        }
//        else {
//            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
//            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
//                                             $className,
//                                             [
//                                                 $templates
//                                             ]);
//            $this->template     = $templateCache->get();
//
//        }
//
//        $this->data = self::getDeltaFromData($this->data);
    }
//
//    protected static function getDeltaFromData($data)
//    {
//        foreach ($data as $key => $elem) {
//            if (isset($data[$key]['_'])) {
//                $data[$key]['_']['memoryDeltaRaw'] = ($data[$key]['_']['memoryEnd'] - $data[$key]['_']['memoryStart']);
//
//                $data[$key]['_']['microtimeDelta'] = Container::callStatic('ContainerHelperCalculate',
//                                                                           'calculateMicroTime',
//                                                                           ($data[$key]['_']['microtimeEnd']) - ($data[$key]['_']['microtimeStart']));
//                $data[$key]['_']['memoryDelta']    = Container::callStatic('ContainerHelperCalculate',
//                                                                           'calculateMemoryBytes',
//                    ($data[$key]['_']['memoryDeltaRaw']));
//            }
//        }
//        return $data;
//    }

    public function getTime(): string
    {

        $firstData = reset($this->data);
        if (isset($firstData['microtimeDiff'])) {
            $timeCollect = 0;
            foreach ($this->data as $elem) {
                $timeCollect += $elem['microtimeDiff'];
            }

            return ContainerHelperCalculate::
            calculateMicroTimeDisplay($timeCollect);
        }

        return '';

    }

}
