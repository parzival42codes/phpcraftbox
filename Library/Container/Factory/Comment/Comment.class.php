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
 * @modul    hasCSS
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
                                                                           'default,item,item.send,item.report');

        $crud     = new ContainerFactoryComment_crud();
        $crudFind = $crud->find([
                                    'crudPath' => $this->path
                                ],
                                [
                                    'dataVariableCreated DESC'
                                ]);

        $content = '';

        $modul = new ContainerFactoryModul_crud();
        $modul->setCrudModul(Core::getRootClass(__CLASS__));
        $modul->findByColumn('crudModul',
                             true);

//        d($crudFind);
//        d($modul);
//        eol();

        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');

        $userReportView = $user->checkUserAccess('ContainerFactoryComment/permitted');

        /** @var ContainerFactoryComment_crud $crudFindItem */
        foreach ($crudFind as $crudFindItem) {

            if (!$crudFindItem->getAdditionalQuerySelect('report_type_crudContent')) {
                $template = new ContainerExtensionTemplate();
                $template->set($templateCache->getCacheContent()['item']);
            }
            else {

                if ($userReportView === true) {
                    $template = new ContainerExtensionTemplate();
                    $template->set($templateCache->getCacheContent()['item.send']);
                }
                else {
                    $template = new ContainerExtensionTemplate();
                    $template->set($templateCache->getCacheContent()['item.report']);
                }

                $template->assign('typeText',
                                  ContainerFactoryLanguage::getLanguageText(json_decode($crudFindItem->getAdditionalQuerySelect('report_type_crudContent'),
                                                                                        true)));
            }


            $crudItemDate = new DateTime($crudFindItem->getDataVariableCreated());

            $template->assign('hash',
                              $modul->getCrudHash());
            $template->assign('id',
                              $crudFindItem->getCrudId());
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

//            d($modul);
//            d($template->get());
//            eol();

            $content .= $template->get();
        }

//        eol();

        $template = new ContainerExtensionTemplate();
        $template->set($templateCache->getCacheContent()['default']);

        $template->assign('content',
                          $content);

        $template->parse();
        return $template->get();
    }

}
