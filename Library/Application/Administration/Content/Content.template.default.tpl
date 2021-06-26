[data=contentTable]

[_config]
cssClass = "template-table-standard template-table-standard-small template-table-standard-monospace"
table = "ContentTable"
source = "ContentTable"

[crudIdent]
titleHeader = "{insert/language class="ApplicationAdministrationContent" path="/table/crudIdent"
language-de_DE="Contentseite Ident"
language-en_US="Content Page Ident "}"
classCell="withFill"
[crudData]
titleHeader = "{insert/language class="ApplicationAdministrationContent" path="/table/crudData"
language-de_DE="Daten"
language-en_US="Data"}"
[dataVariableCreated]
titleHeader = "{insert/language class="ApplicationAdministrationContent" path="/table/dataVariableCreated"
language-de_DE="Erstellt"
language-en_US="Created"}"
[dataVariableEdited]
titleHeader = "{insert/language class="ApplicationAdministrationContent" path="/table/dataVariableUpdated"
language-de_DE="Bearbeitet"
language-en_US="Updated"}"
[createIndex]
titleHeader = "{insert/language class="ApplicationAdministrationContent" path="/table/createIndex"
language-de_DE="Index erstellen"
language-en_US="Create Index"}"

[/data]

<div class="flex-container">
    <div class="flex-container-item" style="flex: 2;">
        {create/pagination ident="content"}
    </div>
</div>

<div class="ApplicationAdministrationLContent">
    <div class="card-container card-container--shadow">
        <div class="card-container-header">
            {insert/language class="ApplicationAdministrationContent" path="/table/contentTableHeader"
            language-de_DE="Content Seiten"
            language-en_US="Content Pages"}
        </div>

        <div class="card-container-content">
            {create/table data="contentTable"}
        </div>
    </div>
</div>
{create/pagination ident="content"}



