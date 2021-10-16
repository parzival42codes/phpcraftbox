<?php declare(strict_types=1);

class ContainerExtensionTemplateParseCreateFormRequest extends Base
{
    protected static bool  $isSetParsleyTranslation = false;
    protected static array $requestElement          = [];
    protected static array $requestLabel            = [];
    protected static array $requestInfo             = [];
    protected static array $requestError            = [];
    protected static array $requestModify           = [];
    protected static array $requestMetaData         = [];
    protected static array $requestData             = [];
    protected bool         $error                   = false;

    protected string $formId   = '';
    protected array  $elements = [];

    public function __construct(string $formId)
    {
        $this->formId = $formId;

        /** @var ContainerExtensionTemplateParseCreateFormElementHeader $elementHeader */
        $elementHeader = Container::get('ContainerExtensionTemplateParseCreateFormElementHeader');
        $this->addElement('Header',
                          $elementHeader);
    }

    public function addElement(string $key, ContainerExtensionTemplateParseCreateFormElement_abstract $element): void
    {
        $element->setFormId($this->formId);
        $element->setName($key);
        $this->elements[$key] = $element;
    }

    /**
     * @param string $formid
     * @param string $key
     *
     * @return string|null
     */
    public static function getRequestData(string $formid, string $key): ?string
    {
        return (self::$requestData[$formid][$key] ?? null);
    }

    /**
     * @param string $formid
     *
     * @return array
     */
    public static function getRequestDataAll(string $formid): array
    {
        return self::$requestData[$formid];
    }

    /**
     * @param array $requestData
     */
    public static function setRequestData(string $formid, string $key, string $value): void
    {
        self::$requestData[$formid][$key] = $value;
    }

    public function create(): void
    {
        /** @var ContainerExtensionTemplateParseCreateFormElementFooter $elementFooter */
        $elementFooter            = Container::get('ContainerExtensionTemplateParseCreateFormElementFooter');
        $this->elements['Footer'] = $elementFooter;

        try {
            /** @var ContainerExtensionTemplateParseCreateFormElement_abstract $element */
            foreach ($this->elements as $key => $element) {
                $element->setFormId($this->formId);
                $element->setName($key);
                if (!empty($element->getError())) {
                    $this->error = true;
                }

                $formKey = $this->formId . '_' . $key;

                self::$requestElement[$formKey] = $element->get();
                self::$requestLabel[$formKey]   = '<label for="' . $this->formId . ucfirst($key) . '">' . $element->getLabel() . '</label>';
                self::$requestInfo[$formKey]    = '<div class="ContainerExtensionTemplateParseCreateForm-info">' . $element->getInfo() . '</div>';
                self::$requestError[$formKey]   = $element->getError();

                self::$requestModify[$this->formId][$key]   = $element->getModifier();
                self::$requestMetaData[$this->formId][$key] = $element->getMetaData();
            }
        } catch (Throwable $exception) {
            d($exception);
            eol();
        }


        /** @var ContainerIndexPage $page */
        $page = Container::getInstance('ContainerIndexPage');
        $page->addPageJavascript('
        $("#' . $this->formId . '").parsley();
              ');

        if (self::$isSetParsleyTranslation === false) {

            /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
            $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                            'ContainerExtensionTemplateParseCreateForm',
                                            'parsley_translate');
            $templates     = $templateCache->get();

            $template = Container::get('ContainerExtensionTemplate');
            $template->set($templates['parsley_translate']);

            $template->parse();

            $page->addPageJavascript($template->get());

            self::$isSetParsleyTranslation = true;
        }

    }

    public static function getElement(string $key): string
    {
        if (!isset(self::$requestElement[$key])) {
            throw new DetailedException('requestElementNotFound',
                                        0,
                                        null,
                                        [
                                            'debug' => [
                                                'key'      => $key,
                                                'elements' => implode(', ',
                                                                      array_keys(self::$requestElement)),
                                            ]
                                        ]);

        }

        return self::$requestElement[$key];
    }

    public static function getLabel(string $key): string
    {
        return (self::$requestLabel[$key] ?? '');
    }

    public static function getInfo(string $key): string
    {
        return (self::$requestInfo[$key] ?? '');
    }

    public static function getModify(string $key): array
    {
        return (self::$requestModify[$key] ?? '');
    }

    public static function getMetaData(string $key): array
    {
        return (self::$requestMetaData[$key] ?? '');
    }

    public static function getError(string $key): string
    {
        return (self::$requestError[$key] ?? '');
    }


}
