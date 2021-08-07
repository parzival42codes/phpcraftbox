<?php declare(strict_types=1);

class ContainerFactoryComment extends Base
{

    protected string             $path = '';
    protected Base_abstract_crud $crud;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function count()
    {
        $crud     = new ContainerFactoryComment_crud();
        $crudCount = $crud->count([
                                    'crudPath' => $this->path
                                ]);

        d($crudCount);
        eol();
    }

    public function get()
    {
        $crud     = new ContainerFactoryComment_crud();
        $crudFind = $crud->find([
                                    'crudPath' => $this->path
                                ],
                                [
                                    'dataVariableCreated DESC'
                                ]);

        d($crudFind);
        eol();
    }

}
