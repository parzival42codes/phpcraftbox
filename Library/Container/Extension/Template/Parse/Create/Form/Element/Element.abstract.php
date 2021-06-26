<?php declare(strict_types=1);

abstract class ContainerExtensionTemplateParseCreateFormElement_abstract extends Base
{
    const ELEMENT_CAN_BE_OPTIONAL = true;

    protected string $formId    = '';
    protected  $name      = '';
    protected array  $modify    = [];
    protected array  $metaData  = [];
    protected string $label     = '';
    protected  $value     = '';
    protected string $info      = '';
    protected string $error     = '';
    protected array  $parameter = [];
    protected int    $flex      = 1;

    public function __construct(...$parameter)
    {
        $this->parameter = $parameter;
    }

    abstract function get(): string;

    public function addModify($value): void
    {
        $this->modify[] = $value;
    }

    public function getModifier(): array
    {
        return $this->modify;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getFormId(): string
    {
        return $this->formId;
    }

    /**
     * @param string $formId
     */
    public function setFormId(string $formId): void
    {
        $this->formId = $formId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int $key
     *
     * @return array
     */
    public function getParameter(int $key)
    {
        return $this->parameter[$key];
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameter;
    }

    /**
     * @return string
     */
    public function getInfo(): string
    {
        return $this->info;
    }

    /**
     * @param string $info
     */
    public function setInfo(string $info): void
    {
        $this->info = $info;
    }

    public function getStdAttribut(): ContainerIndexHtmlAttribute
    {

        /** @var ContainerIndexHtmlAttribute $stdElementAttribut */
        $stdElementAttribut = Container::get('ContainerIndexHtmlAttribute');
        $stdElementAttribut->set('id',
                                 null,
                                 $this->getFormId() . ucfirst($this->getName()));
        $stdElementAttribut->set('form',
                                 null,
                                 $this->getFormId());
        $stdElementAttribut->set('name',
                                 null,
                                 $this->getName());
        $stdElementAttribut->set('value',
                                 null,
                                 $this->getValue());
        $stdElementAttribut->set('class',
                                 null,
                                 'ContainerExtensionTemplateParseCreateForm-input');
        return $stdElementAttribut;

    }


    public function doModifier(ContainerIndexHtmlAttribute $attribut): void
    {
        $modifier = $this->getModifier();

        if (is_array($modifier)) {
            foreach ($modifier as $modifierItem) {

                if (is_callable($modifierItem)) {
                    call_user_func($modifierItem,
                                   $this,
                                   $attribut,
                                   null);
                }
                elseif (is_array($modifierItem)) {

                    if (
                    class_exists($modifierItem[0])
                    ) {
                        if (is_array($modifierItem[1])) {
                            /** @var ContainerExtensionTemplateParseCreateFormModify_abstract $classObject */
                            $classObject = Container::get($modifierItem[0],
                                                          $this,
                                                          $attribut,
                                                          $modifierItem[1]);
                            $classObject->modify();
                        }
                        else {

                            if (
                            method_exists($modifierItem[0],
                                          (string)$modifierItem[1])
                            ) {
                                $staticCall = $modifierItem[0] . '::' . $modifierItem[1];

                                $staticCall($this,
                                            $attribut);
                            }
                            else {
                                $classObject = Container::get($modifierItem[0],
                                                              $this,
                                                              $attribut,
                                                              $modifierItem[1]);
                                $classObject->modify();
                            }


                        }
                    }
                    elseif (is_callable($modifierItem[0])) {
                        call_user_func($modifierItem[0],
                                       $this,
                                       $attribut,
                                       $modifierItem[1]);
                    }

                }
                else {
                    if (!empty($modifierItem)) {

                        /** @var ContainerExtensionTemplateParseCreateFormModify_abstract $validatorObject */
                        $validatorObject = Container::get($modifierItem,
                                                          $this,
                                                          $attribut,
                                                          $modifierItem);
                        $validatorObject->modify();
                    }
                }

            }
        }
    }


    public function getStdInputTemplate(ContainerIndexHtmlAttribute $attribut): string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        'ContainerExtensionTemplateParseCreateFormElement',
                                        'input');

        /** @var ContainerIndexHtmlAttribute $attribut */
        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['input']);
        $template->assign('input',
                          $attribut->get('type'));
        $template->assign('attribut',
                          $attribut->getHtml());

        $template->parse();

        return $template->get();
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }

    /**
     * @param array $metaData
     */
    public function setMetaData(array $metaData): void
    {
        $this->metaData = array_merge($this->metaData,
                                      $metaData);
    }

    /**
     * @return int
     */
    public function getFlex(): int
    {
        return $this->flex;
    }

    /**
     * @param int $flex
     */
    public function setFlex(int $flex): void
    {
        $this->flex = $flex;
    }

}
