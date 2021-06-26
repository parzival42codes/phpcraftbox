<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ApplicationUserMessage" path="/table/header/title"
        language-de_DE="Nachrichten"
        language-en_US="Messages"}
    </div>
    <div class="card-container-content">

        <div class="card-container-content">
            <CMS function="_table">
                {
                "_config": {
                "cssClass": "template-table-standard template-table-standard-small template-table-standard-monospace",
                "table": "table",
                "source": "table"
                },
                "title": {
                "titleHeader": "{insert/language class="ApplicationUserMessage" path="/table/title"
                language-de_DE="Titel"
                language-en_US="Title"}"
                },
                "message": {
                "titleHeader":
                "{insert/language class="ApplicationUserMessage" path="/table/message"
                language-de_DE="Nachricht"
                language-en_US="Message"}",
                "classCell": "withFill"
                },
                "userSource": {
                "titleHeader": "{insert/language class="ApplicationUserMessage" path="/table/userSource"
                language-de_DE="Von"
                language-en_US="From"}"
                },
                "date": {
                "titleHeader": "{insert/language class="ApplicationUserMessage" path="/table/date"
                language-de_DE="Datum"
                language-en_US="Date"}",
                "rowParameter": "date"
                }
                }
            </CMS>
        </div>
    </div>
</div>
