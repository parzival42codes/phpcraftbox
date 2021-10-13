<?php

class ContainerExtensionTemplateParseHelperDialog extends Base
{
    const BOX_POSITION_HEADER = 'header';
    const BOX_POSITION_CENTER = 'center';
    const BOX_POSITION_FOOTER = 'footer';

    protected ?string      $id                        = '';
    protected string       $template                  = '';
    protected string       $header                    = '';
    protected string       $headerClass               = '';
    protected string       $body                      = '';
    protected string       $bodyClass                 = '';
    protected string       $footer                    = '';
    protected string       $footerClass               = '';
    protected string       $closeIcon                 = 'X';
    protected int          $automaticClose            = 0;
    protected bool         $showAutomaticCloseProcess = false;
    protected static array $dialogContent
                                                      = [
            'header' => [],
            'center' => [],
            'footer' => [],
        ];

    public function __construct(string $id = null)
    {
        if ($id === null) {
            $id = uniqid();
        }

        $this->id = $id;

    }

    /**
     * @return array[]
     */
    public static function getDialogContent(): array
    {
        return self::$dialogContent;
    }

    public function create(string $name = '', string $box = self::BOX_POSITION_CENTER, string $class = ''): string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'box');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['box']);

        $template->assign('id',
                          $this->id);
        $template->assign('header',
                          $this->header);
        $template->assign('headerClass',
                          $this->headerClass);
        $template->assign('body',
                          $this->body);
        $template->assign('bodyClass',
                          $this->bodyClass);
        $template->assign('footer',
                          $this->footer);
        $template->assign('closeIcon',
                          $this->closeIcon);
        $template->assign('automaticClose',
                          $this->automaticClose);
        $template->assign('showAutomaticCloseProcess',
                          $this->showAutomaticCloseProcess);
        $template->assign('footerClass',
                          $this->footerClass);

        $template->parse();
        self::$dialogContent[$box][$this->id] = $template->get();
        return '<span class="ContainerExtensionTemplateParseCreateDialog_button btn ' . $class . '" data-dialog="' . $this->id . '">' . $name . '</span>';
    }

    /**
     * Set the Footer
     *
     * Content of $footer
     *
     * @param string $value
     */
    public function setFooter(string $value = ''):void
    {
        if ($value === '') {
            $value = '<span class="btn right dialog_close" style="float: right;">' . ContainerFactoryLanguage::get('/ContainerFactoryLanguage/standard/button/close') . '</span>';
        }

        $this->footer = $value;
    }

    /**
     * Close Icon
     *
     * Has the Dialog a Close Icon
     *
     * HTML:
     * .daContainerExtensionTemplateParseCreateDialog_container
     * data-closeicon = "X"
     *
     * Empty = none
     *
     * @param string $value
     */
    public function setCloseIcon(string $value):void
    {
        $this->closeIcon = $value;
    }

    /**
     * Automatic CLose
     *
     * Has the Dialog a Close Icon
     *
     * HTML:
     * .daContainerExtensionTemplateParseCreateDialog_container
     * data-automaticclose = "0"
     * 0 = none, 5 = 5 sec.
     *
     * @param int $value
     */
    public function setAutomaticClose(int $value):void
    {
        $this->automaticClose = $value;
    }

    /**
     * Show Automatic CLose
     *
     * Has the Dialog a Close Icon
     *
     * HTML:
     * .daContainerExtensionTemplateParseCreateDialog_container
     * data-showautomaticcloseprocess = "0"
     * 0 = no
     * 1 = yes
     *
     * @param bool $value
     */
    public function setShowAutomaticCloseProcess(bool $value):void
    {
        $this->showAutomaticCloseProcess = $value;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     */
    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    /**
     * @return string
     */
    public function getHeaderClass(): string
    {
        return $this->headerClass;
    }

    /**
     * @param string $headerClass
     */
    public function setHeaderClass(string $headerClass): void
    {
        $this->headerClass = $headerClass;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBodyClass(): string
    {
        return $this->bodyClass;
    }

    /**
     * @param string $bodyClass
     */
    public function setBodyClass(string $bodyClass): void
    {
        $this->bodyClass = $bodyClass;
    }

    /**
     * @return bool
     */
    public function isShowAutomaticCloseProcess(): bool
    {
        return $this->showAutomaticCloseProcess;
    }

    /**
     * @return string
     */
    public function getCloseIcon(): string
    {
        return $this->closeIcon;
    }

}
