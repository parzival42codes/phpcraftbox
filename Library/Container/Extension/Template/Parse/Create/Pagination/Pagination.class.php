<?php declare(strict_types=1);

class ContainerExtensionTemplateParseCreatePagination extends ContainerExtensionTemplateParseCreate_abstract
{

    function parse(): string
    {
        $parameter = $this->getParameter();

        /** @var ContainerExtensionTemplateParseCreatePaginationHelper $pagination */
        $pagination = ContainerExtensionTemplateParseCreatePaginationHelper::getPaginationContainer($parameter['ident']);
        if (empty($pagination)) {
            \CoreErrorhandler::trigger(__METHOD__,
                                       'paginationNotFound',
                                       [
                                           $parameter['ident']
                                       ]);
            return '';
        }
        return $pagination->getTemplate();
    }


}

