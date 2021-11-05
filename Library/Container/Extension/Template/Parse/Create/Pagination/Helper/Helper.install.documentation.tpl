<h2>{insert/language class="ContainerExtensionTemplateParseCreatePaginationHelper" path="/documentation/reuquest/code"
    language-de_DE="Ein Request ausführen"
    language-en_US="Create a request"}</h2>

<CMS function="_code">

    $filterData = $this->getFilterData();

    /** @var ContainerExtensionTemplateParseCreateFilterHelper $filter */
    $filter = Container::get('ContainerExtensionTemplateParseCreateFilterHelper',
    'notification');

    $filter->addFilter('crudClass',
    null,
    ContainerFactoryLanguage::get('/ApplicationAdministrationLogNotification/filter/header/class'),
    'select',
    $filterData);

    $filter->addFilter('crudClassIdent',
    null,
    ContainerFactoryLanguage::get('/ApplicationAdministrationLogNotification/filter/header/ident'),
    'input',
    '');

    $filter->create();

    $filterCrud = $filter->getFilterCrud();

    /** @var ContainerFactoryLog_crud_notification $crud */
    $crud  = Container::get('ContainerFactoryLog_crud_notification');
    $count = $crud->count($filterCrud);

    /** @var ContainerFactoryLog_crud_notification $crud */
    $crud                = Container::get('ContainerFactoryLog_crud_notification');
    $crudNotificationAll = $crud->find($filterCrud,
    [],
    [
    'crudId DESC'
    ],
    $pagination->getPagesView(),
    $pagination->getPageOffset());

</CMS>
