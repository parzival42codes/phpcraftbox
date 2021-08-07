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

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['default']);

        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     $this->___getRootClass(),
                                     'register');

        $formHelper->addFormElement('search',
                                    'text');
        $template->assign('search',
                          $formHelper->getElements());

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

        $formContent = '';
        /** @var ContainerFactoryModul_crud $crudSearchItem */
        foreach ($crudSearch as $crudSearchItem) {
            $searchName = $crudSearchItem->getCrudModul() . '_search';
            /** @var ApplicationSearch_abstract $searchModul */
            $searchModul = new $searchName();
            $formContent .= $searchModul->getForm($formHelper);
        }

        $template->assign('registerHeader',
                          $formHelper->getHeader());

        $template->assign('content',
                          $formContent);

        $template->assign('registerFooter',
                          $formHelper->getFooter(),
                          true);

//        d($formHelper);
//        d($crudSearch);
//        d($template);
//        eol();

        $template->parse();
        return $template->get();
    }
}
