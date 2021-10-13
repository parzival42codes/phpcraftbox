<?php

/**
 * Class ContainerExtensionTemplateParseHelperTooltip
 *
 * Creates a Tooltip over the Item
 *
 * @modul name Template Tooltip
 * @modul description Template Tooltip
 * @modul author Stefan Schlombs
 * @modul version 1.0.0
 * @modul versionRequiredSystem 1.0.0
 * @modul hasCSS
 * @modul hasJavascript
 *
 */

class ContainerExtensionTemplateParseHelperTooltip extends Base
{

    /**
     * @var array
     */
    protected static array $tooltipContent = [];

    /**
     * @var string
     */
    protected string $id = '';
    /**
     * @var string
     */
    protected string $content = '';

    public function __construct(string $id, string $content)
    {
        $this->id      = $id;
        $this->content = $content;
    }

    /**
     * @return array
     */
    public static function getTooltipContent(): array
    {
        return self::$tooltipContent;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    public function create(string $name = ''): string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'page.item');

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->get()['page.item']);

        $template->assign('id',
                          $this->id);
        $template->assign('content',
                          $this->content);

        $template->parse();
        self::$tooltipContent[$this->id] = $template->get();

        return '<span class="ContainerExtensionTemplateParseHelperTooltip_hover withFill"  data-tooltip="' . $this->id . '">' . $name . '</span>';
    }


}
