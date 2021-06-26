<?php declare(strict_types=1);

class ApplicationAdministrationContentView_content extends Base
{
    public function get(string $path): ?object
    {
        /** @var ApplicationAdministrationContent_crud_index $crud */
        $crud       = Container::get('ApplicationAdministrationContent_crud_index');
        $crudResult = $crud->find([
                                      'crudPath'     => $path,
                                      'crudLanguage' => \Config::get('/environment/config/iso_language_code'),
                                  ]);

        $crudResultFirst = reset($crudResult);

        if (!empty($crudResultFirst)) {
            $domains = explode(';',
                               $crudResultFirst->getCrudDomain());

            if (
                empty($crudResultFirst->getCrudDomain()) || in_array($crudResultFirst->getCrudDomain(),
                                                                     $domains)
            ) {
                Container::set('ApplicationAdministrationContentView/ident',
                               $crudResultFirst->getCrudIdent());

                return  Container::get('Application',
                                              'ApplicationAdministrationContentView');

            }
        }

        return null;
    }

}
