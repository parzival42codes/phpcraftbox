<?php

class ContainerFactoryModul extends Base
{
    protected static array $classMeta = [];
    protected string       $class     ='';

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function getClass():string
    {
        return $this->class;
    }

    public static function getModulMenuLanguage(string $modulName, string $stdPath): string
    {

        /** @var ContainerFactoryModul_crud $modulCrud */
        $modulCrud = Container::get('ContainerFactoryModul_crud');
        $modulCrud->setCrudModul($modulName);
        $modulCrud->findById();

        if (!empty($modulCrud->getCrudMeta())) {
            $modulCrudMeta = json_decode($modulCrud->getCrudMeta(),
                                         true);

            if (isset($modulCrudMeta['language']['path'])) {
                return ContainerFactoryLanguage::getLanguageText(Config::get('/environment/language'),
                                                                       $modulCrudMeta['language']['path']) . '/' . ContainerFactoryLanguage::getLanguageText(Config::get('/environment/language'),
                                                                                                                                                             $modulCrudMeta['language']['name']);
            }
            else {
                return '/' . $stdPath;
            }

        }
        else {
            return '/' . $stdPath;
        }

    }

}
