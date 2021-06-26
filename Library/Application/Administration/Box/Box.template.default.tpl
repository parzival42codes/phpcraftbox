[data=ApplicationAdministrationBox]

[_config]
cssClass = "template-table-standard"
table = "ApplicationAdministrationBox"
source = "ApplicationAdministrationBox"

[crudAssignment]
titleHeader = "{insert/language class="ApplicationAdministrationBox" path="/table/assignment"
language-de_DE="Box Zuordnung"
language-en_US="Box assignment"}"
[count]
titleHeader = "{insert/language class="ApplicationAdministrationBox" path="/table/count"
language-de_DE="Anzahl"
language-en_US="Count"}"
[/data]

<div class="card-container card-container--shadow">
    <div class="card-container-header">{insert/language class="ApplicationAdministrationBox" path="/table/title"
        language-de_DE="Boxen"
        language-en_US="Boxes"}</div>
    <div class="card-container-content">
        {create/table data="ApplicationAdministrationBox"}
    </div>
</div>
