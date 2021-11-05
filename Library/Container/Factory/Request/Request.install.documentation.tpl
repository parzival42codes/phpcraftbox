<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ContainerFactoryRequest" path="/documentation/reuquest/code"
        language-de_DE="Ein Request ausführen"
        language-en_US="Create a request"}
    </div>
    <div class="card-container-content">
        <div class="btn copyToClipboard"
             data-id="{$copyToClipboard}">{insert/language class="ContainerFactoryLanguage" path="/standard/template/copyToClipboard" import="template"}</div>
        <div class="code"
             id="{$copyToClipboard}">
            <CMS function="_code">
                /** @var ContainerFactoryRequest $requestModify */
                $requestModify = Container::get('ContainerFactoryRequest',
                ContainerFactoryRequest::REQUEST_POST,
                '_modify');
            </CMS>
        </div>
    </div>
</div>
