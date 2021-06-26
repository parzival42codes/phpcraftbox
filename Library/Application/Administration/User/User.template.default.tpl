{create/filter ident="user"}
{create/pagination ident="user"}
<div class="card-container-content">
    <CMS function="_table">
        {
        "_config": {
        "cssClass": "template-table-standard template-table-standard-small template-table-standard-monospace",
        "table": "Table",
        "source": "Table"
        },
        "crudId": {
        "titleHeader": "{insert/language class="ApplicationAdministrationUser" path="/table/crudId"
        language-de_DE="ID"
        language-en_US="ID"}"
        },
        "crudUsername": {
        "titleHeader": "{insert/language class="ApplicationAdministrationUser" path="/table/userName"
        language-de_DE="Benutzername"
        language-en_US="Username"}"
        },
        "groupName": {
        "titleHeader": "{insert/language class="ApplicationAdministrationUser" path="/table/crudUserGroupId"
        language-de_DE="Gruppe"
        language-en_US="Group"}"
        },
        "crudEmail": {
        "titleHeader": "{insert/language class="ApplicationAdministrationUser" path="/table/crudEmail"
        language-de_DE="E-Mail"
        language-en_US="E-Mail"}"
        },
        "crudActivated": {
        "titleHeader": "{insert/language class="ApplicationAdministrationUser" path="/table/crudActivated"
        language-de_DE="User aktiviert ?"
        language-en_US="Breadcrumb"}",
        "rowParameter": "Yesno"
        },
        "crudEmailCheck": {
        "titleHeader": "{insert/language class="ApplicationAdministrationUser" path="/table/crudEmailCheck"
        language-de_DE="Benutzer E-Mail Geprüft ?"
        language-en_US="User E-Mail checked ?"}",
        "rowParameter": "Yesno"
        },
        "edit": {
        "titleHeader": "{insert/language class="ApplicationAdministrationUser" path="/table/edit"
        language-de_DE="Bearbeiten"
        language-en_US="Edit"}"
        }
        }
    </CMS>
</div>
{create/pagination ident="user"}
