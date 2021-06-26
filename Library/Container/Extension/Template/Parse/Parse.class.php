<?php
declare(strict_types=1);

/**
 * Class ContainerExtensionTemplateParse
 * @method string get($parseClass, $parseString, $parameter, $parentTemplateObject) Gets the Name of the File
 */
class ContainerExtensionTemplateParse extends Base
{
    /**
     * Parse Function.
     *
     * @CMSprofilerSetFromScope parseClass
     * @CMSprofilerSetFromScope parseString
     *
     * @CMSprofilerOption       isFunction true
     * @CMSprofilerOption       deph 11
     *
     */
    public function _get(array &$scope, string $parseClass, string $parseString, array $parameter, ContainerExtensionTemplate $parentTemplateObject): string
    {
        $scope['parseClass']  = $parseClass;
        $scope['parseString'] = $parseString;

        /** @var ContainerExtensionTemplateParse_abstract $parse */
        $parse = Container::get($parseClass,
                                $parseString,
                                $parameter,
                                $parentTemplateObject);

        return $parse->parse();
    }

}
