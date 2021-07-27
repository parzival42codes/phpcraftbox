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

                    $crudName = ucfirst($data['crud']);
                    /** @var Base_abstract_crud $crud */
                    $crud = new $crudName();

                    if ($data['ident'] !== null) {

                        $rp = new ReflectionProperty($crud,
                                                     $data['column']);
                        settype($data['ident'],
                                $rp->getType()
                                   ->getName());

                        call_user_func([
                                           $crud,
                                           'set' . ucfirst($data['column'])
                                       ],
                                       $data['ident']);

                        $crud->findByColumn($data['column'],
                                            true);

                    }

                    foreach ($data['values'] as $key => $value) {

                        if (
                            strpos($value,
                                   '??File:') === 0
                        ) {
                            $templateFile = substr($value,
                                                   7);

                            $templateCache = new ContainerExtensionTemplateLoad_cache_template('Db',
                                                                                               $templateFile);

                            $template = new ContainerExtensionTemplate();
                            $template->set($templateCache->getCacheContent()[$templateFile]);
                            $value = $template->get();
                        }

                        $rp = new ReflectionProperty($crud,
                                                     $key);
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
