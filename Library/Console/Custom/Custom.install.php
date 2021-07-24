<?php

class ConsoleCustom_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $jsonDbCustomFile = CMS_ROOT . 'Custom/Custom.db.json';
        if (is_file($jsonDbCustomFile)) {
            $jsonContent = json_decode(file_get_contents($jsonDbCustomFile),
                                       true);

            foreach ($jsonContent['content'] as $jsonContentCrud) {
                $this->installFunction(function () {
                    /** @var array $data */ /*$before*/

                    simpleDebugLog($data);

                    $crudName = ucfirst($data['crud']);
                    /** @var Base_abstract_crud $crud */
                    $crud = new $crudName();

                    $rp = new ReflectionProperty($crud,
                                                 $crud::getTableId());
                    settype($data['ident'],
                            $rp->getType()
                               ->getName());

                    call_user_func([
                                       $crud,
                                       'set' . ucfirst($crud::getTableId())
                                   ],
                                   $data['ident']);

                    $crud->findById();

                    foreach ($data['values'] as $key => $value) {
                        $rp = new ReflectionProperty($crud,
                                                     $value);
                        settype($value,
                                $rp->getType()
                                   ->getName());

                        call_user_func([
                                           $crud,
                                           'set' . ucfirst($key)
                                       ],
                                       $value);
                    }

                    $progressData['message'] = $crud->insertUpdate();

                    /*$after*/
                },
                    $jsonContentCrud);
            }


        }

    }


}
