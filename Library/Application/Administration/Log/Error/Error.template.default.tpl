[data=LogErrorTable]

[_config]
cssClass = "template-table-standard template-table-standard-small template-table-standard-monospace"
table = "LogErrorTable"
source = "LogErrorTable"

[crudId]
titleHeader = "{insert/language class="ApplicationAdministrationLogError" path="/table/crudId"
language-de_DE="ID"
language-en_US="ID"}"
[crudType]
titleHeader = "{insert/language class="ApplicationAdministrationLogError" path="/table/crudType"
language-de_DE="Type"
language-en_US="Type"}"
[crudPath]
titleHeader = "{insert/language class="ApplicationAdministrationLogError" path="/table/crudPath"
language-de_DE="Path"
language-en_US="Path"}"
classCell="breakWord"
[crudTitle]
titleHeader = "{insert/language class="ApplicationAdministrationLogError" path="/table/crudTitle"
language-de_DE="Fehlermeldung"
language-en_US="Error Message"}"
classCell="breakWord"
[crudContent]
titleHeader = "{insert/language class="ApplicationAdministrationLogError" path="/table/crudContent"
language-de_DE="Inhalt"
language-en_US="Content"}"
[crudBacktrace]
titleHeader = "{insert/language class="ApplicationAdministrationLogError" path="/table/crudBacktrace"
language-de_DE="Zurückverfolgung"
language-en_US="Backtrace"}"
[dataVariableCreated]
titleHeader = "{insert/language class="ApplicationAdministrationLogError" path="/table/dataVariableCreated"
language-de_DE="Erstellt"
language-en_US="Created"}"

[/data]

<div class="flex-container">
    <div style="flex: 2;">
        {create/pagination ident="error"}
    </div>
    <div style="flex: 1;">
        {create/filter ident="error"}
    </div>
    <div style="flex: 1;">
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationAdministrationLogError" path="/cache/clean"
                language-de_DE="Cache Löschen"
                language-en_US="Erase Cache"}
            </div>

            <div class="card-container-content">
                <a class="btn" href="index.php?application=ApplicationAdministrationLogError&cache=clean">{insert/language class="ApplicationAdministrationLogError" path="/cache/clean"
                    language-de_DE="Cache Löschen"
                    language-en_US="Erase Cache"}</a>
            </div>
        </div>
    </div>
</div>


<div class="ApplicationAdministrationLogError">
    <div class="card-container card-container--shadow">
        <div class="card-container-header">
            {insert/language class="ApplicationAdministrationLogError" path="/table/logErrorTableHeader"
            language-de_DE="Error Log"
            language-en_US="Error Log"}
        </div>

        <div class="card-container-content">
            {create/table data="LogErrorTable"}
        </div>
    </div>
</div>
{create/pagination ident="error"}
