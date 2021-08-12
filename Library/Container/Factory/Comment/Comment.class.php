<?php declare(strict_types=1);

/**
 * Comments
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    language_path_de_DE Kommentare
 * @modul    language_name_de_DE Kommentare
 * @modul    language_path_en_US Comment
 * @modul    language_name_en_US Comment
 * @modul hasCSS
 */

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
        $crud      = new ContainerFactoryComment_crud();
        $crudCount = $crud->count([
                                      'crudPath' => $this->path
                                  ]);

        d($crudCount);
        eol();
    }

    public function get()
    {
        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'item');

        $crud     = new ContainerFactoryComment_crud();
        $crudFind = $crud->find([
                                    'crudPath' => $this->path
                                ],
                                [
                                    'dataVariableCreated DESC'
                                ]);

        $content = '';

        /** @var ContainerFactoryComment_crud $crudFindItem */
        foreach ($crudFind as $crudFindItem) {
            $template = new ContainerExtensionTemplate();
            $template->set($templateCache->getCacheContent()['item']);

            $crudItemDate = new DateTime($crudFindItem->getDataVariableCreated());


            $template->assign('content',
                              $crudFindItem->getCrudContent());
            $template->assign('user',
                              $crudFindItem->getAdditionalQuerySelect('user_crudUsername'));
            $template->assign('userGroup',
                              $crudFindItem->getAdditionalQuerySelect('user_group_crudLanguage'));
            $template->assign('date',
                              $crudItemDate->format((string)Config::get('/environment/datetime/format')));

//            d($crudFindItem);
//            d($template);

            $template->parse();
            $content .= $template->get();
        }

//        eol();

        return $content;
    }

}
