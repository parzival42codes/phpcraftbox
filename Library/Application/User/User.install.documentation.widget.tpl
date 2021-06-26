<h2>{insert/language class="ApplicationUser" path="/widget/title"
    language-de_DE="Benutzer widgets"
    language-en_US="User widgets"}</h2>

<div style="display: flex;">

    <div class="card-container card-container--shadow"
         style="flex: 1;">
        <div class="card-container-header">{insert/language class="ApplicationUser" path="/widget/link/header"
            language-de_DE="Widget für User, wenn der User nicht eingeloggt ist"
            language-en_US="Widget for User wehen not logget in"}</div>
        <div class="card-container-content">
            <div class="btn copyToClipboard"
                 data-id="{$copyToClipboard}">{insert/language class="ContainerFactoryLanguage" path="/standard/template/copyToClipboard"}</div>
            <div class="code"
                 id="{$copyToClipboard}">
                <CMS function="_code">
                    {insert/widget class="ApplicationUser" widget="link"}
                </CMS>
            </div>
        </div>
    </div>

    <div class="card-container card-container--shadow"
         style="flex: 1;">
        <div class="card-container-header">{insert/language class="ApplicationUser" path="/widget/link/header"
            language-de_DE="Widget für User, wenn der User eingeloggt ist"
            language-en_US="Widget for User wehen is logget in"}</div>
        <div class="card-container-content">
            <div class="btn copyToClipboard"
                 data-id="{$copyToClipboard}">{insert/language class="ContainerFactoryLanguage" path="/standard/template/copyToClipboard"}</div>
            <div class="code"
                 id="{$copyToClipboard}">
                <CMS function="_code">
                    {insert/widget class="ApplicationUser" widget="session"}
                </CMS>
            </div>
        </div>
    </div>

</div>

