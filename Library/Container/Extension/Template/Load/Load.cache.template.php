<?php declare(strict_types=1);

class ContainerExtensionTemplateLoad_cache_template extends ContainerExtensionCache_abstract
{

    protected string $templates = '';

    public function prepare(): void
    {
        $class           = $this->parameter[0];
        $this->templates = $this->parameter[1];

        $this->ident = __CLASS__ . '/' . $class . '/' . $this->templates;

        $this->setPersistent(true);
    }

    public function create(): void
    {
        $class = $this->parameter[0];

        $templates = explode(',',
                             $this->templates);

        $this->cacheContent = [];
        foreach ($templates as $templatesItem) {
            /** @var ContainerFactoryFile $fileTemplate */
            $fileTemplate = Container::get('ContainerFactoryFile',
                                           $class . '.template.' . $templatesItem . '.tpl');

            if ($fileTemplate->exists() === true) {
                $fileTemplate->load();
                $this->cacheContent[$templatesItem] = $fileTemplate->get();
            }
            else {
                \CoreErrorhandler::trigger(__METHOD__,
                                           'templateLoadError',
                                           [
                                               'filename' => $class . '.template.' . $templatesItem . '.tpl'
                                           ]);
            }
        }

    }

}
