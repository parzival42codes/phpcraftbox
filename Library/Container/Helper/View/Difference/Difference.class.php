<?php

class ContainerHelperViewDifference
{
    /**
     * @var array
     */
    protected array $source = [];
    /**
     * @var array
     */
    protected array $target = [];

    public function __construct(array $source, array $target)
    {
        $this->source = $source;
        $this->target = $target;
    }

    public function get():string
    {
        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                        Core::getRootClass(__CLASS__),
                                        'container,row.left,row.right,row.equal,row.diff');

        $all = array_keys(array_merge($this->source,
                                      $this->target));

        $output = '';

        $collectSource = [];
        $collectTarget = [];
        $collectNumericSource = [];
        $collectNumericTarget = [];

        foreach ($all as $key) {
            if (is_numeric($key)) {
                if (isset($this->source[$key])) {
                    $collectNumericSource[] = $this->source[$key];
                }
                if (isset($this->target[$key])) {
                    $collectNumericTarget[] = $this->target[$key];
                }
            }
            else {
                if (isset($this->source[$key])) {
                    $collectSource[$key] = $this->source[$key];
                }
                if (isset($this->target[$key])) {
                    $collectTarget[$key] = $this->target[$key];
                }
            }
        }

        $collect = array_keys(array_merge($collectSource,
                                          $collectTarget));

        foreach ($collect as $collectKey) {
            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->assign('source',
                              $collectSource[$collectKey]);
            $template->assign('target',
                              $collectTarget[$collectKey]);

            if (!isset($collectSource[$collectKey])) {
                $template->set($templateCache->getCacheContent()['row.left']);
            }
            elseif (!isset($collectTarget[$collectKey])) {
                $template->set($templateCache->getCacheContent()['row.right']);
            }
            elseif ($collectSource[$collectKey] === $collectTarget[$collectKey]) {
                $template->set($templateCache->getCacheContent()['row.equal']);
            }
            else {
                $template->set($templateCache->getCacheContent()['row.diff']);
            }

            $template->parseString();
            $output .= $template->get();
        }

        $collectNumeric = array_merge($collectNumericSource,
                                      $collectNumericTarget);

        foreach ($collectNumeric as $item) {

            /** @var ContainerExtensionTemplate $template */
            $template = Container::get('ContainerExtensionTemplate');
            $template->assign('source',
                              '');
            $template->assign('target',
                              '');

            if (
            !in_array($item,
                      $collectNumericSource)
            ) {
                $template->set($templateCache->getCacheContent()['row.left']);
                $template->assign('source',
                                  $item);
            }
            elseif (
            !in_array($item,
                      $collectNumericTarget)
            ) {
                $template->set($templateCache->getCacheContent()['row.right']);
                $template->assign('target',
                                  $item);
            }
            else {
                $template->set($templateCache->getCacheContent()['row.equal']);
                $template->assign('source',
                                  $item);
                $template->assign('target',
                                  $item);
            }

            $template->parseString();
            $output .= $template->get();
        }

        /** @var ContainerExtensionTemplate $template */
        $template
            = Container::get('ContainerExtensionTemplate');
        $template->set($templateCache->getCacheContent()['container']);

        $template->assign('output',
                          $output);

        $template->parseString();

        return $template->get();

    }
}
