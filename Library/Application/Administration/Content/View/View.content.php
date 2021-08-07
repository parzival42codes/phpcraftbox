<?php declare(strict_types=1);

class ApplicationAdministrationContentView_content extends Base
{
    public function get(string $path): ?object
    {
        $crud = new ApplicationAdministrationContent_crud();
        $crud->setCrudIdent($path);
        $crudResult = $crud->findById(true);

        Container::set('ApplicationAdministrationContentView/ident',
                       $crud->getCrudIdent());

        return new Application('ApplicationAdministrationContentView');
    }

}
