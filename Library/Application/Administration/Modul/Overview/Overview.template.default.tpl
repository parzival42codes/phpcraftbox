<div class="flex-container">
    <div class="flex-container-item"
         style="flex: 1;">
        {create/pagination ident="crudPagination"}
    </div>
</div>

<div class="ApplicationAdministrationLContent">
    <div class="card-container card-container--shadow">
        <div class="card-container-header">
            {insert/language class="ApplicationAdministrationModulOverview" path="/table/contentTableHeader"
            language-de_DE="Modul Seiten"
            language-en_US="Modul Pages"
            description="CltkZV9ERV0KZGVzY3JpcHRpb249IktvcGZ6ZWlsZSBkZXIgQ29udGVudCBUYWJlbGxlIgpbZW5fVVNdCmRlc2NyaXB0aW9uPSJIZWFkZXIgZnJvbSB0aGUgQ29udGVudCBUYWJsZSIK"}
        </div>

        <div class="card-container-content">
            <CMS  function="_table">
                {
                "_config": {
                "cssClass": "template-table-standard template-table-standard-small template-table-standard-monospace",
                "table": "ModulTable",
                "source": "ModulTable"
                },
                "crudClassDetail": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModulOverview" path="/table/crudModul"
                language-de_DE="Class / Modul Name"
                language-en_US="Class / Modul Name"}",
                "classCell": "withFill"
                },
                "crudParentModul": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModulOverview" path="/table/crudParentModul"
                language-de_DE="Eltern Modul"
                language-en_US="Parent Modul"}"
                },
                "crudVersion": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModulOverview" path="/table/crudVersion"
                language-de_DE="Version"
                language-en_US="Version"}"
                },
                "crudVersionRequiredSystem": {
                "titleHeader":
                "{insert/language class="ApplicationAdministrationModulOverview" path="/table/crudVersionRequiredSystem"
                language-de_DE="Benötigte Version"
                language-en_US="Required Version"}"
                },
                "crudDependency": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModulOverview" path="/table/crudDependency"
                language-de_DE="Abhängigkeit"
                language-en_US="Dependency"}"
                },
                "crudHasJavascript": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModulOverview" path="/table/crudHasJavascript"
                language-de_DE="Javascript"
                language-en_US="Javascript"}",
                "rowParameter": "Yesno"
                },
                "crudHasCss": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModulOverview" path="/table/crudHasCss"
                language-de_DE="CSS"
                language-en_US="CSS"}",
                "rowParameter": "Yesno"
                },
                "crudHasContent": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModulOverview" path="/table/crudHasContent"
                language-de_DE="Content"
                language-en_US="Content"}",
                "rowParameter": "Yesno"
                },
                "crudActive": {
                "titleHeader": "{insert/language class="ApplicationAdministrationModulOverview" path="/table/crudActive"
                language-de_DE="Aktiv"
                language-en_US="Activ"}",
                "rowParameter": "Yesno"
                }
                }
            </CMS>
        </div>
    </div>
</div>
{create/pagination ident="crudPagination"}



