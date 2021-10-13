<?php declare(strict_types=1);


final class  CoreDebugDumpObject extends CoreDebugDump_abstract_api
{
    protected array $tempCache = [];

    public function execute(): void
    {

        $this->title .= ' | ' . get_class($this->dump) . ' | Object';

        /** @var ContainerIndexTab $eventTab */
        $eventTab = Container::get('ContainerIndexTab',
                                   'tabDebugBarDebugDumpObject' . uniqid());

        /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
        $templateCache   = Container::get('ContainerExtensionTemplateLoad_cache_template',
                                          Core::getRootClass(__CLASS__),
                                          'methods,properties');
        $this->tempCache = $templateCache->get();

        $this->getTabProperties($eventTab);
        $this->getTabMethods($eventTab);
        $this->getTabRaw($eventTab);

        $eventTab->setConfig('triggerFirst',
                             true);
        $eventTab->setConfig('titleWithtMax',
                             false);

        $this->content = $eventTab->get();

    }

    protected function getTabRaw(ContainerIndexTab $eventTab): void
    {
        $dumpData = htmlentities(var_export($this->dump,
                                            true) ?? '');

        /** @var ContainerIndexTabItem $eventTabItem */
        $eventTabItem = $eventTab->createTab('raw');
        $eventTabItem->setTitle('Raw');
        $eventTabItem->setContent('<pre>' . ((strlen($dumpData) < 2000) ? $dumpData : '<details><summary> - Dump too big - klick to view - </summary><div>' . $dumpData . '</div></details>') . '</pre>');
    }

    protected function getTabMethods(ContainerIndexTab $eventTab): void
    {

        /** @var ContainerIndexTabItem $eventTabItem */
        $eventTabItem = $eventTab->createTab('methods');
        $eventTabItem->setTitle('Methods');

        /** @var ReflectionClass $reflectionClass */
        $reflectionClass = new \ReflectionClass(get_class($this->dump));

        $reflectionClassMethods = $reflectionClass->getMethods();

        $tableTcs = [];
        foreach ($reflectionClassMethods as $reflectionClassMethodsItem) {
            if (
                strpos($reflectionClassMethodsItem->getName(),
                       '___') === false
            ) {

                $reflectionMethod = $reflectionClass->getMethod($reflectionClassMethodsItem->name);

                $reflectionMethodParametersCollect = [];
                $reflectionMethodParameters        = $reflectionMethod->getParameters();
                foreach ($reflectionMethodParameters as $reflectionMethodParameter) {
                    $reflectionMethodParametersCollect[] = $reflectionMethodParameter->getName();
                }

                $reflectionMethodType = '?';
                if ($reflectionMethod->isPublic() === true) {
                    $reflectionMethodType = 'public';
                }
                elseif ($reflectionMethod->isProtected() === true) {
                    $reflectionMethodType = 'protected';
                }
                elseif ($reflectionMethod->isPrivate() === true) {
                    $reflectionMethodType = 'private';
                }

                $tableTcs[] = [
                    'getName'       => $reflectionClassMethodsItem->getName(),
                    'type'          => $reflectionMethodType,
                    'isStatic'      => $reflectionMethod->isStatic(),
                    'isAbstract'    => $reflectionMethod->isAbstract(),
                    'getDocComment' => (!empty($reflectionMethod->getDocComment()) ? htmlspecialchars($reflectionMethod->getDocComment()) : ''),
                    'parameter'     => (!empty($reflectionMethodParametersCollect) ? '$' . implode(', $',
                                                                                                   $reflectionMethodParametersCollect) : ''),
                ];

            }

        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($this->tempCache['methods']);
        $template->assign('DebugObjectDataMethods_DebugObjectDataMethods',
                          $tableTcs);

        //  simpleDebugDump($template->get());

        $template->parseQuote();
        $template->parse();

        $eventTabItem->setContent(trim($template->get()));

    }

    protected function getTabProperties(ContainerIndexTab $eventTab): void
    {
        /** @var ContainerIndexTabItem $eventTabItem */
        $eventTabItem = $eventTab->createTab('properties');
        $eventTabItem->setTitle('Properties');

        /** @var ReflectionClass $reflectionClass */
        $reflectionClass = new \ReflectionClass(get_class($this->dump));

        $reflectionClassProperties = $reflectionClass->getProperties();

        $tableTcs = [];
        /** @var ReflectionProperty $reflectionClassPropertiesItem */
        foreach ($reflectionClassProperties as $reflectionClassPropertiesItem) {

            if (
                strpos($reflectionClassPropertiesItem->getName(),
                       '___') === false
            ) {

                $propertyType = '?';
                if ($reflectionClassPropertiesItem->isPublic() === true) {
                    $propertyType = 'public';
                }
                elseif ($reflectionClassPropertiesItem->isProtected() === true) {
                    $propertyType = 'protected';
                }
                elseif ($reflectionClassPropertiesItem->isPrivate() === true) {
                    $propertyType = 'private';
                }

                $reflectionClassPropertiesItem->setAccessible(true);

                $valueContent = $reflectionClassPropertiesItem->getValue($this->dump);

                if (empty($valueContent)) {
                    $valueContent = '';
                }
                elseif (is_array($valueContent)) {
                    $valueContent = var_export($valueContent,
                                               true);
                }
                elseif (is_object($valueContent)) {
                    $valueContent = '* another Object *';
                }

                $tableTcs[] = [
                    'getProperty'   => $reflectionClassPropertiesItem->getName(),
                    'type'          => $propertyType,
                    'isStatic'      => $reflectionClassPropertiesItem->isStatic(),
                    'value'         => $valueContent,
                    'getDocComment' => $reflectionClassPropertiesItem->getDocComment(),
                ];

            }
        }

        /** @var ContainerExtensionTemplate $template */
        $template = Container::get('ContainerExtensionTemplate');
        $template->set($this->tempCache['properties']);
        $template->assign('DebugObjectDataProperties_DebugObjectDataProperties',
                          $tableTcs);

        $template->parseQuote();
        $template->parse();
        $template->catchDataClear();

        $eventTabItem->setContent(trim($template->get()));

        // simpleDebugDump($template->get());
        // eol();

    }

}
