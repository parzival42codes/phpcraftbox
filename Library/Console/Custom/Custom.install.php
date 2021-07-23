<?php

class ConsoleCustom_install extends ContainerFactoryModulInstall_abstract
{

    public function install(): void
    {
        $jsonDbCustomFile = CMS_ROOT . 'Custom/Custom.db.json';
        if (is_file($jsonDbCustomFile)) {
            d(json_decode(file_get_contents($jsonDbCustomFile)),
              true);
        }
        eol();

//        $this->installFunction(function () {
//            /** @var array $data */ /*$before*/
//
//            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
//            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
//                                            'PluginMatomo',
//                                            'install.privacy');
//
//            $crud = new ContainerExtensionTemplateParseInsertPositions_crud();
//            $crud->setCrudPosition('/Content/Privacy/Additional');
//            $crud->setCrudClass('PluginMatomo');
//            $crud->setCrudContent($templateCache->getCacheContent()['install.privacy']);
//
//            $progressData['message'] = $crud->insertUpdate();
//
//            /*$after*/
//        });

    }


}
