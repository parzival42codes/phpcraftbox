<?php declare(strict_types=1);

/**
 * Report
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    groupAccess 1,2,3,4
 * @modul    language_path_de_DE /Meldung
 * @modul    language_name_de_DE Senden
 * @modul    language_path_en_US /Report
 * @modul    language_name_en_US Send
 */
class ApplicationAdministrationReportSend_app extends ApplicationAdministration_abstract
{

    public function setContent(): string
    {
        /** @var ContainerExtensionTemplateParseCreateForm_helper $formHelper */
        $formHelper = Container::get('ContainerExtensionTemplateParseCreateForm_helper',
                                     $this->___getRootClass(),
                                     'report');

        $templateCache = new ContainerExtensionTemplateLoad_cache_template(Core::getRootClass(__CLASS__),
                                                                           'default,form,exists');

        $content = '';

        $container = Container::DIC();
        /** @var ContainerFactoryRouter $router */
        $router = $container->getDIC('/Router');


        $crudModul = new ContainerFactoryModul_crud();
        $crudModul->setCrudHash($router->getParameter('hash'));
        $crudModul->findByColumn('crudHash',
                                 true);

        $crudReport = new ApplicationAdministrationReport_crud();
        $crudReport->setCrudModul($crudModul->getCrudModul());
        $crudReport->setCrudModulId($router->getParameter('id'));
        $crudReport->findByColumn([
                                      'crudModul',
                                      'crudModulId',
                                  ]);

        if ($crudReport->getCrudId() === null) {

            /** @var ApplicationAdministrationReport_abstract $report */
            $reportName = $crudModul->getCrudModul() . '_report';

            $report   = new $reportName();
            $crudName = $report->getCrud();

            /** @var Base_abstract_crud $crud */
            $crud = new $crudName();

            $crudIdName      = $crud::getTableId();
            $crudContentName = $report->getContent();

            $crudSetName = 'set' . ucfirst($crudIdName);
            $crud->$crudSetName($router->getParameter('id'));
            $crud->findByColumn($crudIdName,
                                true);

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templateCache->getCacheContent()['default']);
            $template->assign('content',
                              $crud->$crudContentName());
            $template->parse();
            $content .= $template->get();

            $templateForm = new ContainerExtensionTemplate();
            $templateForm->set($templateCache->getCacheContent()['form']);

            $formHelperResponse = $formHelper->getResponse();
            if (
                $formHelperResponse->isHasResponse()
            ) {
                $this->formResponse($formHelper,
                                    $crud,
                                    $crudReport);
            }


            $formHelper->addFormElement('report',
                                        'select',
                                        [
                                            [
                                                ApplicationAdministrationReport_crud::STATUS_COPYRIGHT_PROTECTION_ACT => ContainerFactoryLanguage::get('/ApplicationAdministrationReportSend/form/report/option/cpa'),
                                            ],
                                        ],
                                        [
                                            'ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                        ]);

            $elementObj = $formHelper->getElement('report');
            $elementObj->setFlex(1);

            $formHelper->addFormElement('content',
                                        'textarea');

            $elementObj = $formHelper->getElement('content');
            $elementObj->setFlex(2);

            $templateForm->assign('form',
                                  $formHelper->getElements(true));

            $templateForm->assign('formHeader',
                                  $formHelper->getHeader());

            $templateForm->assign('formFooter',
                                  $formHelper->getFooter());

            $templateForm->parse();

            $content .= $templateForm->get();

        }
        else {
            $templateExists = new ContainerExtensionTemplate();
            $templateExists->set($templateCache->getCacheContent()['exists']);
            $templateExists->assign('report',
                                    ContainerFactoryLanguage::get('/ApplicationAdministrationReportSend/form/report/option/' . $crudReport->getCrudReport()));
            $templateExists->assign('content',
                                    $crudReport->getCrudContent());
            $templateExists->parse();
            $content .= $templateExists->get();
        }

        $this->createPageData();
//        $this->pageData();

        return $content;
    }

    private function pageData(): void
    {
        $className = Core::getRootClass(__CLASS__);

        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');

        $page->setPageTitle(ContainerFactoryLanguage::get('/' . $className . '/meta/title',
                                                          ''));
        $page->setPageDescription(ContainerFactoryLanguage::get('/' . $className . '/meta/description',
                                                                ''));
        $breadcrumb = $page->getBreadcrumb();

        $container = Container::DIC();
        /** @var ContainerFactoryRouter $router */
        $router = $container->getDIC('/Router');

        $breadcrumb->addBreadcrumbItem(ContainerFactoryLanguage::get('/' . $className . '/breadcrumb'),
                                       $router->getUrlReadable());

        $menu = $this->getMenu();
        $menu->setMenuClassMain($this->___getRootClass());

    }

    public function formResponse(ContainerExtensionTemplateParseCreateForm_helper $formHelper, Base_abstract_crud $crud, ApplicationAdministrationReport_crud $crudReport)
    {
        $response = $formHelper->getResponse();
        if (!$response->hasError()) {
            $crudReport->setCrudContent($response->get('content'));
            $crudReport->setCrudReport($response->get('report'));
            $crudReport->insertUpdate();
        }

    }

}
