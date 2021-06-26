<div class="card-container card-container--shadow">
    <div class="card-container-header">
        {insert/language class="ApplicationUserMessageReply" path="/table/header/title"
        language-de_DE="Nachricht Ansicht"
        language-en_US="Message View"}: {$title}
    </div>
    <div class="card-container-content">
        {$message}
    </div>
</div>

<div style="display: flex;">
    {$form}
    <div class="card-container card-container--shadow"
         style="flex: 2;">
        <div class="card-container-header">
            {insert/language class="ApplicationUserMessageReply" path="/table/header/history"
            language-de_DE="Nachhrichten"
            language-en_US="Messages"}
        </div>
        <div class="card-container-content">
            <CMS function="_table">
                {
                "_config": {
                "cssClass": "template-table-standard template-table-standard-small template-table-standard-monospace",
                "table": "table",
                "source": "table"
                },
                "message": {
                "titleHeader":
                "{insert/language class="ApplicationUserMessageReply" path="/table/message"
                language-de_DE="Nachricht"
                language-en_US="Message"}",
                "classCell": "withFill"
                },
                "user": {
                "titleHeader": "{insert/language class="ApplicationUserMessageReply" path="/table/user"
                language-de_DE="Von"
                language-en_US="From"}"
                },
                "date": {
                "titleHeader": "{insert/language class="ApplicationUserMessageReply" path="/table/date"
                language-de_DE="Datum"
                language-en_US="Date"}",
                "rowParameter": "date"
                }
                }
            </CMS>
        </div>
    </div>
</div>


