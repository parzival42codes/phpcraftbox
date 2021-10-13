<?php declare(strict_types=1);

/**
 * Class ContainerExtensionTemplateParseCreateForm_helper
 */
class ContainerExtensionTemplateParseCreateForm_helper extends Base
{
    /**
     *
     */
    const FORM_TEMPLATE_GROUP_ROW = 'group.row';

    private array  $element        = [];
    private array  $hidden         = [];
    private string $formName       = '';
    private string $template       = '';
    private string $templateOutput = '';
    private string $class          = '';

    private object $templateCacheFormRow;


    /**
     * @var object
     */
    private object $request;

    /**
     * @var object
     */
    private object $response;

    /**
     * ContainerExtensionTemplateParseCreateForm_helper constructor.
     *
     * @param string $class
     * @param string $formName
     * @param string $template
     *
     * @throws DetailedException
     */
    public function __construct(string $class, string $formName, string $template = self::FORM_TEMPLATE_GROUP_ROW)
    {
        $this->class    = $class;
        $this->formName = $formName;
        $this->template = $template;

        $this->templateCacheFormRow = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                                     'ContainerExtensionTemplateParseCreateForm',
                                                     $template . ',' . $template . '.container');

        $this->response = Container::get('ContainerExtensionTemplateParseCreateFormResponse',
                                         $this->formName);

        $this->request = Container::get('ContainerExtensionTemplateParseCreateFormRequest',
                                        $this->formName);

    }

    /**
     * @param bool $isFlex
     *
     * @return string
     * @throws DetailedException
     */
    public function getElements(bool $isFlex = false): string
    {
        /** @var ContainerExtensionTemplateParseCreateFormRequest $request */
        $request = $this->request;

        /** @var ContainerExtensionTemplateParseCreateFormElement_abstract $element */
        foreach ($this->element as $name => $element) {
            $request->addElement($name,
                                 $element);

            if ($element instanceof ContainerExtensionTemplateParseCreateFormElementHidden) {
                $this->hidden[] = $element->getName();
                continue;
            }

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->set($this->templateCacheFormRow->get()[$this->template]);
            $template->assign('form',
                              $this->formName);
            $template->assign('name',
                              $name);

            if ($isFlex === false) {
                $template->assign('groupRowStyle',
                                  '');
            }
            else {
                $template->assign('groupRowStyle',
                                  'flex: ' . $element->getFlex());
            }

            $template->parse();

            $this->templateOutput .= $template->get();
        }

        /** @var ContainerExtensionTemplate $templateContainer */
        $templateContainer = Container::get('ContainerExtensionTemplate');
        $templateContainer->set($this->templateCacheFormRow->get()[$this->template . '.container']);
        $templateContainer->assign('content',
                                   $this->templateOutput);

        if ($isFlex === false) {
            $templateContainer->assign('groupRowContainerStyle',
                                       '');
        }
        else {
            $templateContainer->assign('groupRowContainerStyle',
                                       'display: flex;');
        }

        $this->templateOutput = '';
        $this->element        = [];

        $templateContainer->parseString();

        return $templateContainer->get();
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return '{create/form form="' . $this->formName . '" name="Header"}';
    }

    /**
     * @return string
     */
    public function getFooter(): string
    {
        $this->request->create();

        $return = '';
        foreach ($this->hidden as $hidden) {
            $return .= '{create/form form="' . $this->formName . '" name="' . $hidden . '"}';
        }

        $return .= '{create/form form="' . $this->formName . '" name="Footer"}';

        return $return;
    }

    /**
     * @return ContainerExtensionTemplateParseCreateFormRequest
     */
    public function getRequest(): ContainerExtensionTemplateParseCreateFormRequest
    {
        return $this->request;
    }

    /**
     * @return ContainerExtensionTemplateParseCreateFormResponse
     */
    public function getResponse(): ContainerExtensionTemplateParseCreateFormResponse
    {
        return $this->response;
    }

    public function addFormElement(string $name, string $type, array $parameter = [], array $modify = [], string $class = null): void
    {
        if ($class !== null) {
            $this->class = $class;
        }

        $elementType = ucfirst($type);
        $optional    = [
            'Text',
            'Textarea',
            'Checkbox',
            'Email',
            'File',
            'Radio',
            'Select',
            'Date',
        ];

        if (
            in_array($elementType,
                     $optional) && !in_array('ContainerExtensionTemplateParseCreateFormModifyValidatorRequired',
                                             $modify)
        ) {
            $modify[] = 'ContainerExtensionTemplateParseCreateFormModifyOptional';
        }

        try {
            $this->element[$name] = Container::get('ContainerExtensionTemplateParseCreateFormElement' . $elementType,
                ...
                                                   $parameter);
        } catch (Throwable $e) {
            throw new DetailedException('elementNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                $elementType
                                            ]
                                        ]);
        }

        $path  = '/' . $this->class . '/form/' . $name . '/label';
        $label = ContainerFactoryLanguage::get($path,
                                               '');

        if ($label === '') {
            CoreDebugLog::addLog('/Template/Form',
                                 'Need Label: ' . $path,
                                 CoreDebugLog::LOG_TYPE_NOTE);
        }

        $this->element[$name]->setLabel($label);
        if ($this->response->isHasResponse()) {
            $this->element[$name]->setValue($this->response->get($name));
        }
        $this->element[$name]->setInfo(ContainerFactoryLanguage::get('/' . $this->class . '/form/' . $name . '/info',
                                                                     ''));
        if ($this->response->getError($name)) {
            $this->element[$name]->setError(implode(' ',
                                                    $this->response->getError($name)));
        }

        foreach ($modify as $modifyItem) {
            $this->element[$name]->addModify($modifyItem);
        }

    }

    public function getElement(string $name): ContainerExtensionTemplateParseCreateFormElement_abstract
    {
        return $this->element[$name];
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

}

