[data=LogNotificationTable]

[_config]
cssClass = "template-table-standard template-table-standard-small template-table-standard-monospace"
table = "LogNotificationTable"
source = "LogNotificationTable"

[crudId]
titleHeader = "{insert/language class="ApplicationAdministrationLogNotification" path="/table/crudId"
language-de_DE="ID"
language-en_US="ID"}"
[crudMessage]
titleHeader = "{insert/language class="ApplicationAdministrationLogNotification" path="/table/crudMessage"
language-de_DE="Nachricht"
language-en_US="Message"}"
[crudClass]
titleHeader = "{insert/language class="ApplicationAdministrationLogNotification" path="/table/crudClass"
language-de_DE="Klasse"
language-en_US="Class"}"
[crudClassIdent]
titleHeader = "{insert/language class="ApplicationAdministrationLogNotification" path="/table/crudClassIdent"
language-de_DE="Ident"
language-en_US="Ident"}"
[crudData]
titleHeader = "{insert/language class="ApplicationAdministrationLogNotification" path="/table/crudData"
language-de_DE="Daten"
language-en_US="Data"}"
[userName]
titleHeader = "{insert/language class="ApplicationAdministrationLogNotification" path="/table/userName"
language-de_DE="Benutzer"
language-en_US="User"}"
[dataVariableCreated]
titleHeader = "{insert/language class="ApplicationAdministrationLogNotification" path="/table/dataVariableCreated"
language-de_DE="Erstellt"
language-en_US="Created"}"

[/data]

<div class="flex-container">
    <div style="flex: 2;">
        {create/pagination ident="notification"}
    </div>
    <div style="flex: 1;">
        {create/filter ident="notification"}
    </div>
</div>

<div class="ApplicationAdministrationLogNotification">
    <div class="card-container card-container--shadow">
        <div class="card-container-header">
            {insert/language class="ApplicationAdministrationLogNotification" path="/table/LogNotificationTableHeader"
            language-de_DE="Notification Log"
            language-en_US="Notification Log"}
        </div>

        <div class="card-container-content">
            {create/table data="LogNotificationTable"}
        </div>
    </div>
</div>
{create/pagination ident="notification"}
