<div id="ApplicationAdministrationLContent" data-securekey="{$generatedSecureKey}">
    <div class="card-container card-container--shadow">
        <div class="card-container-header">
            {insert/language class="ApplicationAdministrationModul" path="/table/contentTableHeader"
            language-de_DE="Custom"
            language-en_US="Custom"
            description="CltkZV9ERV0KZGVzY3JpcHRpb249IktvcGZ6ZWlsZSBkZXIgQ29udGVudCBUYWJlbGxlIgpbZW5fVVNdCmRlc2NyaXB0aW9uPSJIZWFkZXIgZnJvbSB0aGUgQ29udGVudCBUYWJsZSIK"}

        </div>

        <div class="card-container-content">
            <CMS function="_table">
                {
                "_config": {
                "cssClass": "template-table-standard template-table-standard-small template-table-standard-monospace",
                "table": "ModulTable",
                "source": "TableData"
                },
                "name": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModul" path="/table/name"
                language-de_DE="Name"
                language-en_US="Name"}"
                },
                "description": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModul" path="/table/description"
                language-de_DE="Beschreibung"
                language-en_US="Description"}",
                "classCell": "withFill"
                },
                "version": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModul" path="/table/version"
                language-de_DE="Version"
                language-en_US="Version"}"
                },
                "author": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModul" path="/table/author"
                language-de_DE="Author"
                language-en_US="Author"}"
                },
                "UnInstall": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModul" path="/table/deInstall"
                language-de_DE="Installieren / Deinstallieren"
                language-en_US="Install / Deinstall"}"
                },
                "InActive": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModul" path="/table/inActive"
                language-de_DE="Aktivieren / Deaktivieren"
                language-en_US="Activate / Deactivate"}"
                }
                }
            </CMS>
        </div>
    </div>

</div>
