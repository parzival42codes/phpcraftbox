<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ContainerFactoryLanguage" path="/documentation/readLanguage/descriptiion" import="documentation"
        language-de_DE="Einfügen & Lesen einer Sprachdefinition"
        language-en_US="Insert & read a language definition"}
    </div>
    <div class="card-container-content">
        <div class="btn copyToClipboard"
             data-id="{$copyToClipboard}1">{insert/language class="ContainerFactoryLanguage" path="/standard/template/copyToClipboard" import="template"}</div>
        <div class="code"
             id="{$copyToClipboard}1">
            <CMS function="_code">
                ContainerFactoryLanguage::get('/ApplicationUserRegister/notification/registered',
                [
                'de_DE' => '',
                'en_US' => '',
                ]);
            </CMS>
        </div>
    </div>
</div>

<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ContainerFactoryLanguage" path="/documentation/template/descriptiion" import="documentation"
        language-de_DE="Aus einer Template Datei"
        language-en_US="From a template file"}
    </div>
    <div class="card-container-content">
        <div class="btn copyToClipboard"
             data-id="{$copyToClipboard}2">{insert/language class="ContainerFactoryLanguage" path="/standard/template/copyToClipboard" import="template"}</div>
        <div class="code"
             id="{$copyToClipboard}2">
            <CMS function="_code">
                {insert/language class="ApplicationUser" path="/button/profil"
                language-de_DE="Profil"
                language-en_US="Profil"}
            </CMS>
        </div>
    </div>
</div>

<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ContainerFactoryLanguage" path="/documentation/install/descriptiion" import="documentation"
        language-de_DE="Beim Installieren"
        language-en_US="At Installation"}
    </div>
    <div class="card-container-content">
        <div class="btn copyToClipboard"
             data-id="{$copyToClipboard}3">{insert/language class="ContainerFactoryLanguage" path="/standard/template/copyToClipboard" import="template"}</div>
        <div class="code"
             id="{$copyToClipboard}3">
            <CMS function="_code">
                $this->readLanguageFromFile('...');
                $this->importLanguage();
            </CMS>
        </div>
    </div>
</div>

<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ContainerFactoryLanguage" path="/documentation/file/descriptiion" import="documentation"
        language-de_DE="Beispiel Sprach Datei"
        language-en_US="Example Language File"}
    </div>
    <div class="card-container-content">
        <div class="btn copyToClipboard"
             data-id="{$copyToClipboard}4">{insert/language class="ContainerFactoryLanguage" path="/standard/template/copyToClipboard" import="template"}</div>
        <div class="code"
             id="{$copyToClipboard}4">
            <CMS function="_code">
                {
                "type": "install.language",
                "content": {
                "/meta/title": {
                "de_DE": "Benutzer Übersicht",
                "en_US": "User Overview"
                },
                "/meta/description": {
                "de_DE": "Benutzer Übersicht",
                "en_US": "User Overview"
                },
                "/breadcrumb": {
                "de_DE": "Benutzer Übersicht",
                "en_US": "User Overview"
                }
                }
                }
            </CMS>
        </div>
    </div>
</div>

