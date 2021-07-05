<div class="flex-container"
     style="flex: 2;flex-direction: row;">
    <div class="card-container card-container--shadow"
         style="flex: 2;">
        <div class="card-container-header">
            {insert/language class="ApplicationAdministrationContentEdit" path="/form/title"
            language-de_DE="Bearbeitung es Inhalts Benutzergruppen"
            language-en_US="Content Edit"}
        </div>
        <div class="card-container-content"
             id="ApplicationAdministrationContent-edit">


            <div class="flex-container"
                 style="flex-direction: column;">
                <div class="flex-container-sub">
                    <div class="flex-container-sub-item label">{create/form form="ContentEditContentEdit" name="contentContent" get="label"}</div>
                    <div class="flex-container-sub-item"
                         style="flex: 6;">{create/form form="ContentEditContentEdit" name="contentContent"}</div>
                    <div class="flex-container-sub-item info">{create/form form="ContentEditContentEdit" name="contentContent" get="info"}</div>
                </div>

                <CMS function="historyEditFormFooter"
                     call="foo">
                    <div class="flex-container-sub">
                        <div class="flex-container-sub-item">  {create/form form="ContentEditContentEdit" name="Header"}{create/form form="ContentEditContentEdit" name="Footer"}</div>
                    </div>
                </CMS>

            </div>

        </div>
    </div>
    <div class="flex-container"
         style="flex-direction: column;">
        <div class="card-container card-container--shadow"
             style="flex: 1;">
            <div class="card-container-header">
                {insert/language class="ApplicationAdministrationContentEdit" path="/index"
                language-de_DE="Index"
                language-en_US="Index"}
            </div>
            <div class="card-container-content">
                {$index}
            </div>
            <div class="card-container-footer">
                {$indexRefresh}
            </div>
        </div>
        <div class="card-container card-container--shadow"
             style="flex: 1;">
            <div class="card-container-header">
                {insert/language class="ApplicationAdministrationContentEdit" path="/form/title"
                language-de_DE="Identifikation"
                language-en_US="Identification"}
            </div>
            <div class="card-container-content"
                 id="ApplicationAdministrationContent-edit">

                <div class="flex-container"
                     style="flex-direction: column;">
                    <div class="flex-container-sub">
                        <div class="flex-container-sub-item label">{create/form form="ContentEditContentEdit" name="contentIdent" get="label"}</div>
                        <div class="flex-container-sub-item"
                             style="flex: 4;">{create/form form="ContentEditContentEdit" name="contentIdent"}</div>
                        <div class="flex-container-sub-item info">{create/form form="ContentEditContentEdit" name="contentIdent" get="info"}</div>
                    </div>

                </div>

            </div>
        </div>
        <div class="card-container card-container--shadow"
             style="flex: 1;">
            <div class="card-container-header">
                {insert/language class="ApplicationAdministrationContentEdit" path="/form/title/version"
                language-de_DE="Version"
                language-en_US="Vwersion"}
            </div>
            <div class="card-container-content"
                 id="ApplicationAdministrationContent-edit">

                <div class="flex-container"
                     style="flex-direction: column;">
                    <div class="flex-container-sub">
                        <div class="flex-container-sub-item label">{create/form form="ContentEditContentHistory" name="contentHistory" get="label"}</div>
                        <div class="flex-container-sub-item"
                             style="flex: 4;">{create/form form="ContentEditContentHistory" name="contentHistory"}</div>
                    </div>

                    <div class="flex-container-sub">
                        <div class="flex-container-sub-item">{create/form form="ContentEditContentHistory" name="Header"}{create/form form="ContentEditContentHistory" name="Footer"}</div>
                    </div>

                </div>

            </div>
        </div>
        <div class="card-container card-container--shadow"
             style="flex: 1;">
            <div class="card-container-header">
                {insert/language class="ApplicationAdministrationContentEdit" path="/form/title/data"
                language-de_DE="Bearbeitung es Inhalts"
                language-en_US="Content Edit"}
            </div>
            <div class="card-container-content"
                 id="ApplicationAdministrationContent-edit">

                <div class="flex-container"
                     style="flex-direction: column;">
                    <div class="flex-container-sub">
                        <div class="flex-container-sub-item label">{create/form form="ContentEditContentEdit" name="contentData" get="label"}</div>
                        <div class="flex-container-sub-item"
                             style="flex: 4;">{create/form form="ContentEditContentEdit" name="contentData"}</div>
                        <div class="flex-container-sub-item info">{create/form form="ContentEditContentEdit" name="contentData" get="info"}</div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

