<?php

/**
 * Suche
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 1,2,3,4
 * @modul    language_path_de_DE Suche
 * @modul    language_name_de_DE Suche
 * @modul    language_path_en_US Suche
 * @modul    language_name_en_US Suche
 */
class ApplicationSearch_app extends Application_abstract
{

    public function setContent(array ...$parameter): string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        $this->___getRootClass(),
                                        'default');

        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     $this->___getRootClass(),
                                     'register');

        /** @var ContainerExtensionTemplateParseCreateFormResponse $formHelperResponse */
        $formHelperResponse = $formHelper->getResponse();
        if (
            $formHelperResponse->isHasResponse()
        ) {

        }

        $crud       = new ContainerFactoryModul_crud();
        $crudSearch = $crud->find([
                                      'crudHasSearch' => 1
                                  ]);

        /** @var ContainerFactoryModul_crud $crudSearchItem */
        foreach ($crudSearch as $crudSearchItem) {
            $searchName = $crudSearchItem->getCrudModul() . '_search';
            /** @var ApplicationSearch_abstract $searchModul */
            $searchModul = new $searchName();
            $searchModul->getForm($formHelper);
        }

        d($crudSearch);
        eol();
    }
}
