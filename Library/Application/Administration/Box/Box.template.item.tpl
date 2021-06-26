<div style="flex: 1;" class="card-container card-container--shadow">
    <div class="card-container-header"> {$crudName}</div>
    <div class="card-container-content">
        <div class="flex-container">
            <div style="flex: 1;">
                <div>
                    <strong>{insert/language class="ApplicationAdministrationUserGroup" path="/item/description" import="application"
                    language-de_DE="Beschreibung"
                    language-en_US="Description"}:</strong>
                    {$crudDescription}
                </div>
            </div>
            <div style="flex: 3;">
                {$groupRights}
            </div>
        </div>
    </div>
    <div class="card-container-footer"><a href="{$linkEdit}"
                                          class="btn">{insert/language class="ApplicationAdministrationUserGroup" path="/item/edit" import="application"
            language-de_DE="Bearbeiten"
            language-en_US="Edit"}</a></div>
</div>
