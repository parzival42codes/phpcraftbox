<?php

abstract class ContainerExtensionApiDebug_abstract extends Base
{

    protected string $title = '';
    protected string $html  = '';
    protected array  $data  = [];

    //public function getTitle();
    //public function getContent();

    public function __construct(array $data)
    {
        $this->getStandard(__CLASS__,
                           $data);
    }

    abstract public function getData(): array;

    abstract public function getTitle(): string;

    abstract public function getHtml(): string;

    protected function getStandard(string $class, array $data): void
    {
        $this->data = $data;
        $this->data = self::getDeltaFromData($this->data);
    }

    protected static function getDeltaFromData(array $data): array
    {
        foreach ($data as $key => $elem) {
            if (isset($data[$key]['_'])) {
                $data[$key]['_']['memoryDeltaRaw'] = ($data[$key]['_']['memoryEnd'] - $data[$key]['_']['memoryStart']);

                $data[$key]['_']['microtimeDelta'] = ContainerHelperCalculate::calculateMicroTime(($data[$key]['_']['microtimeEnd']) - ($data[$key]['_']['microtimeStart']));
                $data[$key]['_']['memoryDelta']    = ContainerHelperCalculate::calculateMemoryBytes(($data[$key]['_']['memoryDeltaRaw']));
            }
        }
        return $data;
    }

    public function getTime(): string
    {
        $timeCollect = 0;
        foreach ($this->data as $elem) {
            if (isset($elem['_'])) {
                $timeCollect += $elem['_']['microtimeDelta'];
            }
        }

        return ContainerHelperCalculate::calculateMicroTimeDisplay($timeCollect);
        //        return number_format(round($timeCollect, 2), 2, ',', '.') . ' ' . $this->language['/template/debug/milliseconds'];
    }

}
