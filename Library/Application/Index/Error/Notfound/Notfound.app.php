<?php

class ApplicationIndexErrorNotfound_app extends Application_abstract
{

    const DIFF_LEVENSHTEIN = 10;

    public function setContent(): string
    {

//        $urlRaw = Container::getInstance('ContainerFactoryRouter')
//                           ->getUrlReadable(true);
//
//        $urlSearch = substr($urlRaw,
//                            1);
//        d($urlRaw);
//        d($urlSearch);
//
//        /** @var ContainerFactoryDatabaseQuery $query */
//        $query = Container::get('ContainerFactoryDatabaseQuery',
//                                __METHOD__ . '#select',
//                                true,
//                                ContainerFactoryDatabaseQuery::MODE_SELECT);
//        $query->setTable('content_index');
//        $query->select('crudPath');
//        $query->select('crudTitle');
//        $query->select('crudDescription');
//        $query->selectRaw('(
//0.5 * (MATCH (crudPath) AGAINST ("+' . $urlSearch . '" IN BOOLEAN MODE))
// AS relevance');
////        $query->setParameterWhereLike('crudPath',
//                                      '%' . $urlSearch . '%');
//        $query->setParameterWhereLike('crudTitle',
//                                      '%' . $urlSearch . '%');
//        $query->setParameterWhereLike('crudDescription',
//                                      '%' . $urlSearch . '%');

//SELECT
//    `content_index`.crudPath,
//    `content_index`.crudTitle,
//    `content_index`.crudDescription,
//    MATCH(crudPath) AGAINST('imp' IN BOOLEAN MODE) AS relevancePath,
//    MATCH(crudTitle) AGAINST('imp' IN BOOLEAN MODE) AS relevanceTitle,
//    MATCH(crudDescription) AGAINST('imp' IN BOOLEAN MODE) AS relevanceDescription
//FROM
//    `content_index` AS `content_index`


//
//        $query->construct();
//        $smtp = $query->execute();
//
//        while ($smtpData = $smtp->fetch()) {
//            d($smtpData);
//        }
//
//        eol();
//        SELECT
//    crudIdent,
//    crudDescription,
//        0.5 *(
//        MATCH(crudDescription) AGAINST('+Milch' IN BOOLEAN MODE)
//        ) AS relevance
//    FROM
//        content
//    ORDER BY
//        relevance
//    DESC
//LIMIT 100

        $this->setHeader(404);

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache        = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                               'ApplicationIndexErrorNotfound',
                                               'default');
        $templateCacheContent = $templateCache->get();

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCacheContent['default']);

        /** @var ContainerFactoryUser $user */
        $user = Container::getInstance('ContainerFactoryUser');

        /** @var ContainerFactoryRouter $router */
        $router = Container::getInstance('ContainerFactoryRouter');

        /** @var ContainerFactoryLogPage_crud $crud */
        $crud = Container::get('ContainerFactoryLogPage_crud');
        $crud->setCrudType('pageNotFound');
        $crud->setCrudUrlPure($router->getUrlPure(true));
        $crud->setCrudUrlReadable($router->getUrlReadable(true));
        $crud->setCrudMessage('');
        $crud->setCrudData('');
        $crud->setCrudUserId($user->getUserId());
        $crud->insert();

        return $template->get();
    }

    public function setTitle(): string
    {
        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set(ContainerFactoryLanguage::get('/header/title'));
            return $template->get();
    }

}
