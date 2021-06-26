<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ContainerExtensionTemplateLoad" path="/documentation/loadFromCacheAndTemplate/descriptiion" import="documentation"
        language-de_DE="Laden eines Templates aus dem Cache und einfügen in das Template Object"
        language-en_US="Load a template from the cache and insert it into the template object"}
    </div>
    <div class="card-container-content">
        <div class="btn copyToClipboard"
             data-id="{$copyToClipboard}">{insert/language class="ContainerFactoryLanguage" path="/standard/template/copyToClipboard" import="template"}</div>
        <div class="code"
             id="{$copyToClipboard}">
            <CMS function="_code">
                /** @var ContainerExtensionTemplateLoad_cache_template $templateCache */
                $templateCache = Container::get('ContainerExtensionTemplateLoad_cache_template',
                Core::getRootClass(__CLASS__),
                '');
                $templateCacheContent = $templateCache->getCacheContent();

                /** @var ContainerExtensionTemplate $template */
                $template = Container::get('ContainerExtensionTemplate');
                $template->set($templateCacheContent['']);
                $template->assign('',
                $tableTcs);

                $template->parse();
                $template->get();
            </CMS>
        </div>
    </div>
</div>

