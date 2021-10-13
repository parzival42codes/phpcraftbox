<?php declare(strict_types=1);

/**
 * Form Filter Helper
 *
 * Form Filter Helper
 *
 * @author   Stefan Schlombs
 * @version  1.0.0
 * @modul    versionRequiredSystem 1.0.0
 * @modul    hasCSS
 * @modul    language_name_de_DE Formular Filter Helfer
 * @modul    language_name_en_US Form Filter Helper
 * @modul    language_path_de_DE /Template/Formular
 * @modul    language_path_en_US /template/Form
 *
 */
class ContainerExtensionTemplateParseCreateFilterHelper extends Base
{

    protected string       $id              = '';
    protected array        $filterValues    = [];
    protected array        $filter          = [];
    protected static array $filterContainer = [];

    public function __construct(string $id)
    {
        $this->id = 'templateFilter' . $id;
    }

    /**
     * @param string     $key
     * @param mixed      $value
     * @param string     $title
     * @param string     $type
     * @param  $data
     * @param array      $option
     */
    public function addFilter(string $key, $value, string $title, string $type, array $data = null, array $option = []): void
    {
        $this->filter[$key] = [
            'title'  => $title,
            'type'   => $type,
            'value'  => $value,
            'data'   => $data,
            'option' => $data,
        ];
    }

    /**
     * @throws DetailedException
     */
    public function create(): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormRequest $request */
        $request = Container::get('ContainerExtensionTemplateParseCreateFormRequest',
                                  $this->id);

        /** @var ContainerFactoryRequest $filterData */
        $filterData = Container::get('ContainerFactoryRequest',
                                     ContainerFactoryRequest::REQUEST_GET,
                                     '_filter');

        if ($filterData->exists()) {
            parse_str(base64_decode($filterData->get()),
                      $this->filterValues);
        }

        foreach ($this->filter as $filterKey => $filter) {
            if ($filter['type'] === 'select') {
                /** @var ContainerExtensionTemplateParseCreateFormElementSelect $element */
                $element = Container::get('ContainerExtensionTemplateParseCreateFormElementSelect',
                                          $filter['data']);
                $element->setLabel($filter['title']);
                $element->setValue(($this->filterValues[$filterKey] ?? ($filter[$filterKey] ?? '')));

                $request->addElement($filterKey,
                                     $element);
            }
            elseif ($filter['type'] === 'input') {
                /** @var ContainerExtensionTemplateParseCreateFormElementSelect $element */
                $element = Container::get('ContainerExtensionTemplateParseCreateFormElementText',
                                          $filter['data']);
                $element->setLabel($filter['title']);
                $element->setValue(($this->filterValues[$filterKey] ?? ($filter[$filterKey] ?? '')));

//                if (isset($filter['option']['modify'])) {
//                    $element->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired');
//                }

                $request->addElement($filterKey,
                                     $element);
            }
            elseif ($filter['type'] === 'date') {
                /** @var ContainerExtensionTemplateParseCreateFormElementSelect $element */
                $element = Container::get('ContainerExtensionTemplateParseCreateFormElementDate',
                                          $filter['data']);
                $element->setLabel($filter['title']);
                $element->setValue(($this->filterValues[$filterKey] ?? ($filter[$filterKey] ?? '')));

//                if (isset($filter['option']['modify'])) {
//                    $element->addModify('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired');
//                }

                $request->addElement($filterKey,
                                     $element);
            }
        }

        $this->formResponse();

        $request->create();

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'default,item,send');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['default']);

        $templateFilterCollect = '';
        foreach ($this->filter as $filterKey => $filter) {
            /** @var ContainerExtensionTemplate $templateFilter */
            $templateFilter = Container::get('ContainerExtensionTemplate');
            $templateFilter->set($templateCache->get()['item']);
            $templateFilter->assign('label',
                                    ContainerExtensionTemplateParseCreateFormRequest::getLabel($this->id . '_' . $filterKey));
            $templateFilter->assign('element',
                                    ContainerExtensionTemplateParseCreateFormRequest::getElement($this->id . '_' . $filterKey));
            $templateFilter->parseString();
            $templateFilterCollect .= $templateFilter->get();
        }

        $template->assign('items',
                          $templateFilterCollect);

        /** @var ContainerExtensionTemplate $templateSend */
        $templateSend = Container::get('ContainerExtensionTemplate');
        $templateSend->set($templateCache->get()['send']);
        $templateSend->assign('item',
                              ContainerExtensionTemplateParseCreateFormRequest::getElement($this->id . '_Header') . ContainerExtensionTemplateParseCreateFormRequest::getElement($this->id . '_Footer'));
        $templateSend->parseString();

        $template->assign('send',
                          $templateSend->get());

        $template->parseString();

        self::$filterContainer[$this->id] = $template->get();
    }

    public static function getFilter(string $key): string
    {
        return self::$filterContainer['templateFilter' . $key];
    }


    protected function formResponse(): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormResponse $response */
        $response = Container::get('ContainerExtensionTemplateParseCreateFormResponse',
                                   $this->id);

        if ($response->isHasResponse()) {
            /** @var ContainerFactoryRouter $router */
            $router = clone Container::getInstance('ContainerFactoryRouter');

            $filterQuery = [];
            foreach ($this->filter as $filterKey => $filter) {
                $filterQuery[$filterKey] = $response->get($filterKey);
            }

            $router->setQuery('_filter',
                              base64_encode(http_build_query($filterQuery)));
            $router->setQuery('_form',
                              null);
            $router->setQuery('_page',
                              null);

            $router->redirect();

        }

    }

    /**
     * @return array
     */
    public function getFilterValues(): array
    {
        return $this->filterValues;
    }

    /**
     * @return array
     */
    public function getFilterCrud(): array
    {
        $filterCrud = [];

        foreach ($this->filterValues as $filterValueKey => $filterValueItem) {
            if (!empty($filterValueItem)) {
                $filterCrud[$filterValueKey] = $filterValueItem;
            }
        }

        return $filterCrud;
    }

    /**
     * @param array $filterValues
     */
    public function setFilterValues(array $filterValues): void
    {
        $this->filterValues = $filterValues;
    }
}

