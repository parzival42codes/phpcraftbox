<?php

class ContainerFactoryRouter_cache_route extends ContainerExtensionCache_abstract
{
    protected string $ident          = '';

    public function prepare(): void
    {
        $this->ident = __CLASS__;
        $this->setPersistent(true);
    }

    public function create(): void
    {
        $this->cacheContent = [];
        $query              = Container::get('ContainerFactoryDatabaseQuery',
                                             __METHOD__ . '#select',
                                             true,
                                             ContainerFactoryDatabaseQuery::MODE_SELECT);
        $query->setTable('index_router');
        $query->select('crudPath');
        $query->select('crudType');
        $query->select('crudClass');
        $query->select('crudRoute');
//        $query->setParameterWhere('crudClass',
//                                  $this->getApplication());
//        $query->setParameterWhere('crudRoute',
//                                  $this->getRoute());


        $query->construct();
        $smtp = $query->execute();
        foreach ($smtp->fetchAll() as $route) {
            $this->cacheContent[$route['crudClass']][$route['crudType']][$route['crudRoute']] = $route['crudPath'];
        }
    }


}
