[data=LogPageTable]

[_config]
cssClass = "template-table-standard template-table-standard-small template-table-standard-monospace"
table = "LogPageTable"
source = "LogPageTable"

[crudId]
titleHeader = "{insert/language class="ApplicationAdministrationLogPage" path="/table/crudId"
language-de_DE="ID"
language-en_US="ID"}"
[crudType]
titleHeader = "{insert/language class="ApplicationAdministrationLogPage" path="/table/crudType"
language-de_DE="Type"
language-en_US="Type"}"
[crudUrlPure]
titleHeader = "{insert/language class="ApplicationAdministrationLogPage" path="/table/crudUrlPure"
language-de_DE="URL Pur"
language-en_US="URL Pure"}"
[crudUrlReadable]
titleHeader = "{insert/language class="ApplicationAdministrationLogPage" path="/table/crudUrlReadable"
language-de_DE="URL Lesbar"
language-en_US="URL Readable"}"
[crudMessage]
titleHeader = "{insert/language class="ApplicationAdministrationLogPage" path="/table/crudMessage"
language-de_DE="Message"
language-en_US="Message"}"
[crudData]
titleHeader = "{insert/language class="ApplicationAdministrationLogPage" path="/table/crudData"
language-de_DE="Daten"
language-en_US="Data"}"
[userName]
titleHeader = "{insert/language class="ApplicationAdministrationLogPage" path="/table/userName"
language-de_DE="Benutzer"
language-en_US="User"}"
[dataVariableCreated]
titleHeader = "{insert/language class="ApplicationAdministrationLogPage" path="/table/dataVariableCreated"
language-de_DE="Erstellt"
language-en_US="Created"}"

[/data]

<div class="flex-container">
    <div style="flex: 2;">
        {create/pagination ident="page"}
    </div>
    <div style="flex: 1;">
        {create/filter ident="page"}
    </div>
</div>

<div class="ApplicationAdministrationLogPage">
    <div class="card-container card-container--shadow">
        <div class="card-container-header">
            {insert/language class="ApplicationAdministrationLogPage" path="/table/LogPageTableHeader"
            language-de_DE="Seitenaufruf Fail Log"
            language-en_US="Page Log"}
        </div>

        <div class="card-container-content">
            {create/table data="LogPageTable"}
        </div>
    </div>
</div>
{create/pagination ident="page"}
