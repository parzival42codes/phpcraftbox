<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ApplicationAdministrationSystemCache" path="/default/title"
        language-de_DE="Cache Inhalt"
        language-en_US="Cache Content"}
    </div>
    <div class="card-container-content">
        <div class="card-container-content">
            <CMS function="_table">
                {
                "_config": {
                "cssClass": "template-table-standard template-table-standard-small template-table-standard-monospace",
                "table": "Table",
                "source": "Table"
                },
                "key": {
                "titleHeader": "{insert/language class="ApplicationAdministrationSystemCache" path="/table/key"
                language-de_DE="Schlüssel"
                language-en_US="Key"}"
                },
                "value": {
                "titleHeader": "{insert/language class="ApplicationAdministrationSystemCache" path="/table/content"
                language-de_DE="Inhalt"
                language-en_US="Content"}"
                },
                "ttl": {
                "titleHeader": "{insert/language class="ApplicationAdministrationSystemCache" path="/table/ttl"
                language-de_DE="TTL"
                language-en_US="TTL"}"
                },
                "size": {
                "titleHeader": "{insert/language class="ApplicationAdministrationSystemCache" path="/table/size"
                language-de_DE="Größe"
                language-en_US="Size"}"
                }
                }
            </CMS>
        </div>
    </div>
</div>
