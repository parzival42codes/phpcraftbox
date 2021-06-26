<div class="flex-container">
    <div class="flex-container"
         style="flex: 2;flex-direction: column;">
        <div class="card-container card-container--shadow"
             style="flex: 1;">
            <div class="card-container-header">
                {insert/language class="ApplicationAdministrationUserGroupEdit" path="/form/title" import="application"
                language-de_DE="Bearbeitung der Benutzergruppen"
                language-en_US="User Group Edit"}
            </div>
            <div class="card-container-content"
                 id="ApplicationAdministrationUserGroupEdit-edit">
                {create/form form="GroupEdit" name="Header"}

                <div class="flex-container"
                     style="flex-direction: column;">
                    <div class="flex-container-sub">
                        <div class="flex-container-sub-item label">{create/form form="GroupEdit" name="groupData" get="label"}</div>
                        <div class="flex-container-sub-item"
                             style="flex: 2;">{create/form form="GroupEdit" name="groupData"}</div>
                        <div class="flex-container-sub-item info">{create/form form="GroupEdit" name="groupData" get="info"}</div>
                    </div>

                    <div class="flex-container-sub">
                        <div class="flex-container-sub-item">{create/form form="GroupEdit" name="Footer"}</div>
                    </div>

                </div>

            </div>
        </div>

    </div>
    <div style="flex: 1;">
        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationAdministrationUserGroupEdit" path="/form/title/rights" import="application"
                language-de_DE="Zugriffsrechte der Gruppe"
                language-en_US="User Group Access"}
            </div>
            <div class="card-container-content" style="overflow: auto;">
                <div class="flex-container-sub">
                    <div class="flex-container-sub-item"
                         style="flex: 2;">{create/form form="GroupEdit" name="groupAccess"}</div>
                </div>
            </div>
        </div>

        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationAdministrationUserGroupEdit" path="/template/takeAccess"
                language-de_DE="Zugriffsrechte Übernhemen"
                language-en_US="Take Group Access"}
            </div>
            <div class="card-container-content">
                <div class="flex-container-sub">
                    {create/form form="GroupTake" name="Header"}
                    {create/form form="GroupTake" name="take"}
                    {create/form form="GroupTake" name="Footer"}
                </div>
            </div>
        </div>

        <div class="card-container card-container--shadow">
            <div class="card-container-header">
                {insert/language class="ApplicationAdministrationUserGroupEdit" path="/template/deleteUserGroup"
                language-de_DE="Gruppe löschen"
                language-en_US="Delete User Group"}
            </div>
            <div class="card-container-content">
                <div class="flex-container-sub">
                    {create/form form="GroupDelete" name="Header"}
                    {create/form form="GroupDelete" name="delete"}
                    {create/form form="GroupDelete" name="Footer"}
                </div>
            </div>
        </div>
    </div>
</div>
